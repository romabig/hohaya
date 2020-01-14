<?php

namespace HohayaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Menu
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="HohayaBundle\Repository\MenuRepository")
 */
class Menu
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    protected $estActif = false;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    protected $masque = false;

    /**
     * @var string
     *
     * @ORM\Column(name="route", type="string", length=255)
     */
    private $route;

    /**
     * @var int
     *
     * @ORM\Column(name="ordreAffichage", type="integer")
     */
    private $ordreAffichage;

    /**
     * Un menu a plusieurs sous menus.
     * @ORM\OneToMany(targetEntity="SMenu", mappedBy="menu", cascade={"persist"}, orphanRemoval = true)
     */
    private $smenus;

    /**
     * Une publication a 0 ou plusieurs images.
     * @ORM\OneToMany(targetEntity="Publication", mappedBy="menu", cascade={"persist"}, orphanRemoval = true)
     */
    private $publications;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    protected $supprimer = false;

    public function __construct() {
        $this->smenus = new ArrayCollection();
        $this->publications = new ArrayCollection();
    }


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
     * Set titre.
     *
     * @param string $titre
     *
     * @return Menu
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre.
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Add smenu.
     *
     * @param \MicaBundle\Entity\SMenu $smenu
     *
     * @return Menu
     */
    public function addSmenu(\MicaBundle\Entity\SMenu $smenu)
    {
        $this->smenus[] = $smenu;

        return $this;
    }

    /**
     * Remove smenu.
     *
     * @param \MicaBundle\Entity\SMenu $smenu
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeSmenu(\MicaBundle\Entity\SMenu $smenu)
    {
        return $this->smenus->removeElement($smenu);
    }

    /**
     * Get smenus.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSmenus()
    {
        return $this->smenus;
    }

    /**
     * Add publication.
     *
     * @param \MicaBundle\Entity\Publication $publication
     *
     * @return Menu
     */
    public function addPublication(\MicaBundle\Entity\Publication $publication)
    {
        $this->publications[] = $publication;

        return $this;
    }

    /**
     * Remove publication.
     *
     * @param \MicaBundle\Entity\Publication $publication
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePublication(\MicaBundle\Entity\Publication $publication)
    {
        return $this->publications->removeElement($publication);
    }

    /**
     * Get publications.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPublications()
    {
        return $this->publications;
    }

    /**
     * Set estActif.
     *
     * @param bool $estActif
     *
     * @return Menu
     */
    public function setEstActif($estActif)
    {
        $this->estActif = $estActif;

        return $this;
    }

    /**
     * Get estActif.
     *
     * @return bool
     */
    public function getEstActif()
    {
        return $this->estActif;
    }

    /**
     * Set route.
     *
     * @param string $route
     *
     * @return Menu
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route.
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set masque.
     *
     * @param bool $masque
     *
     * @return Menu
     */
    public function setMasque($masque)
    {
        $this->masque = $masque;

        return $this;
    }

    /**
     * Get masque.
     *
     * @return bool
     */
    public function getMasque()
    {
        return $this->masque;
    }

    /**
     * Set supprimer.
     *
     * @param bool $supprimer
     *
     * @return Menu
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
     * Set ordreAffichage.
     *
     * @param int $ordreAffichage
     *
     * @return Menu
     */
    public function setOrdreAffichage($ordreAffichage)
    {
        $this->ordreAffichage = $ordreAffichage;

        return $this;
    }

    /**
     * Get ordreAffichage.
     *
     * @return int
     */
    public function getOrdreAffichage()
    {
        return $this->ordreAffichage;
    }
}
