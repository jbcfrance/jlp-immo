<?php

namespace JLP\CoreBundle\Entity;

/**
 * Passerelle
 */
class Passerelle
{
    /**
     * @var integer
     */
    private $passerelleId;

    /**
     * @var \DateTime
     */
    private $passerelleDate;

    /**
     * @var integer
     */
    private $passerelleLog;

    /**
     * @var integer
     */
    private $passerelleNbannonceajouter;

    /**
     * @var integer
     */
    private $passerelleNbannoncesuppr;

    /**
     * @var integer
     */
    private $passerelleNbphotomaj;

    /**
     * @var integer
     */
    private $passerelleNbannoncetraite;

    /**
     * @var integer
     */
    private $passerelleStatut;


    /**
     * Get passerelleId
     *
     * @return integer
     */
    public function getPasserelleId()
    {
        return $this->passerelleId;
    }

    /**
     * Set passerelleDate
     *
     * @param \DateTime $passerelleDate
     *
     * @return Passerelle
     */
    public function setPasserelleDate($passerelleDate)
    {
        $this->passerelleDate = $passerelleDate;

        return $this;
    }

    /**
     * Get passerelleDate
     *
     * @return \DateTime
     */
    public function getPasserelleDate()
    {
        return $this->passerelleDate;
    }

    /**
     * Set passerelleLog
     *
     * @param integer $passerelleLog
     *
     * @return Passerelle
     */
    public function setPasserelleLog($passerelleLog)
    {
        $this->passerelleLog = $passerelleLog;

        return $this;
    }

    /**
     * Get passerelleLog
     *
     * @return integer
     */
    public function getPasserelleLog()
    {
        return $this->passerelleLog;
    }

    /**
     * Set passerelleNbannonceajouter
     *
     * @param integer $passerelleNbannonceajouter
     *
     * @return Passerelle
     */
    public function setPasserelleNbannonceajouter($passerelleNbannonceajouter)
    {
        $this->passerelleNbannonceajouter = $passerelleNbannonceajouter;

        return $this;
    }

    /**
     * Get passerelleNbannonceajouter
     *
     * @return integer
     */
    public function getPasserelleNbannonceajouter()
    {
        return $this->passerelleNbannonceajouter;
    }

    /**
     * Set passerelleNbannoncesuppr
     *
     * @param integer $passerelleNbannoncesuppr
     *
     * @return Passerelle
     */
    public function setPasserelleNbannoncesuppr($passerelleNbannoncesuppr)
    {
        $this->passerelleNbannoncesuppr = $passerelleNbannoncesuppr;

        return $this;
    }

    /**
     * Get passerelleNbannoncesuppr
     *
     * @return integer
     */
    public function getPasserelleNbannoncesuppr()
    {
        return $this->passerelleNbannoncesuppr;
    }

    /**
     * Set passerelleNbphotomaj
     *
     * @param integer $passerelleNbphotomaj
     *
     * @return Passerelle
     */
    public function setPasserelleNbphotomaj($passerelleNbphotomaj)
    {
        $this->passerelleNbphotomaj = $passerelleNbphotomaj;

        return $this;
    }

    /**
     * Get passerelleNbphotomaj
     *
     * @return integer
     */
    public function getPasserelleNbphotomaj()
    {
        return $this->passerelleNbphotomaj;
    }

    /**
     * Set passerelleNbannoncetraite
     *
     * @param integer $passerelleNbannoncetraite
     *
     * @return Passerelle
     */
    public function setPasserelleNbannoncetraite($passerelleNbannoncetraite)
    {
        $this->passerelleNbannoncetraite = $passerelleNbannoncetraite;

        return $this;
    }

    /**
     * Get passerelleNbannoncetraite
     *
     * @return integer
     */
    public function getPasserelleNbannoncetraite()
    {
        return $this->passerelleNbannoncetraite;
    }

    /**
     * Set passerelleStatut
     *
     * @param integer $passerelleStatut
     *
     * @return Passerelle
     */
    public function setPasserelleStatut($passerelleStatut)
    {
        $this->passerelleStatut = $passerelleStatut;

        return $this;
    }

    /**
     * Get passerelleStatut
     *
     * @return integer
     */
    public function getPasserelleStatut()
    {
        return $this->passerelleStatut;
    }
}

