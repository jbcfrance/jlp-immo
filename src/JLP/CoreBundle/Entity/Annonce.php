<?php

namespace JLP\CoreBundle\Entity;

/**
 * Annonce
 */
class Annonce
{
    /**
     * @var integer
     */
    private $annonceId;

    /**
     * @var integer
     */
    private $annonceAgenceId;

    /**
     * @var integer
     */
    private $annonceNegociateurId;

    /**
     * @var string
     */
    private $statusAnnonce;

    /**
     * @var string
     */
    private $reference;

    /**
     * @var integer
     */
    private $nummandat;

    /**
     * @var string
     */
    private $typemandat;

    /**
     * @var integer
     */
    private $categorieoffre;

    /**
     * @var string
     */
    private $typebien;

    /**
     * @var string
     */
    private $categorie;

    /**
     * @var integer
     */
    private $datecreation;

    /**
     * @var integer
     */
    private $datemodification;

    /**
     * @var integer
     */
    private $datedebutmandat;

    /**
     * @var integer
     */
    private $dateecheancemandat;

    /**
     * @var integer
     */
    private $datedisponibiliteouliberation;

    /**
     * @var string
     */
    private $adresse;

    /**
     * @var integer
     */
    private $codepostalpublic;

    /**
     * @var string
     */
    private $villepublique;

    /**
     * @var string
     */
    private $villeaafficher;

    /**
     * @var string
     */
    private $pays;

    /**
     * @var string
     */
    private $quartier;

    /**
     * @var string
     */
    private $environnement;

    /**
     * @var string
     */
    private $proximite;

    /**
     * @var string
     */
    private $transports;

    /**
     * @var integer
     */
    private $montant;

    /**
     * @var integer
     */
    private $charges;

    /**
     * @var integer
     */
    private $loyer;

    /**
     * @var integer
     */
    private $depotgarantie;

    /**
     * @var integer
     */
    private $fraisdivers;

    /**
     * @var integer
     */
    private $loyergarage;

    /**
     * @var integer
     */
    private $agetete;

    /**
     * @var string
     */
    private $typerente;

    /**
     * @var integer
     */
    private $taxehabitation;

    /**
     * @var integer
     */
    private $taxefonciere;

    /**
     * @var integer
     */
    private $fraisdenotairereduits;

    /**
     * @var integer
     */
    private $pieces;

    /**
     * @var integer
     */
    private $chambres;

    /**
     * @var integer
     */
    private $sdb;

    /**
     * @var integer
     */
    private $nbsallesdeau;

    /**
     * @var integer
     */
    private $nbwc;

    /**
     * @var integer
     */
    private $nbparking;

    /**
     * @var integer
     */
    private $nbgarages;

    /**
     * @var integer
     */
    private $niveaux;

    /**
     * @var integer
     */
    private $nbetages;

    /**
     * @var integer
     */
    private $etage;

    /**
     * @var integer
     */
    private $surface;

    /**
     * @var integer
     */
    private $surfacecarrezouhabitable;

    /**
     * @var integer
     */
    private $surfaceterrain;

    /**
     * @var integer
     */
    private $surfacesejour;

    /**
     * @var integer
     */
    private $surfaceterrasse;

    /**
     * @var integer
     */
    private $surfacebalcon;

    /**
     * @var integer
     */
    private $acceshandicape;

    /**
     * @var integer
     */
    private $alarme;

    /**
     * @var integer
     */
    private $ascenseur;

    /**
     * @var integer
     */
    private $balcon;

    /**
     * @var integer
     */
    private $bureau;

    /**
     * @var integer
     */
    private $cave;

    /**
     * @var integer
     */
    private $cellier;

    /**
     * @var integer
     */
    private $dependances;

    /**
     * @var integer
     */
    private $dressing;

    /**
     * @var integer
     */
    private $gardien;

    /**
     * @var integer
     */
    private $interphone;

    /**
     * @var integer
     */
    private $lotissement;

    /**
     * @var integer
     */
    private $meuble;

    /**
     * @var integer
     */
    private $mitoyenne;

    /**
     * @var integer
     */
    private $piscine;

    /**
     * @var integer
     */
    private $terrasse;

    /**
     * @var string
     */
    private $anciennete;

    /**
     * @var integer
     */
    private $anneeconstruction;

    /**
     * @var integer
     */
    private $exposition;

    /**
     * @var string
     */
    private $typechauffage;

