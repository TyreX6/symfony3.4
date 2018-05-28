<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 26/02/2018
 * Time: 22:20
 */
namespace IT\ResourceBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
class DispositifsRepository extends EntityRepository
{

    public function getOsList() {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('d.os')
            ->distinct(true)
            ->from('ITResourceBundle:Dispositif', 'd');
        return $qb->getQuery()->getResult();
    }

    public function getResolutionsList() {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('d.resolution')
            ->distinct(true)
            ->from('ITResourceBundle:Dispositif', 'd');
        return $qb->getQuery()->getResult();
    }

    public function rechercheDispositif($keyword) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('d')
            ->from('ITResourceBundle:Dispositif', 'd')
            ->where('d.model LIKE :keyword')
            ->setParameter('keyword', '%'.$keyword.'%') ;
        return $qb->getQuery()->getResult();
    }

    public function getReservedDevice() {
        $date = new \DateTime(null, new \DateTimeZone("Africa/Tunis"));
        $qb = $this->_em->createQueryBuilder();
        $qb->select('d')
            ->from('ITResourceBundle:Dispositif', 'd')
            ->innerJoin('d.reservations', 'r')
            ->where('r.dateFin >= :now')
            ->andWhere('r.dateDebut <= :now')
            ->orderBy('r.dateDebut', 'ASC')
            ->setParameter('now', $date);
        return $qb->getQuery()->getResult();
    }




}