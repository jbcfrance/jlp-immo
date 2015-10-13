<?php

namespace JLP\CoreBundle\Entity;

/**
 * Agence
 */
class Agence
{
    /**
     * @var integer
     */
    private $agenceId;

    /**
     * @var string
     */
    private $raisonsocialeagence;

    /**
     * @var string
     */
    private $enseigneagence;

    /**
     * @var string
     */
    private $adresseagence;

    /**
     * @var integer
     */
    private $codepostalagence;

    /**
     * @var string
     */
    private $villeagence;

    /**
     * @var string
     */
    private $paysagence;

    /**
     * @var integer
     */
    private $telephoneagence;

    /**
     * @var integer
     */
    private $faxagence;

    /**
     * @var string
     */
    private $emailagence;

    /**
     * @var string
     */
    private $sitewebagence;


    /**
     * Get agenceId
     *
     * @return integer
     */
    public function getAgenceId()
    {
        return $this->agenceId;
    }

    /**
     * Set raisonsocialeagence
     *
     * @param string $raisonsocialeagence
     *
     * @return Agence
     */
    public function setRaisonsocialeagence($raisonsocialeagence)
    {
        $this->raisonsocialeagence = $raisonsocialeagence;

        return $this;
    }

    /**
     * Get raisonsocialeagence
     *
     * @return string
     */
    public function getRaisonsocialeagence()
    {
        return $this->raisonsocialeagence;
    }

    /**
     * Set enseigneagence
     *
     * @param string $enseigneagence
     *
     * @return Agence
     */
    public function setEnseigneagence($enseigneagence)
    {
        $this->enseigneagence = $enseigneagence;

        return $this;
    }

    /**
     * Get enseigneagence
     *
     * @return string
     */
    public function getEnseigneagence()
    {
        return $this->enseigneagence;
    }

    /**
     * Set adresseagence
     *
     * @param string $adresseagence
     *
     * @return Agence
     */
    public function setAdresseagence($adresseagence)
    {
        $this->adresseagence = $adresseagence;

        return $this;
    }

    /**
     * Get adresseagence
     *
     * @return string
     */
    public function getAdresseagence()
    {
        return $this->adresseagence;
    }

    /**
     * Set codepostalagence
     *
     * @param integer $codepostalagence
     *
     * @return Agence
     */
    public function setCodepostalagence($codepostalagence)
    {
        $this->codepostalagence = $codepostalagence;

        return $this;
    }

    /**
     * Get codepostalagence
     *
     * @return integer
     */
    public function getCodepostalagence()
    {
        return $this->codepostalagence;
    }

    /**
     * Set villeagence
     *
     * @param string $villeagence
     *
     * @return Agence
     */
    public function setVilleagence($villeagence)
    {
        $this->villeagence = $villeagence;

        return $this;
    }

    /**
     * Get villeagence
     *
     * @return string
     */
    public function getVilleagence()
    {
        return $this->villeagence;
    }

    /**
     * Set paysagence
     *
     * @param string $paysagence
     *
     * @return Agence
     */
    public function setPaysagence($paysagence)
    {
        $this->paysagence = $paysagence;

        return $this;
    }

    /**
     * Get paysagence
     *
     * @return string
     */
    public function getPaysagence()
    {
        return $this->paysagence;
    }

    /**
     * Set telephoneagence
     *
     * @param integer $telephoneagence
     *
     * @return Agence
     */
    public function setTelephoneagence($telephoneagence)
    {
        $this->telephoneagence = $telephoneagence;

        return $this;
    }

    /**
     * Get telephoneagence
     *
     * @return integer
     */
    public function getTelephoneagence()
    {
        return $this->telephoneagence;
    }

    /**
     * Set faxagence
     *
     * @param integer $faxagence
     *
     * @return Agence
     */
    public function setFaxagence($faxagence)
    {
        $this->faxagence = $faxagence;

        return $this;
    }

    /**
     * Get faxagence
     *
     * @return integer
     */
    public function getFaxagence()
    {
        return $this->faxagence;
    }

    /**
     * Set emailagence
     *
     * @param string $emailagence
     *
     * @return Agence
     */
    public function setEmailagence($emailagence)
    {
        $this->emailagence = $emailagence;

        return $this;
    }

    /**
     * Get emailagence
     *
     * @return string
     */
    public function getEmailagence()
    {
        return $this->emailagence;
    }

    /**
     * Set sitewebagence
     *
     * @param string $sitewebagence
     *
     * @return Agence
     */
    public function setSitewebagence($sitewebagence)
    {
        $this->sitewebagence = $sitewebagence;

        return $this;
    }

    /**
     * Get sitewebagence
     *
     * @return string
     */
    public function getSitewebagence()
    {
        return $this->sitewebagence;
    }
}

