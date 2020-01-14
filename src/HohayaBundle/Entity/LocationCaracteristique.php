<?php

namespace HohayaBundle\Entity;

/**
 * LocationCaracteristique
 */
class LocationCaracteristique
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $valeur;


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
     * Set valeur.
     *
     * @param string $valeur
     *
     * @return LocationCaracteristique
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur.
     *
     * @return string
     */
    public function getValeur()
    {
        return $this->valeur;
    }
}
