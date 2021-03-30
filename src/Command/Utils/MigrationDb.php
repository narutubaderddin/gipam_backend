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
        'action' => self::ACTIONS,
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
        'type_constat' => self::STATUTS,
        'sous_type_constat' => self::ETATS,
        'type_mouvement' => self::TYPES_MOUVEMENTS,
        'type_action' => self::TYPES_ACTIONS,
        'statut_propriete' => self::STATUT_PROPRIETE,
        'statut_depot' => self::STATUT_DEPOT,
        'log_oeuvre' => self::LOG_OEUVRE,
    ];




    public const MATIERE = [
        'id' => 'C_MGPAM',
        'table' => 'OEUVRES',
        'libelle' => 'OE_MATIERES',
    ];

    public const AUTEUR = [
        'id' => 'C_MGPAM',
        'table' => 'OEUVRES',
        'nom' => 'OE_NOMAUTEUR',
        'prenom' => 'OE_PRENOMAUTEUR',
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

    public const STATUTS = [
        'id' => 'C_TYPESTATUT',
        'table' => 'STATUTS',
        'libelle' => 'TST_STATUT',
        'old_id' => 'C_TYPESTATUT',
    ];

    public const ETATS = [
        'id' => 'C_TYPEETAT',
        'table' => 'ETATS',
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

    public const OEUVRES = [
        'id' => 'C_MGPAM',
        'table' => 'OEUVRES',
        'rel_denomination' => 'C_DENOMINATION',
        'rel_domaine' => 'C_DOMAINE',
        'rel_epoque' => 'C_EPOQUE',
        'rel_style' => 'C_STYLE',
        'titre' => 'OE_TITRE',
        'nombre_unite' => 'OE_NB',
        'description_commentaire' => 'OE_REPRISE',
//        'old_id' => 'C_MGPAM'
    ];

    public const LOG_OEUVRE = [
        'id' => 'C_MGPAM',
        'table' => 'OEUVRES',
        'date' => 'OE_DATEENR',
    ];

    public const STATUT = [
        'id' => 'C_MGPAM',
        'table' => 'OEUVRES',
        'commentaire' => 'OE_DEPOT',
        'rel_deposant' => 'C_DEPOSANT',
    ];

    public const STATUT_PROPRIETE = [
        'id' => 'C_MGPAM',
        'table' => 'OEUVRES',
        'date_entree' => 'OE_DATEDEPOT',
        'commentaire' => 'OE_DEPOT',
        'prop_un_pour_cent' => 'OE_UNPOURCENT',
    ];

    public const STATUT_DEPOT = [
        'id' => 'C_MGPAM',
        'table' => 'OEUVRES',
        'commentaire' => 'OE_DEPOT',
        'date_depot' => 'OE_DATEDEPOT',
        'rel_deposant' => 'C_DEPOSANT',
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

    public static function utf8Encode($input)
    {
        if (self::USE_ACCESS_DB && is_string($input)) {
            $input = utf8_encode($input);
        }
        return $input;
    }

    public static function getMappingTable($tableName)
    {
        $mappingTable = self::TABLE_NAME[$tableName];
        // Here whene converting the DB System to postgres the names of tables and columns are in lowercase
        // Option changed in MigrationDb class
        if (!self::UPPERCASE_NAME) {
            $mappingTable = array_map('strtolower', $mappingTable);
        }
        return $mappingTable;
    }

    public static function getOldIdColumns(string $table)
    {
        $mappingTable = self::TABLE_NAME[$table];
        $keys = array_keys($mappingTable);
        $i = count($keys) - 1;
        $columns = [];
        while ((strpos($keys[$i], 'old_id') !== false) && $i >= 0) {
            $columns[] = $keys[$i];
            $i--;
        }
        return array_reverse($columns);
    }
}