<?php

namespace JLP\CoreBundle\Entity;

/**
 * SiteConfig
 */
class SiteConfig
{
    /**
     * @var integer
     */
    private $siteId;

    /**
     * @var string
     */
    private $siteCle;

    /**
     * @var string
     */
    private $siteDesc;

    /**
     * @var string
     */
    private $siteIntroFr;

    /**
     * @var string
     */
    private $siteIntroEn;


    /**
     * Get siteId
     *
     * @return integer
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * Set siteCle
     *
     * @param string $siteCle
     *
     * @return SiteConfig
     */
    public function setSiteCle($siteCle)
    {
        $this->siteCle = $siteCle;

        return $this;
    }

    /**
     * Get siteCle
     *
     * @return string
     */
    public function getSiteCle()
    {
        return $this->siteCle;
    }

    /**
     * Set siteDesc
     *
     * @param string $siteDesc
     *
     * @return SiteConfig
     */
    public function setSiteDesc($siteDesc)
    {
        $this->siteDesc = $siteDesc;

        return $this;
    }

    /**
     * Get siteDesc
     *
     * @return string
     */
    public function getSiteDesc()
    {
        return $this->siteDesc;
    }

    /**
     * Set siteIntroFr
     *
     * @param string $siteIntroFr
     *
     * @return SiteConfig
     */
    public function setSiteIntroFr($siteIntroFr)
    {
        $this->siteIntroFr = $siteIntroFr;

        return $this;
    }

    /**
     * Get siteIntroFr
     *
     * @return string
     */
    public function getSiteIntroFr()
    {
        return $this->siteIntroFr;
    }

    /**
     * Set siteIntroEn
     *
     * @param string $siteIntroEn
     *
     * @return SiteConfig
     */
    public function setSiteIntroEn($siteIntroEn)
    {
        $this->siteIntroEn = $siteIntroEn;

        return $this;
    }

    /**
     * Get siteIntroEn
     *
     * @return string
     */
    public function getSiteIntroEn()
    {
        return $this->siteIntroEn;
    }
}

