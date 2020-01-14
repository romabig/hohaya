<?php

namespace HohayaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserLog
 *
 * @ORM\Table(name="userlog")
 * @ORM\Entity(repositoryClass="HohayaBundle\Repository\UserLogRepository")
 */
class UserLog
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="entityName", type="string", length=255)
     */
    private $entityName;

    /**
     * @var int
     *
     * @ORM\Column(name="entityId", type="string", length=255)
     */
    private $entityId;

    /**
     * @var string
     *
     * @ORM\Column(name="userAction", type="string", length=255)
     */
    private $userAction;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    protected $supprimer = false;

    /**
     * Plusieurs historiques ou userlog liées à une seul utilisateur.
     * @ORM\ManyToOne(targetEntity="Mica\UserBundle\Entity\Utilisateur", inversedBy="userlogs", cascade={"persist", "remove" })
     * @ORM\JoinColumn(name="UtilisateurId", referencedColumnName="id")
     */
    private $utilisateur;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set entityName.
     *
     * @param string $entityName
     *
     * @return UserLog
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;

        return $this;
    }

    /**
     * Get entityName.
     *
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * Set entityId.
     *
     * @param string $entityId
     *
     * @return UserLog
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get entityId.
     *
     * @return string
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set userAction.
     *
     * @param string $userAction
     *
     * @return UserLog
     */
    public function setUserAction($userAction)
    {
        $this->userAction = $userAction;

        return $this;
    }

    /**
     * Get userAction.
     *
     * @return string
     */
    public function getUserAction()
    {
        return $this->userAction;
    }

    /**
     * Set dateCreation.
     *
     * @param \DateTime $dateCreation
     *
     * @return UserLog
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation.
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set supprimer.
     *
     * @param bool $supprimer
     *
     * @return UserLog
     */
    public function setSupprimer($supprimer)
    {
        $this->supprimer = $supprimer;

        return $this;
    }

    /**
     * Get supprimer.
     *
     * @return bool
     */
    public function getSupprimer()
    {
        return $this->supprimer;
    }

    /**
     * Set utilisateur.
     *
     * @param \Mica\UserBundle\Entity\Utilisateur|null $utilisateur
     *
     * @return UserLog
     */
    public function setUtilisateur(\Mica\UserBundle\Entity\Utilisateur $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur.
     *
     * @return \Mica\UserBundle\Entity\Utilisateur|null
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }
}