    /**
     * @var string
     */
    private $naturechauffage;

    /**
     * @var string
     */
    private $modechauffage;

    /**
     * @var string
     */
    private $typecuisine;

    /**
     * @var string
     */
    private $coupdecoeur;

    /**
     * @var string
     */
    private $texte;

    /**
     * @var string
     */
    private $textanglais;

    /**
     * @var string
     */
    private $urlvisitevirtuelle;

    /**
     * @var string
     */
    private $photocoeur;

    /**
     * @var string
     */
    private $photomedium;

    /**
     * @var string
     */
    private $listephotoorig;

    /**
     * @var string
     */
    private $photothumb;

    /**
     * @var string
     */
    private $photoorigmd;

    /**
     * @var integer
     */
    private $consommationenergie;

    /**
     * @var integer
     */
    private $emissionges;

    /**
     * @var string
     */
    private $photoorigmd5;


    /**
     * Get annonceId
     *
     * @return integer
     */
    public function getAnnonceId()
    {
        return $this->annonceId;
    }

    /**
     * Set annonceAgenceId
     *
     * @param integer $annonceAgenceId
     *
     * @return Annonce
     */
    public function setAnnonceAgenceId($annonceAgenceId)
    {
        $this->annonceAgenceId = $annonceAgenceId;

        return $this;
    }

    /**
     * Get annonceAgenceId
     *
     * @return integer
     */
    public function getAnnonceAgenceId()
    {
        return $this->annonceAgenceId;
    }

    /**
     * Set annonceNegociateurId
     *
     * @param integer $annonceNegociateurId
     *
     * @return Annonce
     */
    public function setAnnonceNegociateurId($annonceNegociateurId)
    {
        $this->annonceNegociateurId = $annonceNegociateurId;

        return $this;
    }

    /**
     * Get annonceNegociateurId
     *
     * @return integer
     */
    public function getAnnonceNegociateurId()
    {
        return $this->annonceNegociateurId;
    }

    /**
     * Set statusAnnonce
     *
     * @param string $statusAnnonce
     *
     * @return Annonce
     */
    public function setStatusAnnonce($statusAnnonce)
    {
        $this->statusAnnonce = $statusAnnonce;

        return $this;
    }

