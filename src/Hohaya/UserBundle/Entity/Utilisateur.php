<?php
namespace Hohaya\UserBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="utilisateur")
 */
class Utilisateur extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

        /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEntree", type="datetime", nullable=true)
     */
    private $dateEntree;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    protected $estActif = false;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    protected $supprimer = false;

    private $file;
    private $updatedAt;
    private $temp;

    public function __construct()
    {
        parent::__construct();
        $this->dateEntree = new \DateTime();
    }

    public function getWebPhoto()
    {
        return null === $this->photo
            ? null
            : $this->getUploadDir().$this->photo;
    }

    public function getAbsolutePhoto()
    {
        var_dump($this->photo);
        return null === $this->photo
            ? null
            : $this->getUploadRootDir().$this->photo;
    }
    protected function getUploadRootDir()
    {
        // dossier temporaire qui va contenir les pdf concernant la publication
        $dossier = __DIR__.'/../../../web/'.$this->getUploadDir();

//        if (!file_exists($dossier)) {
//            mkdir($dossier, 0777, true);
//        }

        return $dossier;
    }

    protected function getUploadDir()
    {
        return "uploads/Fichiers/Utilisateurs/".$this->getId()."/photo/";
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $this->updatedAt= new \DateTime();
            $filename = sha1(uniqid(mt_rand(), true));
            $this->photo = $filename.'.'. $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }
        $this->getFile()->move($this->getUploadRootDir(), $this->photo);
        if (isset($this->temp)) {
            unlink($this->temp);
            $this->temp = null;
        }
        $this->file = null;
    }

    public function preRemoveUpload()
    {
        $this->temp = $this->getAbsolutePhoto();
    }

    public function removeUpload()
    {
        if (is_file($this->temp)) {
            unlink($this->temp);
        }
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (is_file($this->getAbsolutePhoto())){
            // store the old name to delete after the update
            $this->temp = $this->getAbsolutePhoto();
            $this->photo = null;
        } else {
            $this->photo = 'uploads/Fichiers/Utilisateurs/user-lg.jpg';
        }

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set nom.
     *
     * @param string|null $nom
     *
     * @return Utilisateur
     */
    public function setNom($nom = null)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom.
     *
     * @return string|null
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom.
     *
     * @param string|null $prenom
     *
     * @return Utilisateur
     */
    public function setPrenom($prenom = null)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom.
     *
     * @return string|null
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set photo.
     *
     * @param string|null $photo
     *
     * @return Utilisateur
     */
    public function setPhoto($photo = null)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo.
     *
     * @return string|null
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set adresse.
     *
     * @param string|null $adresse
     *
     * @return Utilisateur
     */
    public function setAdresse($adresse = null)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse.
     *
     * @return string|null
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set dateEntree.
     *
     * @param \DateTime|null $dateEntree
     *
     * @return Utilisateur
     */
    public function setDateEntree($dateEntree = null)
    {
        $this->dateEntree = $dateEntree;

        return $this;
    }

    /**
     * Get dateEntree.
     *
     * @return \DateTime|null
     */
    public function getDateEntree()
    {
        return $this->dateEntree;
    }

    /**
     * Set estActif.
     *
     * @param bool $estActif
     *
     * @return Utilisateur
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
     * Set supprimer.
     *
     * @param bool $supprimer
     *
     * @return Utilisateur
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
     * Add userlog.
     *
     * @param \GestransportBundle\Entity\UserLog $userlog
     *
     * @return Utilisateur
     */
    public function addUserlog(\GestransportBundle\Entity\UserLog $userlog)
    {
        $this->userlogs[] = $userlog;

        return $this;
    }

    /**
     * Remove userlog.
     *
     * @param \GestransportBundle\Entity\UserLog $userlog
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeUserlog(\GestransportBundle\Entity\UserLog $userlog)
    {
        return $this->userlogs->removeElement($userlog);
    }

    /**
     * Get userlogs.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserlogs()
    {
        return $this->userlogs;
    }
}
