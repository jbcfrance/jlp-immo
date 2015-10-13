<?php

namespace JLP\CoreBundle\Entity;

/**
 * Diapointro
 */
class Diapointro
{
    /**
     * @var integer
     */
    private $diapoId;

    /**
     * @var string
     */
    private $diapoFile;


    /**
     * Get diapoId
     *
     * @return integer
     */
    public function getDiapoId()
    {
        return $this->diapoId;
    }

    /**
     * Set diapoFile
     *
     * @param string $diapoFile
     *
     * @return Diapointro
     */
    public function setDiapoFile($diapoFile)
    {
        $this->diapoFile = $diapoFile;

        return $this;
    }

    /**
     * Get diapoFile
     *
     * @return string
     */
    public function getDiapoFile()
    {
        return $this->diapoFile;
    }
}

