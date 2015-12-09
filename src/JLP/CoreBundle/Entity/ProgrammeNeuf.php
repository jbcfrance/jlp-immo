<?php

namespace JLP\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * ProgrammeNeuf
 * 
 * @ORM\Table(name="programme_neuf")
 * @ORM\Entity(repositoryClass="JLP\CoreBundle\Repository\ProgrammeNeufRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ProgrammeNeuf
{
    /**
     * @var integer
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
     * @var string
     *
     * @ORM\Column(name="titreColor", type="string", length=255)
     */
    private $titreColor;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionFr", type="text")
     */
    private $descriptionFr;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionEn", type="text")
     */
    private $descriptionEn;

    /**
     * @var integer
     * 
     * @ORM\Column(name="partenaire", type="integer")
     */
    private $partenaire;

    /**
     * @var string
     *
     * @ORM\Column(name="identifiant", type="string", length=255)
     */
    private $identifiant;
    
    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;
    
    /**
    * @ORM\Column(name="alt", type="string", length=255)
    */
    private $alt;
    
    private $file;

    private $tempFilename;

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file)
    {
        $this->file = $file;

    // On vérifie si on avait déjà un fichier pour cette entité
    if (null !== $this->url) {
        // On sauvegarde l'extension du fichier pour le supprimer plus tard
      $this->tempFilename = $this->url;

      // On réinitialise les valeurs des attributs url et alt
      $this->url = null;
        $this->alt = null;
    }
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return ProgrammeNeuf
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set titreColor
     *
     * @param string $titreColor
     *
     * @return ProgrammeNeuf
     */
    public function setTitreColor($titreColor)
    {
        $this->titreColor = $titreColor;

        return $this;
    }

    /**
     * Get titreColor
     *
     * @return string
     */
    public function getTitreColor()
    {
        return $this->titreColor;
    }

    /**
     * Set descriptionFr
     *
     * @param string $descriptionFr
     *
     * @return ProgrammeNeuf
     */
    public function setDescriptionFr($descriptionFr)
    {
        $this->descriptionFr = $descriptionFr;

        return $this;
    }

    /**
     * Get descriptionFr
     *
     * @return string
     */
    public function getDescriptionFr()
    {
        return $this->descriptionFr;
    }

    /**
     * Set descriptionEn
     *
     * @param string $descriptionEn
     *
     * @return ProgrammeNeuf
     */
    public function setDescriptionEn($descriptionEn)
    {
        $this->descriptionEn = $descriptionEn;

        return $this;
    }

    /**
     * Get descriptionEn
     *
     * @return string
     */
    public function getDescriptionEn()
    {
        return $this->descriptionEn;
    }

    /**
     * Set partenaire
     *
     * @param integer $partenaire
     *
     * @return ProgrammeNeuf
     */
    public function setPartenaire($partenaire)
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    /**
     * Get partenaire
     *
     * @return integer
     */
    public function getPartenaire()
    {
        return $this->partenaire;
    }

    /**
     * Set identifiant
     *
     * @param string $identifiant
     *
     * @return ProgrammeNeuf
     */
    public function setIdentifiant($identifiant)
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    /**
     * Get identifiant
     *
     * @return string
     */
    public function getIdentifiant()
    {
        return $this->identifiant;
    }
    
    /**
     * Set url
     *
     * @param string $url
     *
     * @return ProgrammeNeuf
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return ProgrammeNeuf
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }
}
