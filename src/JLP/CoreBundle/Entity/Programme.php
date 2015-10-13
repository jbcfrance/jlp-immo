<?php

namespace JLP\CoreBundle\Entity;

/**
 * Programme
 */
class Programme
{
    /**
     * @var integer
     */
    private $programmeId;

    /**
     * @var string
     */
    private $programmeTitre;

    /**
     * @var string
     */
    private $programmeTitreColor;

    /**
     * @var string
     */
    private $programmeDescriptionFr;

    /**
     * @var string
     */
    private $programmeDescriptionEn;

    /**
     * @var integer
     */
    private $programmePartenaire;

    /**
     * @var string
     */
    private $programmeIdentifiant;


    /**
     * Get programmeId
     *
     * @return integer
     */
    public function getProgrammeId()
    {
        return $this->programmeId;
    }

    /**
     * Set programmeTitre
     *
     * @param string $programmeTitre
     *
     * @return Programme
     */
    public function setProgrammeTitre($programmeTitre)
    {
        $this->programmeTitre = $programmeTitre;

        return $this;
    }

    /**
     * Get programmeTitre
     *
     * @return string
     */
    public function getProgrammeTitre()
    {
        return $this->programmeTitre;
    }

    /**
     * Set programmeTitreColor
     *
     * @param string $programmeTitreColor
     *
     * @return Programme
     */
    public function setProgrammeTitreColor($programmeTitreColor)
    {
        $this->programmeTitreColor = $programmeTitreColor;

        return $this;
    }

    /**
     * Get programmeTitreColor
     *
     * @return string
     */
    public function getProgrammeTitreColor()
    {
        return $this->programmeTitreColor;
    }

    /**
     * Set programmeDescriptionFr
     *
     * @param string $programmeDescriptionFr
     *
     * @return Programme
     */
    public function setProgrammeDescriptionFr($programmeDescriptionFr)
    {
        $this->programmeDescriptionFr = $programmeDescriptionFr;

        return $this;
    }

    /**
     * Get programmeDescriptionFr
     *
     * @return string
     */
    public function getProgrammeDescriptionFr()
    {
        return $this->programmeDescriptionFr;
    }

    /**
     * Set programmeDescriptionEn
     *
     * @param string $programmeDescriptionEn
     *
     * @return Programme
     */
    public function setProgrammeDescriptionEn($programmeDescriptionEn)
    {
        $this->programmeDescriptionEn = $programmeDescriptionEn;

        return $this;
    }

    /**
     * Get programmeDescriptionEn
     *
     * @return string
     */
    public function getProgrammeDescriptionEn()
    {
        return $this->programmeDescriptionEn;
    }

    /**
     * Set programmePartenaire
     *
     * @param integer $programmePartenaire
     *
     * @return Programme
     */
    public function setProgrammePartenaire($programmePartenaire)
    {
        $this->programmePartenaire = $programmePartenaire;

        return $this;
    }

    /**
     * Get programmePartenaire
     *
     * @return integer
     */
    public function getProgrammePartenaire()
    {
        return $this->programmePartenaire;
    }

    /**
     * Set programmeIdentifiant
     *
     * @param string $programmeIdentifiant
     *
     * @return Programme
     */
    public function setProgrammeIdentifiant($programmeIdentifiant)
    {
        $this->programmeIdentifiant = $programmeIdentifiant;

        return $this;
    }

    /**
     * Get programmeIdentifiant
     *
     * @return string
     */
    public function getProgrammeIdentifiant()
    {
        return $this->programmeIdentifiant;
    }
}