    /**
     * Get statusAnnonce
     *
     * @return string
     */
    public function getStatusAnnonce()
    {
        return $this->statusAnnonce;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Annonce
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set nummandat
     *
     * @param integer $nummandat
     *
     * @return Annonce
     */
    public function setNummandat($nummandat)
    {
        $this->nummandat = $nummandat;

        return $this;
    }

    /**
     * Get nummandat
     *
     * @return integer
     */
    public function getNummandat()
    {
        return $this->nummandat;
    }

    /**
     * Set typemandat
     *
     * @param string $typemandat
     *
     * @return Annonce
     */
    public function setTypemandat($typemandat)
    {
        $this->typemandat = $typemandat;

        return $this;
    }

    /**
     * Get typemandat
     *
     * @return string
     */
    public function getTypemandat()
    {
        return $this->typemandat;
    }

    /**
     * Set categorieoffre
     *
     * @param integer $categorieoffre
     *
     * @return Annonce
     */
    public function setCategorieoffre($categorieoffre)
    {
        $this->categorieoffre = $categorieoffre;

        return $this;
    }

    /**
     * Get categorieoffre
     *
     * @return integer
     */
    public function getCategorieoffre()
    {
        return $this->categorieoffre;
    }

    /**
     * Set typebien
     *
     * @param string $typebien
     *
     * @return Annonce
     */
    public function setTypebien($typebien)
    {
        $this->typebien = $typebien;

        return $this;
    }

    /**
     * Get typebien
     *
     * @return string
     */
    public function getTypebien()
    {
        return $this->typebien;
    }

    /**
     * Set categorie
     *
     * @param string $categorie
     *
     * @return Annonce
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set datecreation
     *
     * @param integer $datecreation
     *
     * @return Annonce
     */
    public function setDatecreation($datecreation)
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    /**
     * Get datecreation
     *
     * @return integer
     */
    public function getDatecreation()
    {
        return $this->datecreation;
    }

    /**
     * Set datemodification
     *
     * @param integer $datemodification
     *
     * @return Annonce
     */
    public function setDatemodification($datemodification)
    {
        $this->datemodification = $datemodification;

        return $this;
    }

    /**
     * Get datemodification
     *
     * @return integer
     */
    public function getDatemodification()
    {
        return $this->datemodification;
    }

    /**
     * Set datedebutmandat
     *
     * @param integer $datedebutmandat
     *
     * @return Annonce
     */
    public function setDatedebutmandat($datedebutmandat)
    {
        $this->datedebutmandat = $datedebutmandat;

        return $this;
    }

    /**
     * Get datedebutmandat
     *
     * @return integer
     */
    public function getDatedebutmandat()
    {
        return $this->datedebutmandat;
    }

    /**
     * Set dateecheancemandat
     *
     * @param integer $dateecheancemandat
     *
     * @return Annonce
     */
    public function setDateecheancemandat($dateecheancemandat)
    {
        $this->dateecheancemandat = $dateecheancemandat;

        return $this;
    }

    /**
     * Get dateecheancemandat
     *
     * @return integer
     */
    public function getDateecheancemandat()
    {
        return $this->dateecheancemandat;
    }

    /**
     * Set datedisponibiliteouliberation
     *
     * @param integer $datedisponibiliteouliberation
     *
     * @return Annonce
     */
    public function setDatedisponibiliteouliberation($datedisponibiliteouliberation)
    {
        $this->datedisponibiliteouliberation = $datedisponibiliteouliberation;

        return $this;
    }

    /**
     * Get datedisponibiliteouliberation
     *
     * @return integer
     */
    public function getDatedisponibiliteouliberation()
    {
        return $this->datedisponibiliteouliberation;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Annonce
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set codepostalpublic
     *
     * @param integer $codepostalpublic
     *
     * @return Annonce
     */
    public function setCodepostalpublic($codepostalpublic)
    {
        $this->codepostalpublic = $codepostalpublic;

        return $this;
    }

    /**
     * Get codepostalpublic
     *
     * @return integer
     */
    public function getCodepostalpublic()
    {
        return $this->codepostalpublic;
    }

    /**
     * Set villepublique
     *
     * @param string $villepublique
     *
     * @return Annonce
     */
    public function setVillepublique($villepublique)
    {
        $this->villepublique = $villepublique;

        return $this;
    }

    /**
     * Get villepublique
     *
     * @return string
     */
    public function getVillepublique()
    {
        return $this->villepublique;
    }

    /**
     * Set villeaafficher
     *
     * @param string $villeaafficher
     *
     * @return Annonce
     */
    public function setVilleaafficher($villeaafficher)
    {
        $this->villeaafficher = $villeaafficher;

        return $this;
    }

    /**
     * Get villeaafficher
     *
     * @return string
     */
    public function getVilleaafficher()
    {
        return $this->villeaafficher;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Annonce
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set quartier
     *
     * @param string $quartier
     *
     * @return Annonce
     */
    public function setQuartier($quartier)
    {
        $this->quartier = $quartier;

        return $this;
    }

    /**
     * Get quartier
     *
     * @return string
     */
    public function getQuartier()
    {
        return $this->quartier;
    }

    /**
     * Set environnement
     *
     * @param string $environnement
     *
     * @return Annonce
     */
    public function setEnvironnement($environnement)
    {
        $this->environnement = $environnement;

        return $this;
    }

    /**
     * Get environnement
     *
     * @return string
     */
    public function getEnvironnement()
    {
        return $this->environnement;
    }

    /**
     * Set proximite
     *
     * @param string $proximite
     *
     * @return Annonce
     */
    public function setProximite($proximite)
    {
        $this->proximite = $proximite;

        return $this;
    }

    /**
     * Get proximite
     *
     * @return string
     */
    public function getProximite()
    {
        return $this->proximite;
    }

    /**
     * Set transports
     *
     * @param string $transports
     *
     * @return Annonce
     */
    public function setTransports($transports)
    {
        $this->transports = $transports;

        return $this;
    }

    /**
     * Get transports
     *
     * @return string
     */
    public function getTransports()
    {
        return $this->transports;
    }

    /**
     * Set montant
     *
     * @param integer $montant
     *
     * @return Annonce
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return integer
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set charges
     *
     * @param integer $charges
     *
     * @return Annonce
     */
    public function setCharges($charges)
    {
        $this->charges = $charges;

        return $this;
    }

    /**
     * Get charges
     *
     * @return integer
     */
    public function getCharges()
    {
        return $this->charges;
    }

    /**
     * Set loyer
     *
     * @param integer $loyer
     *
     * @return Annonce
     */
    public function setLoyer($loyer)
    {
        $this->loyer = $loyer;

        return $this;
    }

    /**
     * Get loyer
     *
     * @return integer
     */
    public function getLoyer()
    {
        return $this->loyer;
    }

    /**
     * Set depotgarantie
     *
     * @param integer $depotgarantie
     *
     * @return Annonce
     */
    public function setDepotgarantie($depotgarantie)
    {
        $this->depotgarantie = $depotgarantie;

        return $this;
    }

    /**
     * Get depotgarantie
     *
     * @return integer
     */
    public function getDepotgarantie()
    {
        return $this->depotgarantie;
    }

    /**
     * Set fraisdivers
     *
     * @param integer $fraisdivers
     *
     * @return Annonce
     */
    public function setFraisdivers($fraisdivers)
    {
        $this->fraisdivers = $fraisdivers;

        return $this;
    }

    /**
     * Get fraisdivers
     *
     * @return integer
     */
    public function getFraisdivers()
    {
        return $this->fraisdivers;
    }

    /**
     * Set loyergarage
     *
     * @param integer $loyergarage
     *
     * @return Annonce
     */
    public function setLoyergarage($loyergarage)
    {
        $this->loyergarage = $loyergarage;

        return $this;
    }

    /**
     * Get loyergarage
     *
     * @return integer
     */
    public function getLoyergarage()
    {
        return $this->loyergarage;
    }

    /**
     * Set agetete
     *
     * @param integer $agetete
     *
     * @return Annonce
     */
    public function setAgetete($agetete)
    {
        $this->agetete = $agetete;

        return $this;
    }

    /**
     * Get agetete
     *
     * @return integer
     */
    public function getAgetete()
    {
        return $this->agetete;
    }

    /**
     * Set typerente
     *
     * @param string $typerente
     *
     * @return Annonce
     */
    public function setTyperente($typerente)
    {
        $this->typerente = $typerente;

        return $this;
    }

    /**
     * Get typerente
     *
     * @return string
     */
    public function getTyperente()
    {
        return $this->typerente;
    }

    /**
     * Set taxehabitation
     *
     * @param integer $taxehabitation
     *
     * @return Annonce
     */
    public function setTaxehabitation($taxehabitation)
    {
        $this->taxehabitation = $taxehabitation;

        return $this;
    }

    /**
     * Get taxehabitation
     *
     * @return integer
     */
    public function getTaxehabitation()
    {
        return $this->taxehabitation;
    }

    /**
     * Set taxefonciere
     *
     * @param integer $taxefonciere
     *
     * @return Annonce
     */
    public function setTaxefonciere($taxefonciere)
    {
        $this->taxefonciere = $taxefonciere;

        return $this;
    }

    /**
     * Get taxefonciere
     *
     * @return integer
     */
    public function getTaxefonciere()
    {
        return $this->taxefonciere;
    }

    /**
     * Set fraisdenotairereduits
     *
     * @param integer $fraisdenotairereduits
     *
     * @return Annonce
     */
    public function setFraisdenotairereduits($fraisdenotairereduits)
    {
        $this->fraisdenotairereduits = $fraisdenotairereduits;

        return $this;
    }

    /**
     * Get fraisdenotairereduits
     *
     * @return integer
     */
    public function getFraisdenotairereduits()
    {
        return $this->fraisdenotairereduits;
    }

    /**
     * Set pieces
     *
     * @param integer $pieces
     *
     * @return Annonce
     */
    public function setPieces($pieces)
    {
        $this->pieces = $pieces;

        return $this;
    }

    /**
     * Get pieces
     *
     * @return integer
     */
    public function getPieces()
    {
        return $this->pieces;
    }

    /**
     * Set chambres
     *
     * @param integer $chambres
     *
     * @return Annonce
     */
    public function setChambres($chambres)
    {
        $this->chambres = $chambres;

        return $this;
    }

    /**
     * Get chambres
     *
     * @return integer
     */
    public function getChambres()
    {
        return $this->chambres;
    }

    /**
     * Set sdb
     *
     * @param integer $sdb
     *
     * @return Annonce
     */
    public function setSdb($sdb)
    {
        $this->sdb = $sdb;

        return $this;
    }

    /**
     * Get sdb
     *
     * @return integer
     */
    public function getSdb()
    {
        return $this->sdb;
    }

    /**
     * Set nbsallesdeau
     *
     * @param integer $nbsallesdeau
     *
     * @return Annonce
     */
    public function setNbsallesdeau($nbsallesdeau)
    {
        $this->nbsallesdeau = $nbsallesdeau;

        return $this;
    }

    /**
     * Get nbsallesdeau
     *
     * @return integer
     */
    public function getNbsallesdeau()
    {
        return $this->nbsallesdeau;
    }

    /**
     * Set nbwc
     *
     * @param integer $nbwc
     *
     * @return Annonce
     */
    public function setNbwc($nbwc)
    {
        $this->nbwc = $nbwc;

        return $this;
    }

    /**
     * Get nbwc
     *
     * @return integer
     */
    public function getNbwc()
    {
        return $this->nbwc;
    }

    /**
     * Set nbparking
     *
     * @param integer $nbparking
     *
     * @return Annonce
     */
    public function setNbparking($nbparking)
    {
        $this->nbparking = $nbparking;

        return $this;
    }

    /**
     * Get nbparking
     *
     * @return integer
     */
    public function getNbparking()
    {
        return $this->nbparking;
    }

    /**
     * Set nbgarages
     *
     * @param integer $nbgarages
     *
     * @return Annonce
     */
    public function setNbgarages($nbgarages)
    {
        $this->nbgarages = $nbgarages;

        return $this;
    }

    /**
     * Get nbgarages
     *
     * @return integer
     */
    public function getNbgarages()
    {
        return $this->nbgarages;
    }

    /**
     * Set niveaux
     *
     * @param integer $niveaux
     *
     * @return Annonce
     */
    public function setNiveaux($niveaux)
    {
        $this->niveaux = $niveaux;

        return $this;
    }

    /**
     * Get niveaux
     *
     * @return integer
     */
    public function getNiveaux()
    {
        return $this->niveaux;
    }

    /**
     * Set nbetages
     *
     * @param integer $nbetages
     *
     * @return Annonce
     */
    public function setNbetages($nbetages)
    {
        $this->nbetages = $nbetages;

        return $this;
    }

    /**
     * Get nbetages
     *
     * @return integer
     */
    public function getNbetages()
    {
        return $this->nbetages;
    }

    /**
     * Set etage
     *
     * @param integer $etage
     *
     * @return Annonce
     */
    public function setEtage($etage)
    {
        $this->etage = $etage;

        return $this;
    }

    /**
     * Get etage
     *
     * @return integer
     */
    public function getEtage()
    {
        return $this->etage;
    }

    /**
     * Set surface
     *
     * @param integer $surface
     *
     * @return Annonce
     */
    public function setSurface($surface)
    {
        $this->surface = $surface;

        return $this;
    }

    /**
     * Get surface
     *
     * @return integer
     */
    public function getSurface()
    {
        return $this->surface;
    }

    /**
     * Set surfacecarrezouhabitable
     *
     * @param integer $surfacecarrezouhabitable
     *
     * @return Annonce
     */
    public function setSurfacecarrezouhabitable($surfacecarrezouhabitable)
    {
        $this->surfacecarrezouhabitable = $surfacecarrezouhabitable;

        return $this;
    }

    /**
     * Get surfacecarrezouhabitable
     *
     * @return integer
     */
    public function getSurfacecarrezouhabitable()
    {
        return $this->surfacecarrezouhabitable;
    }

    /**
     * Set surfaceterrain
     *
     * @param integer $surfaceterrain
     *
     * @return Annonce
     */
    public function setSurfaceterrain($surfaceterrain)
    {
        $this->surfaceterrain = $surfaceterrain;

        return $this;
    }

    /**
     * Get surfaceterrain
     *
     * @return integer
     */
    public function getSurfaceterrain()
    {
        return $this->surfaceterrain;
    }

    /**
     * Set surfacesejour
     *
     * @param integer $surfacesejour
     *
     * @return Annonce
     */
    public function setSurfacesejour($surfacesejour)
    {
        $this->surfacesejour = $surfacesejour;

        return $this;
    }

    /**
     * Get surfacesejour
     *
     * @return integer
     */
    public function getSurfacesejour()
    {
        return $this->surfacesejour;
    }

    /**
     * Set surfaceterrasse
     *
     * @param integer $surfaceterrasse
     *
     * @return Annonce
     */
    public function setSurfaceterrasse($surfaceterrasse)
    {
        $this->surfaceterrasse = $surfaceterrasse;

        return $this;
    }

    /**
     * Get surfaceterrasse
     *
     * @return integer
     */
    public function getSurfaceterrasse()
    {
        return $this->surfaceterrasse;
    }

    /**
     * Set surfacebalcon
     *
     * @param integer $surfacebalcon
     *
     * @return Annonce
     */
    public function setSurfacebalcon($surfacebalcon)
    {
        $this->surfacebalcon = $surfacebalcon;

        return $this;
    }

    /**
     * Get surfacebalcon
     *
     * @return integer
     */
    public function getSurfacebalcon()
    {
        return $this->surfacebalcon;
    }

    /**
     * Set acceshandicape
     *
     * @param integer $acceshandicape
     *
     * @return Annonce
     */
    public function setAcceshandicape($acceshandicape)
    {
        $this->acceshandicape = $acceshandicape;

        return $this;
    }

    /**
     * Get acceshandicape
     *
     * @return integer
     */
    public function getAcceshandicape()
    {
        return $this->acceshandicape;
    }

    /**
     * Set alarme
     *
     * @param integer $alarme
     *
     * @return Annonce
     */
    public function setAlarme($alarme)
    {
        $this->alarme = $alarme;

        return $this;
    }

    /**
     * Get alarme
     *
     * @return integer
     */
    public function getAlarme()
    {
        return $this->alarme;
    }

    /**
     * Set ascenseur
     *
     * @param integer $ascenseur
     *
     * @return Annonce
     */
    public function setAscenseur($ascenseur)
    {
        $this->ascenseur = $ascenseur;

        return $this;
    }

    /**
     * Get ascenseur
     *
     * @return integer
     */
    public function getAscenseur()
    {
        return $this->ascenseur;
    }

    /**
     * Set balcon
     *
     * @param integer $balcon
     *
     * @return Annonce
     */
    public function setBalcon($balcon)
    {
        $this->balcon = $balcon;

        return $this;
    }

    /**
     * Get balcon
     *
     * @return integer
     */
    public function getBalcon()
    {
        return $this->balcon;
    }

    /**
     * Set bureau
     *
     * @param integer $bureau
     *
     * @return Annonce
     */
    public function setBureau($bureau)
    {
        $this->bureau = $bureau;

        return $this;
    }

    /**
     * Get bureau
     *
     * @return integer
     */
    public function getBureau()
    {
        return $this->bureau;
    }

    /**
     * Set cave
     *
     * @param integer $cave
     *
     * @return Annonce
     */
    public function setCave($cave)
    {
        $this->cave = $cave;

        return $this;
    }

    /**
     * Get cave
     *
     * @return integer
     */
    public function getCave()
    {
        return $this->cave;
    }

    /**
     * Set cellier
     *
     * @param integer $cellier
     *
     * @return Annonce
     */
    public function setCellier($cellier)
    {
        $this->cellier = $cellier;

        return $this;
    }

    /**
     * Get cellier
     *
     * @return integer
     */
    public function getCellier()
    {
        return $this->cellier;
    }

    /**
     * Set dependances
     *
     * @param integer $dependances
     *
     * @return Annonce
     */
    public function setDependances($dependances)
    {
        $this->dependances = $dependances;

        return $this;
    }

    /**
     * Get dependances
     *
     * @return integer
     */
    public function getDependances()
    {
        return $this->dependances;
    }

    /**
     * Set dressing
     *
     * @param integer $dressing
     *
     * @return Annonce
     */
    public function setDressing($dressing)
    {
        $this->dressing = $dressing;

        return $this;
    }

    /**
     * Get dressing
     *
     * @return integer
     */
    public function getDressing()
    {
        return $this->dressing;
    }

    /**
     * Set gardien
     *
     * @param integer $gardien
     *
     * @return Annonce
     */
    public function setGardien($gardien)
    {
        $this->gardien = $gardien;

        return $this;
    }

    /**
     * Get gardien
     *
     * @return integer
     */
    public function getGardien()
    {
        return $this->gardien;
    }

    /**
     * Set interphone
     *
     * @param integer $interphone
     *
     * @return Annonce
     */
    public function setInterphone($interphone)
    {
        $this->interphone = $interphone;

        return $this;
    }

    /**
     * Get interphone
     *
     * @return integer
     */
    public function getInterphone()
    {
        return $this->interphone;
    }

    /**
     * Set lotissement
     *
     * @param integer $lotissement
     *
     * @return Annonce
     */
    public function setLotissement($lotissement)
    {
        $this->lotissement = $lotissement;

        return $this;
    }

    /**
     * Get lotissement
     *
     * @return integer
     */
    public function getLotissement()
    {
        return $this->lotissement;
    }

    /**
     * Set meuble
     *
     * @param integer $meuble
     *
     * @return Annonce
     */
    public function setMeuble($meuble)
    {
        $this->meuble = $meuble;

        return $this;
    }

    /**
     * Get meuble
     *
     * @return integer
     */
    public function getMeuble()
    {
        return $this->meuble;
    }

    /**
     * Set mitoyenne
     *
     * @param integer $mitoyenne
     *
     * @return Annonce
     */
    public function setMitoyenne($mitoyenne)
    {
        $this->mitoyenne = $mitoyenne;

        return $this;
    }

    /**
     * Get mitoyenne
     *
     * @return integer
     */
    public function getMitoyenne()
    {
        return $this->mitoyenne;
    }

    /**
     * Set piscine
     *
     * @param integer $piscine
     *
     * @return Annonce
     */
    public function setPiscine($piscine)
    {
        $this->piscine = $piscine;

        return $this;
    }

    /**
     * Get piscine
     *
     * @return integer
     */
    public function getPiscine()
    {
        return $this->piscine;
    }

    /**
     * Set terrasse
     *
     * @param integer $terrasse
     *
     * @return Annonce
     */
    public function setTerrasse($terrasse)
    {
        $this->terrasse = $terrasse;

        return $this;
    }

    /**
     * Get terrasse
     *
     * @return integer
     */
    public function getTerrasse()
    {
        return $this->terrasse;
    }

    /**
     * Set anciennete
     *
     * @param string $anciennete
     *
     * @return Annonce
     */
    public function setAnciennete($anciennete)
    {
        $this->anciennete = $anciennete;

        return $this;
    }

    /**
     * Get anciennete
     *
     * @return string
     */
    public function getAnciennete()
    {
        return $this->anciennete;
    }

    /**
     * Set anneeconstruction
     *
     * @param integer $anneeconstruction
     *
     * @return Annonce
     */
    public function setAnneeconstruction($anneeconstruction)
    {
        $this->anneeconstruction = $anneeconstruction;

        return $this;
    }

    /**
     * Get anneeconstruction
     *
     * @return integer
     */
    public function getAnneeconstruction()
    {
        return $this->anneeconstruction;
    }

    /**
     * Set exposition
     *
     * @param integer $exposition
     *
     * @return Annonce
     */
    public function setExposition($exposition)
    {
        $this->exposition = $exposition;

        return $this;
    }

    /**
     * Get exposition
     *
     * @return integer
     */
    public function getExposition()
    {
        return $this->exposition;
    }

    /**
     * Set typechauffage
     *
     * @param string $typechauffage
     *
     * @return Annonce
     */
    public function setTypechauffage($typechauffage)
    {
        $this->typechauffage = $typechauffage;

        return $this;
    }

    /**
     * Get typechauffage
     *
     * @return string
     */
    public function getTypechauffage()
    {
        return $this->typechauffage;
    }

    /**
     * Set naturechauffage
     *
     * @param string $naturechauffage
     *
     * @return Annonce
     */
    public function setNaturechauffage($naturechauffage)
    {
        $this->naturechauffage = $naturechauffage;

        return $this;
    }

    /**
     * Get naturechauffage
     *
     * @return string
     */
    public function getNaturechauffage()
    {
        return $this->naturechauffage;
    }

    /**
     * Set modechauffage
     *
     * @param string $modechauffage
     *
     * @return Annonce
     */
    public function setModechauffage($modechauffage)
    {
        $this->modechauffage = $modechauffage;

        return $this;
    }

    /**
     * Get modechauffage
     *
     * @return string
     */
    public function getModechauffage()
    {
        return $this->modechauffage;
    }

    /**
     * Set typecuisine
     *
     * @param string $typecuisine
     *
     * @return Annonce
     */
    public function setTypecuisine($typecuisine)
    {
        $this->typecuisine = $typecuisine;

        return $this;
    }

    /**
     * Get typecuisine
     *
     * @return string
     */
    public function getTypecuisine()
    {
        return $this->typecuisine;
    }

    /**
     * Set coupdecoeur
     *
     * @param string $coupdecoeur
     *
     * @return Annonce
     */
    public function setCoupdecoeur($coupdecoeur)
    {
        $this->coupdecoeur = $coupdecoeur;

        return $this;
    }

    /**
     * Get coupdecoeur
     *
     * @return string
     */
    public function getCoupdecoeur()
    {
        return $this->coupdecoeur;
    }

    /**
     * Set texte
     *
     * @param string $texte
     *
     * @return Annonce
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte
     *
     * @return string
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Set textanglais
     *
     * @param string $textanglais
     *
     * @return Annonce
     */
    public function setTextanglais($textanglais)
    {
        $this->textanglais = $textanglais;

        return $this;
    }

    /**
     * Get textanglais
     *
     * @return string
     */
    public function getTextanglais()
    {
        return $this->textanglais;
    }

    /**
     * Set urlvisitevirtuelle
     *
     * @param string $urlvisitevirtuelle
     *
     * @return Annonce
     */
    public function setUrlvisitevirtuelle($urlvisitevirtuelle)
    {
        $this->urlvisitevirtuelle = $urlvisitevirtuelle;

        return $this;
    }

    /**
     * Get urlvisitevirtuelle
     *
     * @return string
     */
    public function getUrlvisitevirtuelle()
    {
        return $this->urlvisitevirtuelle;
    }

    /**
     * Set photocoeur
     *
     * @param string $photocoeur
     *
     * @return Annonce
     */
    public function setPhotocoeur($photocoeur)
    {
        $this->photocoeur = $photocoeur;

        return $this;
    }

    /**
     * Get photocoeur
     *
     * @return string
     */
    public function getPhotocoeur()
    {
        return $this->photocoeur;
    }

    /**
     * Set photomedium
     *
     * @param string $photomedium
     *
     * @return Annonce
     */
    public function setPhotomedium($photomedium)
    {
        $this->photomedium = $photomedium;

        return $this;
    }

    /**
     * Get photomedium
     *
     * @return string
     */
    public function getPhotomedium()
    {
        return $this->photomedium;
    }

    /**
     * Set listephotoorig
     *
     * @param string $listephotoorig
     *
     * @return Annonce
     */
    public function setListephotoorig($listephotoorig)
    {
        $this->listephotoorig = $listephotoorig;

        return $this;
    }

    /**
     * Get listephotoorig
     *
     * @return string
     */
    public function getListephotoorig()
    {
        return $this->listephotoorig;
    }

    /**
     * Set photothumb
     *
     * @param string $photothumb
     *
     * @return Annonce
     */
    public function setPhotothumb($photothumb)
    {
        $this->photothumb = $photothumb;

        return $this;
    }

    /**
     * Get photothumb
     *
     * @return string
     */
    public function getPhotothumb()
    {
        return $this->photothumb;
    }

    /**
     * Set photoorigmd
     *
     * @param string $photoorigmd
     *
     * @return Annonce
     */
    public function setPhotoorigmd($photoorigmd)
    {
        $this->photoorigmd = $photoorigmd;

        return $this;
    }

    /**
     * Get photoorigmd
     *
     * @return string
     */
    public function getPhotoorigmd()
    {
        return $this->photoorigmd;
    }

    /**
     * Set consommationenergie
     *
     * @param integer $consommationenergie
     *
     * @return Annonce
     */
    public function setConsommationenergie($consommationenergie)
    {
        $this->consommationenergie = $consommationenergie;

        return $this;
    }

    /**
     * Get consommationenergie
     *
     * @return integer
     */
    public function getConsommationenergie()
    {
        return $this->consommationenergie;
    }

    /**
     * Set emissionges
     *
     * @param integer $emissionges
     *
     * @return Annonce
     */
    public function setEmissionges($emissionges)
    {
        $this->emissionges = $emissionges;

        return $this;
    }

    /**
     * Get emissionges
     *
     * @return integer
     */
    public function getEmissionges()
    {
        return $this->emissionges;
    }

    /**
     * Set photoorigmd5
     *
     * @param string $photoorigmd5
     *
     * @return Annonce
     */
    public function setPhotoorigmd5($photoorigmd5)
    {
        $this->photoorigmd5 = $photoorigmd5;

        return $this;
    }

    /**
     * Get photoorigmd5
     *
     * @return string
     */
    public function getPhotoorigmd5()
    {
        return $this->photoorigmd5;
    }
}

