<?php

namespace App\Utilities;

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
        'C_SERVICE' => 'id',
        'SERV_SIGLE' => 'acronym',
        'SERV_LIBELLE' => 'label',
    ];

    public const SITES = [
        'id' => 'C_SITE',
        'label' => 'SITE_NOM',
    ];

    public const COMMUNES = [
        'id' => 'COM',
        'name' => 'NCC',
        'rel_departement' => 'DEP',
    ];

    public const DEPARTEMENTS = [
        'id' => 'DEP',
        'name' => 'NCC',
        'rel_region' => 'REGION',
    ];

    public const REGIONS = [
        'id' => 'REGION',
        'name' => 'NCC',
    ];

    public const MOUVEMENTS = [
        'C_MGPAM' => 'id',
        'MVT_COMM' => 'comment',
        'MVT_DATE' => 'date',
        'C_TYPEMVT' => 'rel_movement_type',
    ];

    public const TYPES_MOUVEMENTS = [
        'C_TYPEMVT' => 'id',
        'TMVT_MOUVEMENT' => 'label',
    ];

    public const ACTIONS = [
        'C_TYPEMVT' => 'id',
        'TMVT_MOUVEMENT' => 'label',
        'C_TYPESTATUT' => 'rel_report',
        'C_TYPEACT' => 'rel_action_type',
    ];

    public const TYPES_ACTIONS = [
        'C_TYPEACT' => 'id',
        'TACT_ACTION' => 'label',
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
        'id' => 'C_SITE',
        'name' => 'SITE_NOM',
        'distrib' => 'SITE_DISTRIB',
        'address' => 'SITE_ADRESSE',
        'cedex' => 'SITE_CEDEX',
        'rel_commune' => 'COM'
    ];
}