<?php

namespace App\Command\Utils;

class MigrationDb
{

    public const UPPERCASE_NAME = true;
    public const USE_ACCESS_DB = true;

    public const NEW_TABLE_NAME = [
        'subdivision',
        'localisation',
        'localisationType',
        'room',
        'responsible',
        'movementactiontype',
        'alert',
        'reporttype',
        'artworklog',
    ];

    public const TABLE_NAME = [
        'ministere' => self::MINISTERES,
        'etablissement' => self::ETAB_DIR,
        'correspondant' => self::CORRESPONDANTS,
        'service' => self::SERVICES,
        'site' => self::SITES,
        'commune' => self::COMMUNES,
        'departement' => self::DEPARTEMENTS,
        'mouvement' => self::MOUVEMENTS,
        'type_mouvement' => self::TYPES_MOUVEMENTS,
        'action' => self::ACTIONS,
        'type_action' => self::TYPES_ACTIONS,
        'fichier_joint' => self::PHOTOGRAPHIES,
        'oeuvre_art' => self::OEUVRES,
        'region' => self::REGIONS,
        'batiment' => self::SITES_6A,
        'epoque' => self::EPOQUES,
        'domaine' => self::DOMAINE,
        'denomination' => self::DENOMINATIONS,
        'style' => self::STYLES,
        'type_deposant' => self::TYPES_DEPOSANTS,
        'deposant' => self::DEPOSANT,
        'auteur' => self::AUTEUR,
        'matiere_technique' => self::MATIERE,
        'constat' => self::STATUS,
        'sous_type_constat' => self::ETAT,
    ];




    public const MATIERE = [
        'id' => 'C_MGPAM',
        'table' => 'OEUVRES',
        'libelle' => 'OE_MATIERES',
        'old_id' => 'C_MGPAM'
    ];

    public const AUTEUR = [
        'id' => 'C_MGPAM',
        'table' => 'OEUVRES',
        'nom' => 'OE_NOMAUTEUR',
        'prenom' => 'OE_PRENOMAUTEUR',
        'old_id' => 'C_MGPAM'
    ];



    public const DEPOSANT = [
        'id' => 'C_DEPOSANT',
        'table' => 'DEPOSANT',
        'rel_type_deposant' => 'C_TYPE_DEPOSANTS',
        'nom' => 'DEP_DEPOSANT',
        'sigle' => 'DEP_SIGLE',
        'adresse' => 'DEP_ADRESSE1',
        'distrib' => 'DEP_DISTRIB',
        'tel' => 'DEP_TEL',
        'fax' => 'DEP_FAX',
        'mail' => 'DEP_MEL',
        'contact' => 'DEP_CONTACT',
        'commentaire' => 'DEP_COMM',
        'old_id' => 'C_DEPOSANT'
    ];

    public const TYPES_DEPOSANTS = [
        'id' => 'C_TYPE_DEPOSANTS',
        'table' => 'TYPES_DEPOSANTS',
        'libelle' => 'TDEP_DEPOSANTS',
        'old_id' => 'C_TYPE_DEPOSANTS'
    ];

    public const STYLES = [
        'id' => 'C_STYLE',
        'table' => 'STYLES',
        'libelle' => 'STY_STYLE',
        'old_id' => 'C_STYLE'
    ];

    public const DENOMINATIONS = [
        'id' => 'C_DENOMINATION',
        'table' => 'DENOMINATIONS',
        'rel_domaine' => 'C_DOMAINE',
        'libelle' => 'DEN_DENOMINATION',
        'old_id' => 'C_DENOMINATION'
    ];

    public const DOMAINE = [
        'id' => 'C_DOMAINE',
        'table' => 'DOMAINE',
        'libelle' => 'DOM_DOMAINE',
        'old_id' => 'C_DOMAINE'
    ];

    public const EPOQUES = [
        'id' => 'C_EPOQUE',
        'table' => 'EPOQUES',
        'libelle' => 'EPO_EPOQUE',
        'old_id' => 'C_EPOQUE'
    ];

    public const MINISTERES = [
        'id' => 'C_MIN',
        'table' => 'MINISTERES',
        'sigle' => 'MIN_SIGLELDAP',
        'nom' => 'MIN_LIBELLE',
        'date_debut' => 'MIN_DATE_CRE',
        'date_disparition' => 'MIN_DATE_DIS',
        'old_id' => 'C_MIN',
    ];

    public const ETAB_DIR = [
        'id' => 'C_ETABDIR',
        'table' => 'ETAB_DIR',
        'rel_ministere' => 'C_MIN',
        'sigle' => 'ED_SIGLE',
        'libelle' => 'ED_LIBELLE',
        'old_id' => 'C_ETABDIR',
    ];

