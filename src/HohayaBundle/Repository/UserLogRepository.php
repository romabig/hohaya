<?php

namespace HohayaBundle\Repository;

/**
 * UserLogRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserLogRepository extends \Doctrine\ORM\EntityRepository
{
	public function listeHistorique()
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT u, us FROM HohayaBundle:UserLog u
		        JOIN u.utilisateur us
                WHERE u.supprimer = 0
		        ORDER BY u.id DESC"
            )
            ->getResult();
    }
}