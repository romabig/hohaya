<?php

namespace HohayaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * SMenu
 *
 * @ORM\Table(name="smenu")
 * @ORM\Entity(repositoryClass="HohayaBundle\Repository\SMenuRepository")
 */
class SMenu
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
    protected $masque = false;

    /**
     * @var int
     *
     * @ORM\Column(name="ordreAffichage", type="integer")
     */
    private $ordreAffichage;

    /**
     * Plusieurs sous sous menus liés à un seul sous menu.
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="smenus")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
     */
    private $menu;

    /**
     * Un sous menu a plusieurs sous sous menus.
     * @ORM\OneToMany(targetEntity="SSMenu", mappedBy="smenu", cascade={"persist"}, orphanRemoval = true)
     */
    private $ssmenus;

    /**
     * Un sous menu a plusieurs publications.
     * @ORM\OneToMany(targetEntity="Publication", mappedBy="smenu", cascade={"persist"}, orphanRemoval = true)
     */
    private $publications;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    protected $estActif = false;

    /**
     * @var string
     *
     * @ORM\Column(name="route", type="string", length=255)
     */
    private $route;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    protected $supprimer = false;

    public function __construct() {
        $this->ssmenus = new ArrayCollection();
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
     * @return SMenu
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
     * Set menu.
     *
     * @param \MicaBundle\Entity\Menu|null $menu
     *
     * @return SMenu
     */
    public function setMenu(\MicaBundle\Entity\Menu $menu = null)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu.
     *
     * @return \MicaBundle\Entity\Menu|null
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set smenu.
     *
     * @param \MicaBundle\Entity\SMenu|null $smenu
     *
     * @return SMenu
     */
    public function setSmenu(\MicaBundle\Entity\SMenu $smenu = null)
    {
        $this->smenu = $smenu;

        return $this;
    }

    /**
     * Get smenu.
     *
     * @return \MicaBundle\Entity\SMenu|null
     */
    public function getSmenu()
    {
        return $this->smenu;
    }

    /**
     * Add ssmenu.
     *
     * @param \MicaBundle\Entity\SSMenu $ssmenu
     *
     * @return SMenu
     */
    public function addSsmenu(\MicaBundle\Entity\SSMenu $ssmenu)
    {
        $this->ssmenus[] = $ssmenu;

        return $this;
    }

    /**
     * Remove ssmenu.
     *
     * @param \MicaBundle\Entity\SSMenu $ssmenu
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeSsmenu(\MicaBundle\Entity\SSMenu $ssmenu)
    {
        return $this->ssmenus->removeElement($ssmenu);
    }

    /**
     * Get ssmenus.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsmenus()
    {
        return $this->ssmenus;
    }

    /**
     * Add publication.
     *
     * @param \MicaBundle\Entity\Publication $publication
     *
     * @return SMenu
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
     * @return SMenu
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
     * @return SMenu
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
     * @return SMenu
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
     * @return SMenu
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
     * @return SMenu
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
