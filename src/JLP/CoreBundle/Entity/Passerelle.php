<?php

namespace JLP\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Passerelle
 * 
 * @ORM\Table(name="passerelle")
 * @ORM\Entity(repositoryClass="JLP\CoreBundle\Repository\PasserelleRepository")
 */
class Passerelle
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var integer
     * 
     * @ORM\Column(name="log", type="integer")
     */
    private $log;

    /**
     * @var integer
     * 
     * @ORM\Column(name="nbAnnonceAjouter", type="integer")
     */
    private $nbAnnonceAjouter;

    /**
     * @var integer
     * 
     * @ORM\Column(name="nbAnnonceSuppr", type="integer")
     */
    private $nbAnnonceSuppr;

    /**
     * @var integer
     * 
     * @ORM\Column(name="nbPhotoMaj", type="integer")
     */
    private $nbPhotoMaj;

    /**
     * @var integer
     * 
     * @ORM\Column(name="NbAnnonceTraite", type="integer")
     */
    private $nbAnnonceTraite;

    /**
     * @var integer
     * 
     * @ORM\Column(name="Statut", type="integer")
     */
    private $statut;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Passerelle
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set log
     *
     * @param integer $log
     *
     * @return Passerelle
     */
    public function setLog($log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Get log
     *
     * @return integer
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Set nbAnnonceAjouter
     *
     * @param integer $nbAnnonceAjouter
     *
     * @return Passerelle
     */
    public function setNbAnnonceAjouter($nbAnnonceAjouter)
    {
        $this->nbAnnonceAjouter = $nbAnnonceAjouter;

        return $this;
    }

    /**
     * Get nbAnnonceAjouter
     *
     * @return integer
     */
    public function getNbAnnonceAjouter()
    {
        return $this->nbAnnonceAjouter;
    }

    /**
     * Set nbAnnonceSuppr
     *
     * @param integer $nbAnnonceSuppr
     *
     * @return Passerelle
     */
    public function setNbAnnonceSuppr($nbAnnonceSuppr)
    {
        $this->nbAnnonceSuppr = $nbAnnonceSuppr;

        return $this;
    }

    /**
     * Get nbAnnonceSuppr
     *
     * @return integer
     */
    public function getNbAnnonceSuppr()
    {
        return $this->nbAnnonceSuppr;
    }

    /**
     * Set nbPhotoMaj
     *
     * @param integer $nbPhotoMaj
     *
     * @return Passerelle
     */
    public function setNbPhotoMaj($nbPhotoMaj)
    {
        $this->nbPhotoMaj = $nbPhotoMaj;

        return $this;
    }

    /**
     * Get nbPhotoMaj
     *
     * @return integer
     */
    public function getNbPhotoMaj()
    {
        return $this->nbPhotoMaj;
    }

    /**
     * Set nbAnnonceTraite
     *
     * @param integer $nbAnnonceTraite
     *
     * @return Passerelle
     */
    public function setNbAnnonceTraite($nbAnnonceTraite)
    {
        $this->NbAnnonceTraite = $nbAnnonceTraite;

        return $this;
    }

    /**
     * Get nbAnnonceTraite
     *
     * @return integer
     */
    public function getNbAnnonceTraite()
    {
        return $this->NbAnnonceTraite;
    }

    /**
     * Set statut
     *
     * @param integer $statut
     *
     * @return Passerelle
     */
    public function setStatut($statut)
    {
        $this->Statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return integer
     */
    public function getStatut()
    {
        return $this->Statut;
    }
}
