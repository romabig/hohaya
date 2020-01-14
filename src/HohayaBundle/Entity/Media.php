<?php

namespace HohayaBundle\Entity;

/**
 * Media
 */
class Media
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $chemin;

    /**
     * @var string
     */
    private $contenu;

    /**
     * @var string
     */
    private $extension;


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
     * Set chemin.
     *
     * @param string $chemin
     *
     * @return Media
     */
    public function setChemin($chemin)
    {
        $this->chemin = $chemin;

        return $this;
    }

    /**
     * Get chemin.
     *
     * @return string
     */
    public function getChemin()
    {
        return $this->chemin;
    }

    /**
     * Set contenu.
     *
     * @param string $contenu
     *
     * @return Media
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
     * Set extension.
     *
     * @param string $extension
     *
     * @return Media
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension.
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }
}
