<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 26/02/2018
 * Time: 22:20
 */

namespace IT\ReservationBundle\Repository;

use DateInterval;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use IT\ReservationBundle\Entity\Regles;
use IT\ReservationBundle\Entity\Reservation;
use Doctrine\ORM\Query\ResultSetMapping;
class ReservationsRepository extends EntityRepository
{
    public function getReservationsByDispositive($id)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('r')
            ->from('ITReservationBundle:Reservation', 'r')
            ->innerJoin('r.dispositif', 'd')
            ->where('d.id = :id')
            ->setParameter('id', $id);
        return $qb->getQuery()->getResult();
    }

    public function rechercheDispositif($keyword)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('r')
            ->from('ITReservationBundle:Reservation', 'r')
            ->innerJoin('r.dispositif', 'd')
            ->where('d.modele LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%');
        return $qb->getQuery()->getResult();
    }

    /**
     * @param $username
     * @param $regles Regles
     * @return int
     */
    public function getActualReservationByUserRawSql($username, $regles)
    {
        $timeout = $regles->getDureeTimeout();
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM `reservation` AS r LEFT JOIN `fos_user` as f ON r.user_id=f.id WHERE f.username='".$username."' AND NOW() > r.date_debut AND NOW() < ADDTIME(r.date_debut,'0 0:$timeout:00.00')";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getIncomingReservationsOrdererByDate()
    {
        $date = new \DateTime(null, new \DateTimeZone("Africa/Tunis"));
        $qb = $this->_em->createQueryBuilder();
        $qb->select('r')
            ->from('ITReservationBundle:Reservation', 'r')
            ->where('r.dateFin >= :now')
            ->orderBy('r.dateDebut', 'ASC')
            ->setParameter('now', $date);
        return $qb->getQuery()->getResult();
    }

    public function getActifReservationsOrdererByDate()
    {
        $date = new \DateTime(null, new \DateTimeZone("Africa/Tunis"));
        $qb = $this->_em->createQueryBuilder();
        $qb->select('r')
            ->from('ITReservationBundle:Reservation', 'r')
            ->where('r.dateFin >= :now')
            ->andWhere('r.dateDebut <= :now')
            ->orderBy('r.dateDebut', 'ASC')
            ->setParameter('now', $date);
        return $qb->getQuery()->getResult();
    }


    /**
     * @param $reservation Reservation
     * @param $regles Regles
     * @return array
     */
    public function verifReservation($reservation, $regles)
    {
        $message = "succé";
        $arrayResult = $this->verifDureeLimite($reservation, $regles);

        if ($arrayResult["success"] === 0) {
            return array("success" => 0, "message" => $arrayResult["message"]);
        }

        $arrayResult = $this->verifNbrMaxJour($reservation, $regles);
        if ($arrayResult["success"] === 0) {
            return array("success" => 0, "message" => $arrayResult["message"]);
        }

        $arrayResult = $this->verifNbrMaxSemaine($reservation, $regles);
        if ($arrayResult["success"] === 0) {
            return array("success" => 0, "message" => $arrayResult["message"]);
        }

        return array("success" => 1, "message" => $message);

    }


    /**
     * @param $reservation Reservation
     * @param $regles Regles
     * @return array
     */
    public function verifDureeLimite($reservation, $regles)
    {
        $message = "";
        $success = null;
        $dureeSec = $regles->getLimDureeReservation() * 3600;
        if (($reservation->getDateFinTimeStamp() - $reservation->getDateDebutTimeStamp()) > $dureeSec) {
            $message = "Durée limite de " . $regles->getLimDureeReservation() . " heures est dépassé";
            $success = 0;
        } else {
            $message = "Régle respecté";
            $success = 1;
        }

        return array("message" => $message, "success" => $success);
    }


    /**
     * @param $reservation Reservation
     * @param $regles Regles
     * @return array
     */
    public function verifNbrMaxJour($reservation, $regles)
    {
        $message = "";
        $success = null;

        //reference problem
        $now = new DateTime($reservation->getDateDebut()->format("Y-m-d H:i:s"));
        $oneDayAgo = new DateTime($reservation->getDateDebut()->format("Y-m-d"));
        //$oneDayAgo = $oneDayAgo->sub(new DateInterval("PT24H"));
        $qb = $this->_em->createQueryBuilder();

        $qb->select('count(r.id)')
            ->from('ITReservationBundle:Reservation', 'r')
            ->where('r.dateDebut BETWEEN :yesterday AND :today')
            ->andWhere('r.user = :user')
            ->andWhere('r.dispositif = :dispositif')
            ->setParameter('user', $reservation->getUser())
            ->setParameter('dispositif', $reservation->getDispositif())
            ->setParameter('today', $now->format('Y-m-d H:i:s'))
            ->setParameter('yesterday', $oneDayAgo->format('Y-m-d H:i:s'));

        $count = (int)$qb->getQuery()->getSingleScalarResult();

        if ($count >= $regles->getNbrLimiteParJour()) {
            $message = "Nombre max de " . $regles->getNbrLimiteParJour() . " réservation par jour est dépassé";
            $success = 0;
        } else {
            $message = "Régle respecté";
            $success = 1;
        }

        return array("message" => $message, "success" => $success);
    }


    /**
     * @param $reservation Reservation
     * @param $regles Regles
     * @return array
     */
    public function verifNbrMaxSemaine($reservation, $regles)
    {
        $message = "";
        $success = null;

        //reference problem
        $now = new DateTime($reservation->getDateDebut()->format("Y-m-d H:i:s"));
        $oneWeekAgo = new DateTime($reservation->getDateDebut()->format("Y-m-d"));
        $oneWeekAgo = $oneWeekAgo->sub(new DateInterval("P7D"));
        $qb = $this->_em->createQueryBuilder();

        $qb->select('count(r.id)')
            ->from('ITReservationBundle:Reservation', 'r')
            ->where('r.dateDebut BETWEEN :lastWeek AND :today')
            ->andWhere('r.user = :user')
            ->andWhere('r.dispositif = :dispositif')
            ->setParameter('user', $reservation->getUser())
            ->setParameter('dispositif', $reservation->getDispositif())
            ->setParameter('today', $now->format('Y-m-d H:i:s'))
            ->setParameter('lastWeek', $oneWeekAgo->format('Y-m-d H:i:s'));

        $count = (int)$qb->getQuery()->getSingleScalarResult();

        if ($count >= $regles->getNbrLimiteParSemaine()) {
            $message = "Nombre max de " . $regles->getNbrLimiteParSemaine() . " réservation par semaine est dépassé";
            $success = 0;
        } else {
            $message = "Régle respecté";
            $success = 1;
        }

        return array("message" => $message, "success" => $success);
    }

}