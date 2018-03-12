<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 26/02/2018
 * Time: 22:20
 */
namespace IT\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
class UsersRepository extends EntityRepository
{
    public function getUsersByRole($role) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from($this->_entityName, 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"'.$role.'"%');

        return $qb->getQuery()->getResult();
    }

}