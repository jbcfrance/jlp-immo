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
     */
    private $date;

    /**
     * @var integer
     */
    private $log;

    /**
     * @var integer
     */
    private $nbAnnonceAjouter;

    /**
     * @var integer
     */
    private $nbAnnonceSuppr;

    /**
     * @var integer
     */
    private $nbPhotoMaj;

    /**
     * @var integer
     */
    private $NbAnnonceTraite;

    /**
     * @var integer
     */
    private $Statut;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
