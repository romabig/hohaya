<?php

namespace HohayaBundle\Repository;

/**
 * SSMenuRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SSMenuRepository extends \Doctrine\ORM\EntityRepository
{

	 public function listeOrganisme()
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT ssm FROM HohayaBundle:SSMenu ssm
                JOIN ssm.smenu sm
                WHERE sm.id = :id
                AND ssm.supprimer = 0
                ORDER BY ssm.ordreAffichage  DESC"
            )->setParameter('id', 14)
            ->setMaxResults (3)
            ->setFirstResult(0)
            ->getResult();
    }
}
