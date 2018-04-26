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

    public function rechercheDispositif($keyword) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('d')
            ->from('ITResourceBundle:Dispositif', 'd')
            ->where('d.model LIKE :keyword')
            ->setParameter('keyword', '%'.$keyword.'%') ;
        return $qb->getQuery()->getResult();
    }


}