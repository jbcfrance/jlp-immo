<?php
class traduction {
	private $arrayFr = array(
		/* Biens */
		'reference'	 					 => 	'Réference',
		'numMandat'						 => 	'Numero du mandat',
		'categorieOffre'	 			 => 	'Catégorie de l\'offre',
		'typeBien'	 					 => 	'Type de bien',
		'maison'						 =>		'Maison',
		'appartement'					 =>		'Appartement',
		'bureau'						 =>		'Bureau',
		'terrain'						 =>		'Terrain',
		'immeuble'						 =>		'Immeuble',
		'commerce'						 =>		'Commerce',
		'garage'						 =>		'Garage',
		'categorie'	 					 => 	'Catégorie',
		'villePublique'	 				 => 	'Ville',
		'villeAAfficher'	 			 => 	'Localité',
		'proximite'	 					 => 	'A proximité',
		'montant'	 					 => 	'Montant',
		'fraisDeNotaireReduits'	 		 => 	'Frais de notaire reduits',
		'pieces'	 					 => 	'Pièces',
		'chambres'	 					 => 	'Chambre',
		'sdb'	 						 => 	'Salle de bain',
		'nbSallesDEau'	 				 => 	'Salle d\'eau',
		'nbWC'	 						 => 	'WC',
		'nbParking'				 		 => 	'Parking',
		'nbGarages'	 					 => 	'Garage',
		'niveaux'	 					 => 	'Niveaux',
		'nbEtages'	 					 => 	'Étages',
		'etage'	 						 => 	'Etage',
		'surface'	 					 => 	'Surface',
		'surfaceCarrezOuHabitable'	 	 => 	'Surface Carrez ou Habitable',
		'surfaceTerrain'	 			 => 	'Terrain',
		'ascenseur'	 					 => 	'Ascenseur',
		'balcon'	 					 => 	'Balcon',
		'cave'	 						 => 	'Cave',
		'dependances'	 				 => 	'Nombre de dépendances',
		'meuble'	 					 => 	'Meublé',
		'piscine'	 					 => 	'Piscine',
		'terrasse'						 => 	'Terrasse',
		'exposition'	 				 => 	'Exposition',
		'typeChauffage'					 => 	'Type de chauffage',
		'natureChauffage'	 			 => 	'Nature du chauffage',
		'modeChauffage'	 				 => 	'Mode de chauffage',
		'visiteVirtuelleLink'			 =>		'Visite Virtuelle',
		'bilanenergetique'				 =>		'Bilan énérgétique',
		'imprimer'						 =>		'Imprimer',
		/*Tri*/
		'TitreTri'						 =>		'Tri',
		'TriParMontant'					 =>		'Par Montant',
		'TriParLocalite'				 =>		'Par Localité',
		'TriParTypeBien'				 =>		'Par Type de bien',
		/*Titre*/
		'Budget'						 =>		'Budget',
		'Options'						 =>		'Options',
		'votrebien'						 =>		'Votre bien',
		'voscoordonne'					 =>		'Vos coordonnées',
		'rechercherapide'				 =>		'Recherche rapide',
		'formdemandeinfo'				 =>		'Formulaire de demande d\'information',
		/* Liens */
		'AnnoncesPrgNeuf'				 =>		'Annonces du programme',
		'DemandeInfo'					 =>		'Demande d\'information',
		'SendAmis'						 =>		'Envoyer à un ami',
		/*Boutons Formulaire*/
		'SubmitAcheter'					 =>		'Lancer la recherche',
		'SubmitVendre'					 =>		'Envoyer votre demande',
		'SubmitModule'					 =>		'Envoyer',
		'FastSubmit'					 =>		'Trouver ! ',
		'CallSubmit'					 =>		'Appelez-moi ! ',
		'ChampsOblig'					 =>		'Champs obligatoires',
		'more'							 =>		'et plus',
		'oui'							 =>		'Oui',
		'non'							 =>		'Non',
		'nord'							 =>		'Nord',
		'nordest'						 =>		'Nord-Est',
		'est'							 =>		'Est',
		'sudest'						 =>		'Sud-Est',
		'sud'							 =>		'Sud',
		'sudouest'						 =>		'Sud-Ouest',
		'ouest'							 =>		'Ouest',
		'nordouest'						 =>		'Nord-Ouest',
		'noresult'						 =>		'Aucune annonce ne correspond à votre recherche',
		'noannonce'						 =>		'L\'annonce que vous demandez n\'est plus disponible sur le site.',
		'moreinfo'						 =>		'Contactez-nous pour plus d\'informations',
		/*Coordonnés Client*/
		'nom'							 =>		'Nom',
		'prenom'						 =>		'Prénom',
		'adresse'						 =>		'Adresse',
		'codepostal'					 =>		'Code Postal',
		'ville'						 	 =>		'Ville',
		'pays'						 	 =>		'Pays',
		'mail'							 =>		'E-mail',
		'telephone'						 =>		'Téléphone',
		'mailamis'						 =>		'Email de l\'ami',
		'descriptionbien'				 =>		'Description du bien',
		'lienannonce'					 =>		'Lien vers l\'annonce',
		/*Form contact*/
		'agence_images'					 =>		'JLP-Immo en images',
		'label_nom'						 =>		'Votre Nom',
		'label_email'					 =>		'Votre Email',
		'label_message'					 =>		'Votre Message',
		'title_form'					 =>		'Ecrivez-nous !',
		'send_mess'						 =>		'Envoyer votre message',
		'agenceimmo'					 =>		'Agence Immobilière',
		'tel'							 =>		'Tél',
		'fax'							 =>		'Fax',
		'partenaire'					 =>		'Nos partenaires',
		'officetourisme'				 =>		'Office du tourisme de Saint-Gervais-Les-Bains',
		'therme'						 =>		'Thermes de Saint-Gervais-Les-Bains',
		'cpkweb'						 =>		'CPK-Web - Création de sites web',
		'nicodeco'						 =>		'Nicodeco',
		/*Menu accueil*/
		'Accueil'						 =>		'Accueil',
		'Acheter'						 =>		'Acheter',
		'Vendre'						 =>		'Vendre',
		'ProgrammeNeuf'					 =>		'Programmes Neufs',
		'Contact'						 =>		'Contact',
		'Annonce'						 =>		'Annonce',
		/*Organigramme*/
		'DirGen'						 =>		'Directeur Général',
		'GestAdmin'						 =>		'Gestion administrative',
		'RespInfo'						 =>		'Responsable informatique',
		'Nego'							 =>		'Négociatrice',
		/*Footer*/
		'mentions'						 =>		'Mentions légales',
		'design'						 =>		'Design',
		'webmaster'						 =>		'Création',
		
		'ref'							 =>		'Réf'
		
		
	);
	
