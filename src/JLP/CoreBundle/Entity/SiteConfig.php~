<?php

namespace JLP\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SiteConfig
 * 
 * @ORM\Table(name="site_config")
 * @ORM\Entity(repositoryClass="JLP\CoreBundle\Repository\SiteConfigRepository")
 */
class SiteConfig
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
     * @ORM\Column(name="motCle", type="string", length=255)
     */
    private $motCle;

    /**
     * @var string
     *
     * @ORM\Column(name="desc", type="string", length=255)
     */
    private $desc;

    /**
     * @var text
     *
     * @ORM\Column(name="introFr", type="text")
     */
    private $introFr;

    /**
     * @var text
     *
     * @ORM\Column(name="introEn", type="text")
     */
    private $introEn;

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
     * Set motCle
     *
     * @param string $motCle
     *
     * @return SiteConfig
     */
    public function setMotCle($motCle)
    {
        $this->motCle = $motCle;

        return $this;
    }

    /**
     * Get motCle
     *
     * @return string
     */
    public function getMotCle()
    {
        return $this->motCle;
    }

    /**
     * Set desc
     *
     * @param string $desc
     *
     * @return SiteConfig
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;

        return $this;
    }

    /**
     * Get desc
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Set introFr
     *
     * @param string $introFr
     *
     * @return SiteConfig
     */
    public function setIntroFr($introFr)
    {
        $this->introFr = $introFr;

        return $this;
    }

    /**
     * Get introFr
     *
     * @return string
     */
    public function getIntroFr()
    {
        return $this->introFr;
    }

    /**
     * Set introEn
     *
     * @param string $introEn
     *
     * @return SiteConfig
     */
    public function setIntroEn($introEn)
    {
        $this->introEn = $introEn;

        return $this;
    }

    /**
     * Get introEn
     *
     * @return string
     */
    public function getIntroEn()
    {
        return $this->introEn;
    }
}
