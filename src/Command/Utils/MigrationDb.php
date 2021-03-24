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
        'ministry' => self::MINISTERES,
        'establishment' => self::ETAB_DIR,
        'correspondent' => self::CORRESPONDANTS,
        'service' => self::SERVICES,
        'site' => self::SITES,
        'commune' => self::COMMUNES,
        'departement' => self::DEPARTEMENTS,
        'movement' => self::MOUVEMENTS,
        'movement_type' => self::TYPES_MOUVEMENTS,
        'action' => self::ACTIONS,
        'action_type' => self::TYPES_ACTIONS,
        'report' => self::STATUS,
        'attachment' => self::PHOTOGRAPHIES,
        'furniture' => self::OEUVRE,
        'region' => self::REGIONS,
        'building' => self::SITES_6A,
        'era' => self::EPOQUES,
        'domaine' => self::DOMAINE,
        'denomination' => self::DENOMINATIONS,
        'style' => self::STYLES,
        'deposittype' => self::TYPES_DEPOSANTS,
        'depositor' => self::DEPOSANT,
        'author' => self::AUTEUR,
    ];

    public const AUTEUR = [
        'id' => 'C_DEPOSANT',
        'unique' => 'C_DEPOSANT',
        'table' => 'OEUVRES',
        'nom' => 'OE_NOMAUTEUR',
        'prenom' => 'OE_PRENOMAUTEUR',
    ];

    public const DEPOSANT = [
        'id' => 'C_DEPOSANT',
        'unique' => 'C_DEPOSANT',
        'table' => 'DEPOSANT',
        'rel_deposittype' => 'C_TYPE_DEPOSANTS',
        'name' => 'DEP_DEPOSANT',
        'acronym' => 'DEP_SIGLE',
        'address' => 'DEP_ADRESSE1',
        'distrib' => 'DEP_DISTRIB',
        'phone' => 'DEP_TEL',
        'fax' => 'DEP_FAX',
        'mail' => 'DEP_MEL',
        'contant' => 'DEP_CONTACT',
        'comment' => 'DEP_COMM',
        'old_id' => 'C_DEPOSANT'
    ];

    public const TYPES_DEPOSANTS = [
        'id' => 'C_TYPE_DEPOSANTS',
        'unique' => 'C_TYPE_DEPOSANTS',
        'table' => 'TYPES_DEPOSANTS',
        'label' => 'TDEP_DEPOSANTS',
        'old_id' => 'C_TYPE_DEPOSANTS'
    ];

    public const STYLES = [
        'id' => 'C_STYLE',
        'unique' => 'STYLES',
        'table' => 'STYLES',
        'label' => 'STY_STYLE',
        'old_id' => 'C_STYLE'
    ];

    public const DENOMINATIONS = [
        'id' => 'C_DENOMINATION',
        'unique' => 'C_DENOMINATION',
        'table' => 'DENOMINATIONS',
        'rel_domaine' => 'C_DOMAINE',
        'label' => 'DEN_DENOMINATION',
        'old_id' => 'C_DENOMINATION'
    ];

    public const DOMAINE = [
        'id' => 'C_DOMAINE',
        'unique' => 'C_DOMAINE',
        'table' => 'DOMAINE',
        'libelle' => 'DOM_DOMAINE',
        'old_id' => 'C_DOMAINE'
    ];

    public const EPOQUES = [
        'id' => 'C_EPOQUE',
        'unique' => 'C_EPOQUE',
        'table' => 'EPOQUES',
        'label' => 'EPO_EPOQUE',
        'old_id' => 'C_EPOQUE'
    ];

    public const MINISTERES = [
        'id' => 'C_MIN',
        'unique' => 'C_MIN',
        'table' => 'MINISTERES',
        'acronym' => 'MIN_SIGLELDAP',
        'name' => 'MIN_LIBELLE',
        'startdate' => 'MIN_DATE_CRE',
        'disappearancedate' => 'MIN_DATE_DIS',
        'old_id' => 'C_MIN',
    ];

    public const ETAB_DIR = [
        'id' => 'C_ETABDIR',
        'unique' => 'C_ETABDIR',
        'table' => 'ETAB_DIR',
        'rel_ministry' => 'C_MIN',
        'acronym' => 'ED_SIGLE',
        'label' => 'ED_LIBELLE',
        'old_id' => 'C_ETABDIR',
    ];

    public const CORRESPONDANTS = [
        'id' => 'C_COR',
        'unique' => 'C_COR',
        'table' => 'CORRESPONDANTS',
        'rel_service' => 'C_SERVICE',
        'rel_establishment' => 'C_ETABDIR',
        'lastname' => 'COR_NOM',
        'firstname' => 'COR_PRENOM',
        'phone' => 'COR_TEL',
        'fax' => 'COR_FAX',
        'mail' => 'COR_MEL',
        'old_id' => 'C_COR',
    ];

    public const SERVICES = [
        'id' => 'C_SERVICE',
        'unique' => 'SERV_SIGLE',
        'table' => 'SERVICES',
        'acronym' => 'SERV_SIGLE',
        'label' => 'SERV_LIBELLE',
        'old_id' => 'C_SERVICE',
    ];

    // todo : will be migrated in parallel with Furniture, also there is no id
    public const MOUVEMENTS = [
        'table' => 'MOUVEMENTS',
//        'id' => 'id',
//        'id' => 'C_MGPAM',
        'comment' => 'MVT_COMM',
        'date' => 'MVT_DATE',
        'rel_movement_type' => 'C_TYPEMVT',
    ];

    public const TYPES_MOUVEMENTS = [
        'table' => 'TYPES_MOUVEMENTS',
        'id' => 'C_TYPEMVT',
        'unique' => 'C_TYPEMVT',
        'label' => 'TMVT_MOUVEMENT',
        'old_id' => 'C_TYPEMVT',
    ];

    // todo : will be migrated in parallel with Furniture
    public const ACTIONS = [
        'table' => 'ACTIONS',
        'id' => 'C_TYPEMVT',
        'label' => 'TMVT_MOUVEMENT',
        'rel_report' => 'C_TYPESTATUT',
        'rel_action_type' => 'C_TYPEACT',
    ];

    public const TYPES_ACTIONS = [
        'table' => 'TYPES_ACTIONS',
        'id' => 'C_TYPEACT',
        'unique' => 'C_TYPEACT',
        'label' => 'TACT_ACTION',
    ];

    public const STATUS = [
        'C_TYPESTATUT' => 'id',
    ];

    public const ETAT = [
        'C_TYPEETAT' => 'id',
        'TET_ETAT' => 'label',
    ];

    public const PHOTOGRAPHIES = [
        'C_PHOTO' => 'id',
        'PH_DATE' => 'date',
        'PH_COMM' => 'comment',
        'PH_LIEN' => 'PH_LIEN',
        'PH_PRINC' => 'principleImage',
        'C_MGPAM' => 'rel_furniture',
    ];

    public const OEUVRE = [
        'C_MGPAM' => 'id',
        'OE_TITRE' => 'title',
        'OE_NB' => 'numberOfUnit',
        'OE_REPRISE' => 'description',
        'C_DENOMINATION' => 'rel_denomination',
        'C_DOMAINE' => 'rel_domaine',
        'C_EPOQUE' => 'rel_era',
        'C_STYLE' => 'rel_style',
    ];

    public const SITES_6A = [
        'id' => 'C_SITE',
        'unique' => 'SITE_NOM',
        'table' => 'SITES_6A',
        'rel_commune' => 'COM',
        'name' => 'SITE_NOM',
        'address' => 'SITE_ADRESSE',
        'distrib' => 'SITE_DISTRIB',
        'cedex' => 'SITE_CEDEX',
        'old_id' => 'C_SITE',
    ];

    public const SITES = [
        'id' => 'C_SITE',
        'unique' => 'SITE_NOM',
        'table' => 'SITES',
        'label' => 'SITE_NOM',
        'old_id' => 'C_SITE',
    ];

    public const COMMUNES = [
        'id' => 'COM',
        'unique' => 'NCCENR',
        'table' => 'COMMUNES',
        'rel_departement' => 'DEP',
        'name' => 'NCCENR',
        'old_id_1' => 'COM',
        'old_id_2' => 'DEP',
    ];

    public const DEPARTEMENTS = [
        'id' => 'DEP',
        'unique' => 'NCCENR',
        'table' => 'DEPARTEMENTS',
        'rel_region' => 'REGION',
        'name' => 'NCCENR',
        'old_id' => 'DEP',
    ];

    public const REGIONS = [
        'id' => 'REGION',
        'unique' => 'NCCENR',
        'table' => 'REGIONS',
        'name' => 'NCCENR',
        'old_id' => 'REGION',
    ];
}