	private $arrayEn = array(
		/* Biens */
		'reference'	 					 => 	'Reference',
		'numMandat'						 => 	'Mandat number',
		'categorieOffre'	 			 => 	'Offer categorie',
		'typeBien'	 					 => 	'Property Type',
		'maison'						 =>		'House',
		'appartement'					 =>		'Appartment',
		'bureau'						 =>		'Office',
		'terrain'						 =>		'Land',
		'immeuble'						 =>		'Building',
		'commerce'						 =>		'Shop',
		'garage'						 =>		'Parking',
		'categorie'	 					 => 	'Category',
		'villePublique'	 				 => 	'Town',
		'villeAAfficher'	 			 => 	'Location',
		'proximite'	 					 => 	'Near',
		'montant'	 					 => 	'Amount',
		'fraisDeNotaireReduits'	 		 => 	'Solicitors fee reduced',
		'pieces'	 					 => 	'Rooms',
		'chambres'	 					 => 	'Bedrooms',
		'sdb'	 						 => 	'Bathroom',
		'nbSallesDEau'	 				 => 	'Waterroom',
		'nbWC'	 						 => 	'WC',
		'nbParking'				 		 => 	'Parking',
		'nbGarages'	 					 => 	'Car-park',
		'niveaux'	 					 => 	'Levels',
		'nbEtages'	 					 => 	'Floors',
		'etage'	 						 => 	'Floor',
		'surface'	 					 => 	'Area',
		'surfaceCarrezOuHabitable'	 	 => 	'Carez Area',
		'surfaceTerrain'	 			 => 	'Land',
		'ascenseur'	 					 => 	'Lift',
		'balcon'	 					 => 	'Balcony',
		'cave'	 						 => 	'Cellar',
		'dependances'	 				 => 	'Number of dependancy',
		'meuble'	 					 => 	'Furnished',
		'piscine'	 					 => 	'Swimming-pool',
		'terrasse'						 => 	'Landscape',
		'exposition'	 				 => 	'Orientation',
		'typeChauffage'					 => 	'Heating\'s type',
		'natureChauffage'	 			 => 	'Heating\'s nature',
		'modeChauffage'	 				 => 	'Heating\'s mode',
		'visiteVirtuelleLink'			 =>		'Virtual visit',
		'bilanenergetique'				 =>		'Bilan énérgétique',
		'imprimer'						 =>		'Print',
		/*Tri*/
		'TitreTri'						 =>		'Select',
		'TriParMontant'					 =>		'By Amount',
		'TriParLocalite'				 =>		'By Location',
		'TriParTypeBien'				 =>		'By Type of property',
		/*Titre*/
		'Budget'						 =>		'Budget',
		'Options'						 =>		'Options',
		'votrebien'						 =>		'Your property',
		'voscoordonne'					 =>		'Your details',
		'rechercherapide'				 =>		'Quick search',
		'formdemandeinfo'				 =>		'Ask more',
		/* Liens */
		'AnnoncesPrgNeuf'				 =>		'Program\'s goods',
		'DemandeInfo'					 =>		'Ask more',
		'SendAmis'						 =>		'Send to a friend',
		/*Boutons Formulaire*/
		'SubmitAcheter'					 =>		'Go',
		'SubmitVendre'					 =>		'Submit',
		'SubmitModule'					 =>		'Submit',
		'FastSubmit'					 =>		'Found ! ',
		'CallSubmit'					 =>		'Call me ! ',
		'ChampsOblig'					 =>		'Mandatory fields',
		'more'							 =>		'and more',
		'oui'							 =>		'yes',
		'non'							 =>		'no',
		'nord'							 =>		'North',
		'nordest'						 =>		'North-East',
		'est'							 =>		'East',
		'sudest'						 =>		'South-East',
		'sud'							 =>		'South',
		'sudouest'						 =>		'South-West',
		'ouest'							 =>		'West',
		'nordouest'						 =>		'North-West',
		'noresult'						 =>		'No match found',
		'noannonce'						 =>		'No such property available',
		'moreinfo'						 =>		'Contact-us for more informations',
		/*Coordonnés Client*/
		'nom'							 =>		'Last name',
		'prenom'						 =>		'First name',
		'adresse'						 =>		'Address',
		'codepostal'					 =>		'Post code',
		'ville'						 	 =>		'Town',
		'pays'						 	 =>		'Country',
		'mail'							 =>		'E-mail',
		'telephone'						 =>		'Phone',
		'mailamis'						 =>		'Friend\'s e-mail',
		'descriptionbien'				 =>		'Description of the property',
		'lienannonce'					 =>		'Link to the property',
		/*Form contact*/
		'agence_images'					 =>		'Pictures of JLP-Immo',
		'label_nom'						 =>		'Your Name',
		'label_email'					 =>		'Your Email',
		'label_message'					 =>		'Your Message',
		'title_form'					 =>		'Contact Us !',
		'send_mess'						 =>		'Send your message',
		'agenceimmo'					 =>		'Agence Immobilière',
		'tel'							 =>		'Phone',
		'fax'							 =>		'Fax',
		'partenaire'					 =>		'Our partners',
		'officetourisme'				 =>		'Tourist Office of Saint-Gervais-Les-Bains',
		'therme'						 =>		'Thermes de Saint-Gervais-Les-Bains',
		'cpkweb'						 =>		'CPK-Web - Web agency',
		'nicodeco'						 =>		'Nicodeco',
		/*Menu accueil*/
		'Accueil'						 =>		'Home',
		'Acheter'						 =>		'Buy',
		'Vendre'						 =>		'Sell',
		'ProgrammeNeuf'					 =>		'New developments',
		'Contact'						 =>		'Contact',
		'Annonce'						 =>		'Property',
		/*Organigramme*/
		'DirGen'						 =>		'CEO',
		'GestAdmin'						 =>		'Administration',
		'RespInfo'						 =>		'IT Manager',
		'Nego'							 =>		'Negociator',
		/*Footer*/
		'mentions'						 =>		'Legal mentions',
		'design'						 =>		'Design',
		'webmaster'						 =>		'Creation',
		
		'ref'							 =>		'Ref'
		);
	private $sString = null;
		
		/*
		A Modifier pour introduir la gestion de différentes variables si besoin
		Fonctions php à utilisés:
		vsprintf
		func_get_args
		func_nums_args
		array_shift
		*/



		public function __construct () {
			
		}

		public function getTrad($sString) {
			switch ($_SESSION["lang"]) {
				case "fr":
					return $this->arrayFr["".$sString.""];
				break;
				case "en":
					return $this->arrayEn["".$sString.""];
				break;
				default:
				return $this->arrayFr["".$sString.""];
			}
		}
		
		public function getFr($sString) {
			return $this->arrayFr["".$sString.""];
		}
		
		public function getEn($sString) {
			return $this->arrayEn["".$sString.""];
		}

}
?>