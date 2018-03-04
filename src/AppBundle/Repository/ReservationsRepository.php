<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 26/02/2018
 * Time: 22:20
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
class ReservationsRepository extends EntityRepository
{
    public function getReservationsByDispositive($id) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('r')
            ->from('AppBundle:Reservation', 'r')
            ->innerJoin('r.dispositif', 'd')
            ->where('d.id = :id')
            ->setParameter('id', $id)
        ;
        return $qb->getQuery()->getResult();
    }

}