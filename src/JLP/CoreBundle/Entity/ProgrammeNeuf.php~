<?php

namespace JLP\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    * @ORM\PrePersist()
    * @ORM\PreUpdate()
    */
   public function preUpload()
   {
       // Si jamais il n'y a pas de fichier (champ facultatif)
     if (null === $this->file) {
         return;
     }

     // Le nom du fichier est son id, on doit juste stocker également son extension
     // Pour faire propre, on devrait renommer cet attribut en « extension », plutôt que « url »
     $this->url = $this->file->guessExtension();

     // Et on génère l'attribut alt de la balise <img>, à la valeur du nom du fichier sur le PC de l'internaute
     $this->alt = $this->file->getClientOriginalName();
   }

   /**
    * @ORM\PostPersist()
    * @ORM\PostUpdate()
    */
   public function upload()
   {
       // Si jamais il n'y a pas de fichier (champ facultatif)
     if (null === $this->file) {
         return;
     }

     // Si on avait un ancien fichier, on le supprime
     if (null !== $this->tempFilename) {
         $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;
         if (file_exists($oldFile)) {
             unlink($oldFile);
         }
     }

     // On déplace le fichier envoyé dans le répertoire de notre choix
     $this->file->move(
       $this->getUploadRootDir(), // Le répertoire de destination
       $this->id.'.'.$this->url   // Le nom du fichier à créer, ici « id.extension »
     );
   }

   /**
    * @ORM\PreRemove()
    */
   public function preRemoveUpload()
   {
       // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
     $this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->url;
   }

   /**
    * @ORM\PostRemove()
    */
   public function removeUpload()
   {
       // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
     if (file_exists($this->tempFilename)) {
         // On supprime le fichier
       unlink($this->tempFilename);
     }
   }

    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur
     return 'uploads/img';
    }

    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
     return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    public function getWebPath()
    {
        return $this->getUploadDir().'/'.$this->getId().'.'.$this->getUrl();
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
