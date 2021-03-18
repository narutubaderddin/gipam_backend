<?php

namespace App\Utilities;

class MigrationDb
{

    public const NEW_TABLE_NAME = [
        'sub_division',
        'localisation',
        'localisationType',
        'building',
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
        'SERVICES' => 'service',
        'SITES' => 'site',
        'COMMUNE' => 'commune',
        'DEPARTEMENTS' => 'departement',
        'MOUVEMENTS' => 'movement',
        'TYPES_MOUVEMENTS' => 'movement_type',
        'ACTIONS' => 'action',
        'TYPES_ACTIONS' => 'action_type',
        'STATUS' => 'report',
        'ETATS' => 'report_sub_type',
        'PHOTOGRAPHIES' => 'attachment',
        'OEUVRE' => 'furniture',
        'REGIONS' => 'furniture',
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

    public const SITE = [
        'C_SITE' => 'id',
        'SITE_NOM' => 'label',
    ];

    public const COMMUNES = [
        'C_SITE' => 'id',
        'SITE_NOM' => 'label',
        'DEP' => 'rel_departement',
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
}