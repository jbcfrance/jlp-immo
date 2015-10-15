<?php

namespace JLP\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeImage
 * 
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="JLP\CoreBundleBundle\Entity\TypeImageRepository")
 */
class TypeImage
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
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

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
     * Set typeImage
     *
     * @param string $typeImage
     *
     * @return TypeImage
     */
    public function setTypeImage($typeImage)
    {
        $this->typeImage = $typeImage;

        return $this;
    }

    /**
     * Get typeImage
     *
     * @return string
     */
    public function getTypeImage()
    {
        return $this->typeImage;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return TypeImage
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
