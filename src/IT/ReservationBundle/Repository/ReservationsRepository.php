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
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use IT\ReservationBundle\Entity\Regles;
use Doctrine\ORM\Query\ResultSetMapping;
use IT\ReservationBundle\Entity\Reservation as Reservation;
use IT\ResourceBundle\Entity\Dispositif as Dispositif;

class ReservationsRepository extends EntityRepository
{
    public function getReservationsByDispositive($id)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('r')
            ->from('ITReservationBundle:Reservation', 'r')
            ->innerJoin('r.ressource', 'rs')
            ->where('rs.id = :id')
            ->setParameter('id', $id);
        return $qb->getQuery()->getResult();
    }

    public function getReservationsByUser($id)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('r')
            ->from('ITReservationBundle:Reservation', 'r')
            ->innerJoin('r.user', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id);
        return $qb->getQuery()->getResult();
    }

    public function getReservationsByDeviceUuid($uuid)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('r')
            ->from('ITReservationBundle:Reservation', 'r')
            ->join('r.ressource', 'rs')
            ->where('rs.deviceUUID = :uuid ')
            ->setParameter('uuid', $uuid);
        return $qb->getQuery()->getResult();
    }

    public function rechercheDispositif($keyword)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('r')
            ->from('ITReservationBundle:Reservation', 'r')
            ->innerJoin('r.ressource', 'rs')
            ->leftJoin('ITResourceBundle:Dispositif', 'd', 'WITH', 'rs.id = d.id')
            ->where('d.model LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%');
        return $qb->getQuery()->getResult();
    }

    /**
     * @param $UUID
     * @param $username
     * @param $regles Regles
     * @return array
     * @throws DBALException
     */
    public function getActualReservationByUserRawSql($UUID, $username, $regles)
    {
        $timeout = $regles->getDureeTimeout();
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT r.id,r.date_debut,r.date_fin FROM `reservation` AS r LEFT JOIN `fos_user` as f ON r.user_id=f.id LEFT JOIN `dispositif` d on r.`ressource_id` = d.`id` WHERE f.username='" . $username . "' AND d.device_uuid='" . $UUID . "' AND NOW() > r.date_debut ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * @param $UUID
     * @param $username
     * @param $regles Regles
     * @return array
     * @throws DBALException
     */
    public function getActualReservationByUserRawSqlBackup($UUID, $username, $regles)
    {
        $timeout = $regles->getDureeTimeout();
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT r.id,r.date_debut,r.date_fin FROM `reservation` AS r LEFT JOIN `fos_user` as f ON r.user_id=f.id LEFT JOIN `dispositif` d on r.`ressource_id` = d.`id` WHERE f.username='" . $username . "' AND d.device_uuid='" . $UUID . "' AND NOW() > r.date_debut AND NOW() < ADDTIME(r.date_debut,'0 0:$timeout:00.00')";
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

    public function getDeviceReservationsOrdererByDate($resource)
    {
        $date = new \DateTime(null, new \DateTimeZone("Africa/Tunis"));
        $qb = $this->_em->createQueryBuilder();
        $qb->select('r')
            ->from('ITReservationBundle:Reservation', 'r')
            ->where('r.dateFin >= :now')
            ->andWhere('r.ressource = :resource')
            ->orderBy('r.dateDebut', 'ASC')
            ->setParameter('resource',$resource)
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

    public function getActifReservationsByDevice($id)
    {
        $date = new \DateTime(null, new \DateTimeZone("Africa/Tunis"));
        $qb = $this->_em->createQueryBuilder();
        $qb->select('r')
            ->from('ITReservationBundle:Reservation', 'r')
            ->leftJoin('r.ressource', 'rs')
            ->where('r.dateFin >= :now')
            ->andWhere('r.dateDebut <= :now')
            ->andWhere('rs.id = :id')
            ->orderBy('r.dateDebut', 'ASC')
            ->setParameter('now', $date)
            ->setParameter('id', $id);
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

        try {
            $arrayResult = $this->verifCrossReservation($reservation);
        } catch (NonUniqueResultException $e) {
        }
        if ($arrayResult["success"] === 0) {
            return array("success" => 0, "message" => $arrayResult["message"]);
        }

        try {
            $arrayResult = $this->verifyMaxPerDay($reservation, $regles);
            if ($arrayResult["success"] === 0) {
                return array("success" => 0, "message" => $arrayResult["message"]);
            }
        } catch (NonUniqueResultException $e) {
        }


        try {
            $arrayResult = $this->verifyMaxPerWeek($reservation, $regles);
        } catch (NonUniqueResultException $e) {
        }
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
        if (($reservation->getDateFin()->getTimestamp() - $reservation->getDateDebut()->getTimestamp()) > $dureeSec) {
            $message = "Durée limite de " . $regles->getLimDureeReservation() . " heures est dépassé";
            $success = 0;
        } else {
            $message = "Régle respecté";
            $success = 1;
        }

        return array("message" => $message, "success" => $success);
    }

    /**
     * Verify if an existant reservation cros the new reservation
     * passed in parameter or not.
     * Of course do not count the existant reservation
     *
     * @param $reservation Reservation
     * @return array
     */
    public function verifCrossReservation($reservation)
    {
        $message = "";
        $success = null;
        $id = $reservation->getId();
        if ($id == null) {
            $id = 0;
        }

        $qb = $this->_em->createQueryBuilder();
        $qb->select('count(r.id)')
            ->from('ITReservationBundle:Reservation', 'r')
            ->where('r.ressource = :resource')
            ->andWhere('r.id != :id')
            ->andWhere($qb->expr()->orX(
                $qb->expr()->between(
                    'r.dateDebut',
                    ':start',
                    ':end'),
                $qb->expr()->between(
                    'r.dateFin',
                    ':start',
                    ':end')
            ))
            ->setParameter('resource', $reservation->getRessource())
            ->setParameter('start', $reservation->getDateDebut()->format('Y-m-d H:i:s'))
            ->setParameter('end', $reservation->getDateFin()->format('Y-m-d H:i:s'))
            ->setParameter('id', $id);

        try {
            $count = (int)$qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            $count = 0;
        }

        if ($count > 0) {
            $message = "Réservation déja existe";
            $success = 0;
        } else {
            $message = "Régle respecté";
            $success = 1;
        }

        return array("message" => $message, "success" => $success);
    }


    /**
     * Verify the maximum number of reservations in a day
     * Of course do not count the existant reservation
     *
     * @param $reservation Reservation
     * @param $regles Regles
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function verifyMaxPerDay($reservation, $regles)
    {
        $message = "";
        $success = null;
        $id = $reservation->getId();
        if ($id == null) {
            $id = 0;
        }


        //reference problem
        $now = new DateTime($reservation->getDateDebut()->format("Y-m-d H:i:s"));
        $dayStart = new DateTime($reservation->getDateDebut()->format("Y-m-d"));
        $dayEnd = new DateTime($reservation->getDateDebut()->format("Y-m-d"));
        $dayEnd->setTime(23, 59, 59);
        //$oneDayAgo = $oneDayAgo->sub(new DateInterval("PT24H"));
        $qb = $this->_em->createQueryBuilder();

        $qb->select('count(r.id)')
            ->from('ITReservationBundle:Reservation', 'r')
            ->where('r.dateDebut BETWEEN :start AND :end')
            ->andWhere('r.user = :user')
            ->andWhere('r.ressource = :resource')
            ->andWhere('r.id != :id')
            ->setParameter('user', $reservation->getUser())
            ->setParameter('resource', $reservation->getRessource())
            ->setParameter('start', $dayStart->format('Y-m-d H:i:s'))
            ->setParameter('end', $dayEnd->format('Y-m-d H:i:s'))
            ->setParameter('id', $id);

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
     * Verify the maximum number of reservations in a week
     * Of course do not count the existant reservation
     *
     * @param $reservation Reservation
     * @param $regles Regles
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function verifyMaxPerWeek($reservation, $regles)
    {
        $message = "";
        $success = null;

        $id = $reservation->getId();
        if ($id == null) {
            $id = 0;
        }

        //reference problem
        $now = new DateTime($reservation->getDateDebut()->format("Y-m-d H:i:s"));
        $oneWeekAgo = new DateTime($reservation->getDateDebut()->format("Y-m-d"));
        $oneWeekAgo = $oneWeekAgo->sub(new DateInterval("P7D"));
        $qb = $this->_em->createQueryBuilder();

        $qb->select('count(r.id)')
            ->from('ITReservationBundle:Reservation', 'r')
            ->where('r.dateDebut BETWEEN :lastWeek AND :today')
            ->andWhere('r.user = :user')
            ->andWhere('r.ressource = :resource')
            ->andWhere('r.id != :reservation_id')
            ->setParameter('user', $reservation->getUser())
            ->setParameter('resource', $reservation->getRessource())
            ->setParameter('today', $now->format('Y-m-d H:i:s'))
            ->setParameter('lastWeek', $oneWeekAgo->format('Y-m-d H:i:s'))
            ->setParameter('reservation_id', $id);

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