    public const CORRESPONDANTS = [
        'id' => 'C_COR',
        'table' => 'CORRESPONDANTS',
        'rel_service' => 'C_SERVICE',
        'rel_etablissement' => 'C_ETABDIR',
        'nom' => 'COR_NOM',
        'prenom' => 'COR_PRENOM',
        'telephone' => 'COR_TEL',
        'fax' => 'COR_FAX',
        'mail' => 'COR_MEL',
        'old_id' => 'C_COR',
    ];

    public const SERVICES = [
        'id' => 'C_SERVICE',
        'table' => 'SERVICES',
        'sigle' => 'SERV_SIGLE',
        'libelle' => 'SERV_LIBELLE',
        'old_id' => 'C_SERVICE',
    ];

    // todo : will be migrated in parallel with Furniture, also there is no id
    public const MOUVEMENTS = [
        // todo id mouvement ??
        'id' => '',
        'table' => 'MOUVEMENTS',
//        'id' => 'id',
//        'id' => 'C_MGPAM',
        'commentaire' => 'MVT_COMM',
        'date' => 'MVT_DATE',
        'rel_type_mouvement' => 'C_TYPEMVT',
    ];

    public const TYPES_MOUVEMENTS = [
        'id' => 'C_TYPEMVT',
        'table' => 'TYPES_MOUVEMENTS',
        'libelle' => 'TMVT_MOUVEMENT',
        'old_id' => 'C_TYPEMVT',
    ];

    // todo : will be migrated in parallel with Furniture
    public const ACTIONS = [
        'id' => 'C_TYPEMVT',
        'table' => 'ACTIONS',
        'rel_constat' => 'C_TYPESTATUT',
        'rel_type_action' => 'C_TYPEACT',
        'libelle' => 'TMVT_MOUVEMENT',
    ];

    public const TYPES_ACTIONS = [
        'id' => 'C_TYPEACT',
        'table' => 'TYPES_ACTIONS',
        'libelle' => 'TACT_ACTION',
    ];

    public const STATUS = [
        'C_TYPESTATUT' => 'id',
    ];

    public const ETAT = [
        'id' => 'C_TYPEETAT',
        'table' => 'ETAT',
        'libelle' => 'TET_ETAT',
        'old_id' => 'C_TYPEETAT',
    ];

    public const PHOTOGRAPHIES = [
        'id' => 'C_PHOTO',
        'table' => 'PHOTOGRAPHIES',
        'rel_objet_mobilier' => 'C_MGPAM',
        'date' => 'PH_DATE',
        'commentaire' => 'PH_COMM',
        'lien' => 'PH_LIEN',
        'image_principale' => 'PH_PRINC',
        'old_id' => 'C_PHOTO',
    ];

    // todo still relations
    public const OEUVRES = [
        'id' => 'C_MGPAM',
        'table' => 'OEUVRES',
        'rel_denomination' => 'C_DENOMINATION',
        'rel_domaine' => 'C_DOMAINE',
        'rel_epoque' => 'C_EPOQUE',
        'rel_style' => 'C_STYLE',
        'rel_auteur' => 'C_MGPAM',
        'rel_matiere_technique' => 'C_MGPAM',
        'titre' => 'OE_TITRE',
        'nombre_unite' => 'OE_NB',
        'description_commentaire' => 'OE_REPRISE',
        'old_id' => 'C_MGPAM'
    ];

    public const SITES_6A = [
        'id' => 'C_SITE',
        'table' => 'SITES_6A',
        'rel_commune' => 'COM',
        'nom' => 'SITE_NOM',
        'addresse' => 'SITE_ADRESSE',
        'distrib' => 'SITE_DISTRIB',
        'cedex' => 'SITE_CEDEX',
        'old_id' => 'C_SITE',
    ];

    public const SITES = [
        'id' => 'C_SITE',
        'table' => 'SITES',
        'libelle' => 'SITE_NOM',
        'old_id' => 'C_SITE',
    ];

    public const COMMUNES = [
        'id' => 'COM',
        'table' => 'COMMUNES',
        'rel_departement' => 'DEP',
        'nom' => 'NCCENR',
        'old_id_1' => 'COM',
        'old_id_2' => 'DEP',
    ];

    public const DEPARTEMENTS = [
        'id' => 'DEP',
        'table' => 'DEPARTEMENTS',
        'rel_region' => 'REGION',
        'nom' => 'NCCENR',
        'old_id' => 'DEP',
    ];

    public const REGIONS = [
        'id' => 'REGION',
        'table' => 'REGIONS',
        'nom' => 'NCCENR',
        'old_id' => 'REGION',
    ];
}