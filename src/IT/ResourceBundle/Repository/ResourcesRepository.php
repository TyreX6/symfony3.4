<?php

namespace IT\ResourceBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ResourcesRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class ResourcesRepository extends EntityRepository
{
    public function getResourcesGroupedByCateg() {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('r')
            ->from('ITResourceBundle:Ressource', 'r')
            ->groupBy('r.category') ;
        return $qb->getQuery()->getResult();
    }
}
