<?php

namespace App\Command\Utils;

class MigrationDb
{

    public const NEW_TABLE_NAME = [
        'sub_division',
        'localisation',
        'localisationType',
        'room',
        'responsible',
        'movement_action_type',
        'alert',
        'report_type',
        'art_work_log',
    ];

    /*
    public const TABLE_NAME = [
        'USERS' => 'user',
        'MINISTERES' => 'ministry',
        'ETAB_DIR' => 'establishment',
        'CORRESPONDANTS' => 'correspondent',
        'service' => 'SERVICES',
        'site' => 'SITES',
        'commune' => 'COMMUNES',
        'departement' => 'DEPARTEMENTS',
        'MOUVEMENTS' => 'movement',
        'TYPES_MOUVEMENTS' => 'movement_type',
        'ACTIONS' => 'action',
        'TYPES_ACTIONS' => 'action_type',
        'STATUS' => 'report',
        'ETATS' => 'report_sub_type',
        'PHOTOGRAPHIES' => 'attachment',
        'OEUVRE' => 'furniture',
        'region' => 'REGIONS',
        'building' => 'SITES_6A',
        'era' => 'EPOQUES',
        'field' => 'DOMAINE',
        'denomination' => 'DENOMINATIONS',
        'style' => 'STYLES',
        'deposit_type' => 'TYPES_DEPOSANTS',
    ];
    */

    public const TABLE_NAME = [
//        'user' => self::USERS,
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
//        'report_sub_type' => ETATS,
        'attachment' => self::PHOTOGRAPHIES,
        'furniture' => self::OEUVRE,
        'region' => self::REGIONS,
        'building' => self::SITES_6A,
        'era' => self::EPOQUES,
        'field' => self::DOMAINES,
        'denomination' => self::DENOMINATIONS,
        'style' => self::STYLES,
        'deposit_type' => self::TYPES_DEPOSANTS,
    ];

    public const TYPES_DEPOSANTS = [
        'id' => 'C_TYPE_DEPOSANTS',
        'label' => 'TDEP_DEPOSANTS',
    ];

    public const STYLES = [
        'id' => 'C_STYLE',
        'label' => 'STY_STYLE',
    ];

    public const DENOMINATIONS = [
        'id' => 'C_DENOMINATION',
        'label' => 'DEN_DENOMINATION',
        'rel_field' => 'C_DOMAINE',
    ];

    public const DOMAINES = [
      'id' => 'C_DOMAINE',
      'label' => 'DOM_DOMAINE',
    ];

    public const EPOQUES = [
        'id' => 'C_EPOQUE',
        'label' => 'EPO_EPOQUE',
    ];

    public const USERS = [
        'C_USERS' => 'id',
        'US_NOM' => 'lastName',
        'US_PRENOM' => 'firstName',
        'US_COMM' => 'comment',
        'C_PROFIL' => 'roles',
    ];

    public const MINISTERES = [
        'C_MIN' => 'id',
        'MIN_SIGLELDAP' => 'acronym',
        'MIN_LIBELLE' => 'name',
        'MIN_DATE_CRE' => 'startDate',
        'MIN_DATE_DIS' => 'disappearanceDate',
    ];

    public const ETAB_DIR = [
        'C_ETABDIR' => 'id',
        'ED_SIGLE' => 'acronym',
        'ED_LIBELLE' => 'label',
        'C_MIN' => 'rel_ministry',
    ];

    public const CORRESPONDANTS = [
        'C_COR' => 'id',
        'COR_NOM' => 'lastName',
        'COR_PRENOM' => 'firstName',
        'COR_TEL' => 'phone',
        'COR_FAX' => 'fax',
        'COR_MEL' => 'mel',
        'C_SERVICE' => 'rel_service',
        'C_ETABDIR' => 'rel_establishment',
    ];

    public const SERVICES = [
        'table' => 'MOUVEMENTS',
        'id' => 'C_SERVICE',
        'unique' => 'SERV_SIGLE',
        'acronym' => 'SERV_SIGLE',
        'label' => 'SERV_LIBELLE',
    ];

    // todo : will be migrated in parallel with Furniture
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
        'C_DOMAINE' => 'rel_field',
        'C_EPOQUE' => 'rel_era',
        'C_STYLE' => 'rel_style',
    ];

    public const SITES_6A = [
        'table' => 'SITES_6A',
        'id' => 'C_SITE',
        'unique' => 'SITE_NOM',
        'name' => 'SITE_NOM',
        'distrib' => 'SITE_DISTRIB',
        'address' => 'SITE_ADRESSE',
        'cedex' => 'SITE_CEDEX',
        'rel_commune' => 'COM'
    ];

    public const SITES = [
        'table' => 'SITES',
        'id' => 'C_SITE',
        'unique' => 'SITE_NOM',
        'label' => 'SITE_NOM',
    ];

    public const COMMUNES = [
        'table' => 'COMMUNES',
        'id' => 'COM',
        'unique' => 'NCCENR',
        'name' => 'NCCENR',
        'rel_departement' => 'DEP',
    ];

    public const DEPARTEMENTS = [
        'table' => 'DEPARTEMENTS',
        'id' => 'DEP',
        'unique' => 'NCCENR',
        'name' => 'NCCENR',
        'rel_region' => 'REGION',
    ];

    public const REGIONS = [
        'table' => 'REGIONS',
        'id' => 'REGION',
        'unique' => 'NCCENR',
        'name' => 'NCCENR',
    ];
}