<?php

namespace HohayaBundle\Repository;
use \Doctrine\ORM\EntityRepository;
use HohayaBundle\Entity\Menu;

/**
 * SMenuRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SMenuRepository extends EntityRepository
{
    public function findAllOrderedById()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT sm FROM HohayaBundle:SMenu sm WHERE sm.supprimer = 0 ORDER BY sm.ordreAffichage ASC'
            )
            ->getResult();
    }

    public function findByMenu($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT sm FROM HohayaBundle:SMenu sm WHERE sm.menu = :id AND sm.supprimer = 0 ORDER BY sm.ordreAffichage ASC'
            )->setParameter('id', $id)
            ->getResult();
    }
}
