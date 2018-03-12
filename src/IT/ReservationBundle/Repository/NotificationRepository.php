<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 08/03/2018
 * Time: 00:46
 */

namespace IT\ReservationBundle\Repository;


use Doctrine\ORM\EntityRepository;

class NotificationRepository extends EntityRepository
{
    public function getLatestNotifications($number) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('n')
            ->from('ITReservationBundle:Notification', 'n')
            ->where('n.vu = false')
            ->orderBy('n.dateRes', 'ASC')
            ->setMaxResults($number);
        return $qb->getQuery()->getResult();
    }
}