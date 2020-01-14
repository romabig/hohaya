<?php

namespace HohayaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Publication
 *
 * @ORM\Table(name="publication")
 * @ORM\Entity(repositoryClass="HohayaBundle\Repository\PublicationRepository")
 */
class Publication
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
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    protected $estActif = false;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    protected $estSlide = false;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=16777216, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="titreImage", type="string", length=16777216, nullable=true)
     */
    private $titreImage;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="string", length=16777216, nullable=true)
     */
    private $contenu;

    /**
     * @var string
     *
     * @ORM\Column(name="nomDocument", type="string", length=255, nullable=true)
     */
    private $nomDocument;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nomImage", type="string", length=255, nullable=true)
     */
    private $nomImage;

     /**
     * @var string|null
     *
     * @ORM\Column(name="nomPDF", type="string", length=255, nullable=true)
     */
    private $nomPDF;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255, nullable=true)
     */
    private $icon;

    /**
     * Une publication a 0 ou plusieurs images.
     * @ORM\OneToMany(targetEntity="Photo", mappedBy="publication", cascade={"persist"}, orphanRemoval = true)
     */
    private $photos;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * Plusieurs publications liées à un seul menu.
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="publications", cascade={"persist", "remove" })
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
     */

    private $menu;

    /**
     * Plusieurs publications liées à une seul sous menu.
     * @ORM\ManyToOne(targetEntity="SMenu", inversedBy="publications", cascade={"persist", "remove" })
     * @ORM\JoinColumn(name="smenu_id", referencedColumnName="id")
     */
    private $smenu;

    /**
     * Plusieurs publications liées à une seul sous sous menu.
     * @ORM\ManyToOne(targetEntity="SSMenu", inversedBy="publications", cascade={"persist", "remove" })
     * @ORM\JoinColumn(name="ssmenu_id", referencedColumnName="id")
     */
    private $ssmenu;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    protected $supprimer = false;

    public function __construct() {
        $this->photos = new ArrayCollection();
        $this->urlpublications = new ArrayCollection();
        $this->dateCreation = new \DateTime();
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
     * @return Publication
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
     * Set description.
     *
     * @param string $description
     *
     * @return Publication
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set nomDocument.
     *
     * @param string $nomDocument
     *
     * @return Publication
     */
    public function setNomDocument($nomDocument)
    {
        $this->nomDocument = $nomDocument;

        return $this;
    }

    /**
     * Get nomDocument.
     *
     * @return string
     */
    public function getNomDocument()
    {
        return $this->nomDocument;
    }

    /**
     * Set nomImage.
     *
     * @param string|null $nomImage
     *
     * @return Publication
     */
    public function setNomImage($nomImage = null)
    {
        $this->nomImage = $nomImage;

        return $this;
    }

    /**
     * Get nomImage.
     *
     * @return string|null
     */
    public function getNomImage()
    {
        return $this->nomImage;
    }

    /**
     * Set nomPDF.
     *
     * @param string|null $nomPDF
     *
     * @return Publication
     */
    public function setNomPDF($nomPDF = null)
    {
        $this->nomPDF = $nomPDF;

        return $this;
    }

    /**
     * Get nomPDF.
     *
     * @return string|null
     */
    public function getNomPDF()
    {
        return $this->nomPDF;
    }
    
    /**
     * Add photo.
     *
     * @param \MicaBundle\Entity\Photo $photo
     *
     * @return Publication
     */
    public function addPhoto(\MicaBundle\Entity\Photo $photo)
    {
        $this->photos[] = $photo;

        return $this;
    }

    /**
     * Remove photo.
     *
     * @param \MicaBundle\Entity\Photo $photo
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePhoto(\MicaBundle\Entity\Photo $photo)
    {
        return $this->photos->removeElement($photo);
    }

    /**
     * Get photos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set menu.
     *
     * @param \MicaBundle\Entity\Menu|null $menu
     *
     * @return Publication
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
     * @return Publication
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
     * Set ssmenu.
     *
     * @param \MicaBundle\Entity\SSMenu|null $ssmenu
     *
     * @return Publication
     */
    public function setSsmenu(\MicaBundle\Entity\SSMenu $ssmenu = null)
    {
        $this->ssmenu = $ssmenu;

        return $this;
    }

    /**
     * Get ssmenu.
     *
     * @return \MicaBundle\Entity\SSMenu|null
     */
    public function getSsmenu()
    {
        return $this->ssmenu;
    }

    /**
     * Set contenu.
     *
     * @param string $contenu
     *
     * @return Publication
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu.
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set icon.
     *
     * @param string|null $icon
     *
     * @return Publication
     */
    public function setIcon($icon = null)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon.
     *
     * @return string|null
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set estActif.
     *
     * @param bool $estActif
     *
     * @return Publication
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
     * Set titreImage.
     *
     * @param string|null $titreImage
     *
     * @return Publication
     */
    public function setTitreImage($titreImage = null)
    {
        $this->titreImage = $titreImage;

        return $this;
    }

    /**
     * Get titreImage.
     *
     * @return string|null
     */
    public function getTitreImage()
    {
        return $this->titreImage;
    }

    /**
     * Set supprimer.
     *
     * @param bool $supprimer
     *
     * @return Publication
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
     * Set estSlide.
     *
     * @param bool $estSlide
     *
     * @return Publication
     */
    public function setEstSlide($estSlide)
    {
        $this->estSlide = $estSlide;

        return $this;
    }

    /**
     * Get estSlide.
     *
     * @return bool
     */
    public function getEstSlide()
    {
        return $this->estSlide;
    }


    /**
     * Set dateCreation.
     *
     * @param \DateTime $dateCreation
     *
     * @return Publication
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
     * Add urlpublication.
     *
     * @param \MicaBundle\Entity\UrlPublication $urlpublication
     *
     * @return Publication
     */
    public function addUrlpublication(\MicaBundle\Entity\UrlPublication $urlpublication)
    {
        $this->urlpublications[] = $urlpublication;

        return $this;
    }

    /**
     * Remove urlpublication.
     *
     * @param \MicaBundle\Entity\UrlPublication $urlpublication
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeUrlpublication(\MicaBundle\Entity\UrlPublication $urlpublication)
    {
        return $this->urlpublications->removeElement($urlpublication);
    }

    /**
     * Get urlpublications.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUrlpublications()
    {
        return $this->urlpublications;
    }
}
