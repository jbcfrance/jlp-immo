<?php

namespace JLP\CoreBundle\Entity;

/**
 * Negociateur
 */
class Negociateur
{
    /**
     * @var integer
     */
    private $negociateurId;

    /**
     * @var integer
     */
    private $negociateurAgenceId;

    /**
     * @var string
     */
    private $prenomnegociateur;

    /**
     * @var string
     */
    private $nomnegociateur;

    /**
     * @var integer
     */
    private $telephonenegociateur;

    /**
     * @var string
     */
    private $emailnegociateur;


    /**
     * Get negociateurId
     *
     * @return integer
     */
    public function getNegociateurId()
    {
        return $this->negociateurId;
    }

    /**
     * Set negociateurAgenceId
     *
     * @param integer $negociateurAgenceId
     *
     * @return Negociateur
     */
    public function setNegociateurAgenceId($negociateurAgenceId)
    {
        $this->negociateurAgenceId = $negociateurAgenceId;

        return $this;
    }

    /**
     * Get negociateurAgenceId
     *
     * @return integer
     */
    public function getNegociateurAgenceId()
    {
        return $this->negociateurAgenceId;
    }

    /**
     * Set prenomnegociateur
     *
     * @param string $prenomnegociateur
     *
     * @return Negociateur
     */
    public function setPrenomnegociateur($prenomnegociateur)
    {
        $this->prenomnegociateur = $prenomnegociateur;

        return $this;
    }

    /**
     * Get prenomnegociateur
     *
     * @return string
     */
    public function getPrenomnegociateur()
    {
        return $this->prenomnegociateur;
    }

    /**
     * Set nomnegociateur
     *
     * @param string $nomnegociateur
     *
     * @return Negociateur
     */
    public function setNomnegociateur($nomnegociateur)
    {
        $this->nomnegociateur = $nomnegociateur;

        return $this;
    }

    /**
     * Get nomnegociateur
     *
     * @return string
     */
    public function getNomnegociateur()
    {
        return $this->nomnegociateur;
    }

    /**
     * Set telephonenegociateur
     *
     * @param integer $telephonenegociateur
     *
     * @return Negociateur
     */
    public function setTelephonenegociateur($telephonenegociateur)
    {
        $this->telephonenegociateur = $telephonenegociateur;

        return $this;
    }

    /**
     * Get telephonenegociateur
     *
     * @return integer
     */
    public function getTelephonenegociateur()
    {
        return $this->telephonenegociateur;
    }

    /**
     * Set emailnegociateur
     *
     * @param string $emailnegociateur
     *
     * @return Negociateur
     */
    public function setEmailnegociateur($emailnegociateur)
    {
        $this->emailnegociateur = $emailnegociateur;

        return $this;
    }

    /**
     * Get emailnegociateur
     *
     * @return string
     */
    public function getEmailnegociateur()
    {
        return $this->emailnegociateur;
    }
}

