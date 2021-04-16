<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210416110016 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE action (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, mouvement_id INT DEFAULT NULL, type_mouvement_action_id INT DEFAULT NULL, constat_id INT DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, delai INT NOT NULL, nature_action VARCHAR(255) DEFAULT NULL, INDEX IDX_47CC8C92C54C8C93 (type_id), INDEX IDX_47CC8C92ECD1C222 (mouvement_id), INDEX IDX_47CC8C92E8BC2E97 (type_mouvement_action_id), INDEX IDX_47CC8C92BDDDB8C (constat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE alerte (id INT AUTO_INCREMENT NOT NULL, action_id INT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_3AE753A9D32F035 (action_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auteur (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, actif TINYINT(1) NOT NULL, INDEX IDX_55AB140C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE batiment (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, commune_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, addresse VARCHAR(255) DEFAULT NULL, distrib VARCHAR(255) DEFAULT NULL, date_debut DATETIME DEFAULT NULL, date_disparition DATETIME DEFAULT NULL, cedex VARCHAR(255) DEFAULT NULL, INDEX IDX_F5FAB00CF6BD1646 (site_id), INDEX IDX_F5FAB00C131A4F72 (commune_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, actif TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commune (id INT AUTO_INCREMENT NOT NULL, departement_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, date_debut DATETIME DEFAULT NULL, date_disparition DATETIME DEFAULT NULL, INDEX IDX_E2E2D1EECCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE constat (id INT AUTO_INCREMENT NOT NULL, sous_type_constat_id INT DEFAULT NULL, objet_mobilier_id INT DEFAULT NULL, date DATETIME NOT NULL, commentaire LONGTEXT DEFAULT NULL, titre_perception TINYINT(1) DEFAULT NULL, INDEX IDX_F96EDD98DAAF40C5 (sous_type_constat_id), INDEX IDX_F96EDD98B7105527 (objet_mobilier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE correspondant (id INT AUTO_INCREMENT NOT NULL, etablissement_id INT DEFAULT NULL, sous_direction_id INT DEFAULT NULL, service_id INT DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) DEFAULT NULL, date_debut DATETIME DEFAULT NULL, date_fin DATETIME DEFAULT NULL, INDEX IDX_E4DD79A3FF631228 (etablissement_id), INDEX IDX_E4DD79A310E381F2 (sous_direction_id), INDEX IDX_E4DD79A3ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE denomination (id INT AUTO_INCREMENT NOT NULL, domaine_id INT DEFAULT NULL, libelle VARCHAR(255) DEFAULT NULL, actif TINYINT(1) NOT NULL, INDEX IDX_15AEA10C4272FC9F (domaine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE denomination_matiere_technique (denomination_id INT NOT NULL, matiere_technique_id INT NOT NULL, INDEX IDX_CD45773AE9293F06 (denomination_id), INDEX IDX_CD45773A5564D221 (matiere_technique_id), PRIMARY KEY(denomination_id, matiere_technique_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, date_debut DATETIME DEFAULT NULL, date_disparition DATETIME DEFAULT NULL, INDEX IDX_C1765B6398260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deposant (id INT AUTO_INCREMENT NOT NULL, type_deposant_id INT DEFAULT NULL, sigle VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, departement VARCHAR(255) DEFAULT NULL, distrib VARCHAR(255) DEFAULT NULL, tel VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) DEFAULT NULL, date_debut DATETIME DEFAULT NULL, date_fin DATETIME DEFAULT NULL, contact VARCHAR(255) DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_64C4A25E4B42C9BD (type_deposant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE domaine (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, actif TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE epoque (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, actif TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etablissement (id INT AUTO_INCREMENT NOT NULL, ministere_id INT DEFAULT NULL, type_id INT DEFAULT NULL, libelle VARCHAR(255) DEFAULT NULL, sigle VARCHAR(255) DEFAULT NULL, date_debut DATETIME DEFAULT NULL, date_disparition DATETIME DEFAULT NULL, INDEX IDX_20FD592CAD745416 (ministere_id), INDEX IDX_20FD592CC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fichier_joint (id INT AUTO_INCREMENT NOT NULL, objet_mobilier_id INT DEFAULT NULL, date DATETIME DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, lien VARCHAR(255) NOT NULL, image_principale TINYINT(1) NOT NULL, INDEX IDX_AAACC83FB7105527 (objet_mobilier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE localisation (id INT AUTO_INCREMENT NOT NULL, etablissement_id INT DEFAULT NULL, sous_direction_id INT DEFAULT NULL, type_id INT DEFAULT NULL, piece_id INT DEFAULT NULL, INDEX IDX_BFD3CE8FFF631228 (etablissement_id), INDEX IDX_BFD3CE8F10E381F2 (sous_direction_id), INDEX IDX_BFD3CE8FC54C8C93 (type_id), INDEX IDX_BFD3CE8FC40FCFA8 (piece_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log_oeuvre (id INT AUTO_INCREMENT NOT NULL, objet_mobilier_id INT DEFAULT NULL, date DATETIME DEFAULT NULL, INDEX IDX_20047D5CB7105527 (objet_mobilier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere_technique (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, actif TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ministere (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, sigle VARCHAR(255) DEFAULT NULL, date_debut DATETIME DEFAULT NULL, date_disparition DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mobilier_bureau (id INT NOT NULL, fournisseur VARCHAR(255) DEFAULT NULL, prix_achat DOUBLE PRECISION DEFAULT NULL, etat VARCHAR(255) DEFAULT NULL, volume_unitaire DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mode_entree (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, actif TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mouvement (id INT AUTO_INCREMENT NOT NULL, localisation_id INT DEFAULT NULL, type_id INT DEFAULT NULL, objet_mobilier_id INT DEFAULT NULL, date DATETIME NOT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_5B51FC3EC68BE09C (localisation_id), INDEX IDX_5B51FC3EC54C8C93 (type_id), INDEX IDX_5B51FC3EB7105527 (objet_mobilier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mouvement_correspondant (mouvement_id INT NOT NULL, correspondant_id INT NOT NULL, INDEX IDX_57A584D2ECD1C222 (mouvement_id), INDEX IDX_57A584D2BBE04A3B (correspondant_id), PRIMARY KEY(mouvement_id, correspondant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE objet_mobilier (id INT AUTO_INCREMENT NOT NULL, epoque_id INT DEFAULT NULL, style_id INT DEFAULT NULL, matiere_technique_id INT DEFAULT NULL, denomination_id INT DEFAULT NULL, domaine_id INT DEFAULT NULL, status_id INT DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, longueur VARCHAR(100) DEFAULT NULL, largeur VARCHAR(100) DEFAULT NULL, hauteur VARCHAR(100) DEFAULT NULL, profondeur VARCHAR(100) DEFAULT NULL, diametre VARCHAR(100) DEFAULT NULL, poids VARCHAR(100) DEFAULT NULL, nombre_unite INT DEFAULT NULL, description_commentaire LONGTEXT DEFAULT NULL, table_associee VARCHAR(255) NOT NULL, INDEX IDX_A38B3DB645E7D711 (epoque_id), INDEX IDX_A38B3DB6BACD6074 (style_id), INDEX IDX_A38B3DB65564D221 (matiere_technique_id), INDEX IDX_A38B3DB6E9293F06 (denomination_id), INDEX IDX_A38B3DB64272FC9F (domaine_id), INDEX IDX_A38B3DB66BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE objet_mobilier_auteur (objet_mobilier_id INT NOT NULL, auteur_id INT NOT NULL, INDEX IDX_9EC00F42B7105527 (objet_mobilier_id), INDEX IDX_9EC00F4260BB6FE6 (auteur_id), PRIMARY KEY(objet_mobilier_id, auteur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oeuvre_art (id INT NOT NULL, date_creation DATETIME DEFAULT NULL, longueur_totale DOUBLE PRECISION DEFAULT NULL, largeur_totale DOUBLE PRECISION DEFAULT NULL, hauteur_totale DOUBLE PRECISION DEFAULT NULL, signature_inscription VARCHAR(255) DEFAULT NULL, mots_descriptifs VARCHAR(255) DEFAULT NULL, valeur_assurance INT DEFAULT NULL, date_valeur_assurance DATETIME DEFAULT NULL, date_depot DATETIME DEFAULT NULL, numero_arret INT DEFAULT NULL, autres_inscription VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE piece (id INT AUTO_INCREMENT NOT NULL, batiment_id INT DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, niveau VARCHAR(255) DEFAULT NULL, date_debut DATETIME DEFAULT NULL, date_fin DATETIME DEFAULT NULL, INDEX IDX_44CA0B23D6F6891B (batiment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, date_debut DATETIME DEFAULT NULL, date_disparition DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responsable (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) DEFAULT NULL, date_debut DATETIME DEFAULT NULL, date_fin DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responsable_batiment (responsable_id INT NOT NULL, batiment_id INT NOT NULL, INDEX IDX_D152DC1653C59D72 (responsable_id), INDEX IDX_D152DC16D6F6891B (batiment_id), PRIMARY KEY(responsable_id, batiment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, sous_direction_id INT DEFAULT NULL, libelle VARCHAR(255) DEFAULT NULL, sigle VARCHAR(255) DEFAULT NULL, date_debut DATETIME DEFAULT NULL, date_disparition DATETIME DEFAULT NULL, INDEX IDX_E19D9AD210E381F2 (sous_direction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, date_debut DATETIME DEFAULT NULL, date_disparition DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sous_direction (id INT AUTO_INCREMENT NOT NULL, etablissement_id INT DEFAULT NULL, libelle VARCHAR(255) DEFAULT NULL, sigle VARCHAR(255) DEFAULT NULL, date_debut DATETIME DEFAULT NULL, date_fin DATETIME DEFAULT NULL, INDEX IDX_25433AFCFF631228 (etablissement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sous_type_constat (id INT AUTO_INCREMENT NOT NULL, type_constat_id INT DEFAULT NULL, libelle VARCHAR(255) DEFAULT NULL, INDEX IDX_6EDFEA0816DE0DA9 (type_constat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statut (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) DEFAULT NULL, date_debut DATETIME DEFAULT NULL, date_fin DATETIME DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, table_associee VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statut_depot (id INT NOT NULL, deposant_id INT DEFAULT NULL, numero_inventaire INT DEFAULT NULL, date_depot DATETIME DEFAULT NULL, INDEX IDX_7569E4C25B1E2C (deposant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statut_propriete (id INT NOT NULL, mode_entree_id INT DEFAULT NULL, categorie_id INT DEFAULT NULL, date_entree DATETIME DEFAULT NULL, marquage VARCHAR(255) DEFAULT NULL, propUnPourCent TINYINT(1) DEFAULT NULL, INDEX IDX_D206CE51C8A5F4A8 (mode_entree_id), INDEX IDX_D206CE51BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE style (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, actif TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_auteur (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, actif TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_constat (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_constat_action (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_deposant (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, actif TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_etablissement (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_localisation (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_mouvement (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(150) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_mouvement_action (id INT AUTO_INCREMENT NOT NULL, type_mouvement_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_6A1383156B927827 (type_mouvement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(50) NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, password VARCHAR(100) DEFAULT NULL, roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', comment LONGTEXT DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C92C54C8C93 FOREIGN KEY (type_id) REFERENCES type_constat_action (id)');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C92ECD1C222 FOREIGN KEY (mouvement_id) REFERENCES mouvement (id)');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C92E8BC2E97 FOREIGN KEY (type_mouvement_action_id) REFERENCES type_mouvement_action (id)');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C92BDDDB8C FOREIGN KEY (constat_id) REFERENCES constat (id)');
        $this->addSql('ALTER TABLE alerte ADD CONSTRAINT FK_3AE753A9D32F035 FOREIGN KEY (action_id) REFERENCES action (id)');
        $this->addSql('ALTER TABLE auteur ADD CONSTRAINT FK_55AB140C54C8C93 FOREIGN KEY (type_id) REFERENCES type_auteur (id)');
        $this->addSql('ALTER TABLE batiment ADD CONSTRAINT FK_F5FAB00CF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE batiment ADD CONSTRAINT FK_F5FAB00C131A4F72 FOREIGN KEY (commune_id) REFERENCES commune (id)');
        $this->addSql('ALTER TABLE commune ADD CONSTRAINT FK_E2E2D1EECCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE constat ADD CONSTRAINT FK_F96EDD98DAAF40C5 FOREIGN KEY (sous_type_constat_id) REFERENCES sous_type_constat (id)');
        $this->addSql('ALTER TABLE constat ADD CONSTRAINT FK_F96EDD98B7105527 FOREIGN KEY (objet_mobilier_id) REFERENCES objet_mobilier (id)');
        $this->addSql('ALTER TABLE correspondant ADD CONSTRAINT FK_E4DD79A3FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE correspondant ADD CONSTRAINT FK_E4DD79A310E381F2 FOREIGN KEY (sous_direction_id) REFERENCES sous_direction (id)');
        $this->addSql('ALTER TABLE correspondant ADD CONSTRAINT FK_E4DD79A3ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE denomination ADD CONSTRAINT FK_15AEA10C4272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine (id)');
        $this->addSql('ALTER TABLE denomination_matiere_technique ADD CONSTRAINT FK_CD45773AE9293F06 FOREIGN KEY (denomination_id) REFERENCES denomination (id)');
        $this->addSql('ALTER TABLE denomination_matiere_technique ADD CONSTRAINT FK_CD45773A5564D221 FOREIGN KEY (matiere_technique_id) REFERENCES matiere_technique (id)');
        $this->addSql('ALTER TABLE departement ADD CONSTRAINT FK_C1765B6398260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE deposant ADD CONSTRAINT FK_64C4A25E4B42C9BD FOREIGN KEY (type_deposant_id) REFERENCES type_deposant (id)');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT FK_20FD592CAD745416 FOREIGN KEY (ministere_id) REFERENCES ministere (id)');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT FK_20FD592CC54C8C93 FOREIGN KEY (type_id) REFERENCES type_etablissement (id)');
        $this->addSql('ALTER TABLE fichier_joint ADD CONSTRAINT FK_AAACC83FB7105527 FOREIGN KEY (objet_mobilier_id) REFERENCES objet_mobilier (id)');
        $this->addSql('ALTER TABLE localisation ADD CONSTRAINT FK_BFD3CE8FFF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE localisation ADD CONSTRAINT FK_BFD3CE8F10E381F2 FOREIGN KEY (sous_direction_id) REFERENCES sous_direction (id)');
        $this->addSql('ALTER TABLE localisation ADD CONSTRAINT FK_BFD3CE8FC54C8C93 FOREIGN KEY (type_id) REFERENCES type_localisation (id)');
        $this->addSql('ALTER TABLE localisation ADD CONSTRAINT FK_BFD3CE8FC40FCFA8 FOREIGN KEY (piece_id) REFERENCES piece (id)');
        $this->addSql('ALTER TABLE log_oeuvre ADD CONSTRAINT FK_20047D5CB7105527 FOREIGN KEY (objet_mobilier_id) REFERENCES objet_mobilier (id)');
        $this->addSql('ALTER TABLE mobilier_bureau ADD CONSTRAINT FK_9573A921BF396750 FOREIGN KEY (id) REFERENCES objet_mobilier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mouvement ADD CONSTRAINT FK_5B51FC3EC68BE09C FOREIGN KEY (localisation_id) REFERENCES localisation (id)');
        $this->addSql('ALTER TABLE mouvement ADD CONSTRAINT FK_5B51FC3EC54C8C93 FOREIGN KEY (type_id) REFERENCES type_mouvement (id)');
        $this->addSql('ALTER TABLE mouvement ADD CONSTRAINT FK_5B51FC3EB7105527 FOREIGN KEY (objet_mobilier_id) REFERENCES objet_mobilier (id)');
        $this->addSql('ALTER TABLE mouvement_correspondant ADD CONSTRAINT FK_57A584D2ECD1C222 FOREIGN KEY (mouvement_id) REFERENCES mouvement (id)');
        $this->addSql('ALTER TABLE mouvement_correspondant ADD CONSTRAINT FK_57A584D2BBE04A3B FOREIGN KEY (correspondant_id) REFERENCES correspondant (id)');
        $this->addSql('ALTER TABLE objet_mobilier ADD CONSTRAINT FK_A38B3DB645E7D711 FOREIGN KEY (epoque_id) REFERENCES epoque (id)');
        $this->addSql('ALTER TABLE objet_mobilier ADD CONSTRAINT FK_A38B3DB6BACD6074 FOREIGN KEY (style_id) REFERENCES style (id)');
        $this->addSql('ALTER TABLE objet_mobilier ADD CONSTRAINT FK_A38B3DB65564D221 FOREIGN KEY (matiere_technique_id) REFERENCES matiere_technique (id)');
        $this->addSql('ALTER TABLE objet_mobilier ADD CONSTRAINT FK_A38B3DB6E9293F06 FOREIGN KEY (denomination_id) REFERENCES denomination (id)');
        $this->addSql('ALTER TABLE objet_mobilier ADD CONSTRAINT FK_A38B3DB64272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine (id)');
        $this->addSql('ALTER TABLE objet_mobilier ADD CONSTRAINT FK_A38B3DB66BF700BD FOREIGN KEY (status_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE objet_mobilier_auteur ADD CONSTRAINT FK_9EC00F42B7105527 FOREIGN KEY (objet_mobilier_id) REFERENCES objet_mobilier (id)');
        $this->addSql('ALTER TABLE objet_mobilier_auteur ADD CONSTRAINT FK_9EC00F4260BB6FE6 FOREIGN KEY (auteur_id) REFERENCES auteur (id)');
        $this->addSql('ALTER TABLE oeuvre_art ADD CONSTRAINT FK_39B8CDC0BF396750 FOREIGN KEY (id) REFERENCES objet_mobilier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE piece ADD CONSTRAINT FK_44CA0B23D6F6891B FOREIGN KEY (batiment_id) REFERENCES batiment (id)');
        $this->addSql('ALTER TABLE responsable_batiment ADD CONSTRAINT FK_D152DC1653C59D72 FOREIGN KEY (responsable_id) REFERENCES responsable (id)');
        $this->addSql('ALTER TABLE responsable_batiment ADD CONSTRAINT FK_D152DC16D6F6891B FOREIGN KEY (batiment_id) REFERENCES batiment (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD210E381F2 FOREIGN KEY (sous_direction_id) REFERENCES sous_direction (id)');
        $this->addSql('ALTER TABLE sous_direction ADD CONSTRAINT FK_25433AFCFF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE sous_type_constat ADD CONSTRAINT FK_6EDFEA0816DE0DA9 FOREIGN KEY (type_constat_id) REFERENCES type_constat (id)');
        $this->addSql('ALTER TABLE statut_depot ADD CONSTRAINT FK_7569E4C25B1E2C FOREIGN KEY (deposant_id) REFERENCES deposant (id)');
        $this->addSql('ALTER TABLE statut_depot ADD CONSTRAINT FK_7569E4C2BF396750 FOREIGN KEY (id) REFERENCES statut (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE statut_propriete ADD CONSTRAINT FK_D206CE51C8A5F4A8 FOREIGN KEY (mode_entree_id) REFERENCES mode_entree (id)');
        $this->addSql('ALTER TABLE statut_propriete ADD CONSTRAINT FK_D206CE51BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE statut_propriete ADD CONSTRAINT FK_D206CE51BF396750 FOREIGN KEY (id) REFERENCES statut (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type_mouvement_action ADD CONSTRAINT FK_6A1383156B927827 FOREIGN KEY (type_mouvement_id) REFERENCES type_mouvement (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alerte DROP FOREIGN KEY FK_3AE753A9D32F035');
        $this->addSql('ALTER TABLE objet_mobilier_auteur DROP FOREIGN KEY FK_9EC00F4260BB6FE6');
        $this->addSql('ALTER TABLE piece DROP FOREIGN KEY FK_44CA0B23D6F6891B');
        $this->addSql('ALTER TABLE responsable_batiment DROP FOREIGN KEY FK_D152DC16D6F6891B');
        $this->addSql('ALTER TABLE statut_propriete DROP FOREIGN KEY FK_D206CE51BCF5E72D');
        $this->addSql('ALTER TABLE batiment DROP FOREIGN KEY FK_F5FAB00C131A4F72');
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C92BDDDB8C');
        $this->addSql('ALTER TABLE mouvement_correspondant DROP FOREIGN KEY FK_57A584D2BBE04A3B');
        $this->addSql('ALTER TABLE denomination_matiere_technique DROP FOREIGN KEY FK_CD45773AE9293F06');
        $this->addSql('ALTER TABLE objet_mobilier DROP FOREIGN KEY FK_A38B3DB6E9293F06');
        $this->addSql('ALTER TABLE commune DROP FOREIGN KEY FK_E2E2D1EECCF9E01E');
        $this->addSql('ALTER TABLE statut_depot DROP FOREIGN KEY FK_7569E4C25B1E2C');
        $this->addSql('ALTER TABLE denomination DROP FOREIGN KEY FK_15AEA10C4272FC9F');
        $this->addSql('ALTER TABLE objet_mobilier DROP FOREIGN KEY FK_A38B3DB64272FC9F');
        $this->addSql('ALTER TABLE objet_mobilier DROP FOREIGN KEY FK_A38B3DB645E7D711');
        $this->addSql('ALTER TABLE correspondant DROP FOREIGN KEY FK_E4DD79A3FF631228');
        $this->addSql('ALTER TABLE localisation DROP FOREIGN KEY FK_BFD3CE8FFF631228');
        $this->addSql('ALTER TABLE sous_direction DROP FOREIGN KEY FK_25433AFCFF631228');
        $this->addSql('ALTER TABLE mouvement DROP FOREIGN KEY FK_5B51FC3EC68BE09C');
        $this->addSql('ALTER TABLE denomination_matiere_technique DROP FOREIGN KEY FK_CD45773A5564D221');
        $this->addSql('ALTER TABLE objet_mobilier DROP FOREIGN KEY FK_A38B3DB65564D221');
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY FK_20FD592CAD745416');
        $this->addSql('ALTER TABLE statut_propriete DROP FOREIGN KEY FK_D206CE51C8A5F4A8');
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C92ECD1C222');
        $this->addSql('ALTER TABLE mouvement_correspondant DROP FOREIGN KEY FK_57A584D2ECD1C222');
        $this->addSql('ALTER TABLE constat DROP FOREIGN KEY FK_F96EDD98B7105527');
        $this->addSql('ALTER TABLE fichier_joint DROP FOREIGN KEY FK_AAACC83FB7105527');
        $this->addSql('ALTER TABLE log_oeuvre DROP FOREIGN KEY FK_20047D5CB7105527');
        $this->addSql('ALTER TABLE mobilier_bureau DROP FOREIGN KEY FK_9573A921BF396750');
        $this->addSql('ALTER TABLE mouvement DROP FOREIGN KEY FK_5B51FC3EB7105527');
        $this->addSql('ALTER TABLE objet_mobilier_auteur DROP FOREIGN KEY FK_9EC00F42B7105527');
        $this->addSql('ALTER TABLE oeuvre_art DROP FOREIGN KEY FK_39B8CDC0BF396750');
        $this->addSql('ALTER TABLE localisation DROP FOREIGN KEY FK_BFD3CE8FC40FCFA8');
        $this->addSql('ALTER TABLE departement DROP FOREIGN KEY FK_C1765B6398260155');
        $this->addSql('ALTER TABLE responsable_batiment DROP FOREIGN KEY FK_D152DC1653C59D72');
        $this->addSql('ALTER TABLE correspondant DROP FOREIGN KEY FK_E4DD79A3ED5CA9E6');
        $this->addSql('ALTER TABLE batiment DROP FOREIGN KEY FK_F5FAB00CF6BD1646');
        $this->addSql('ALTER TABLE correspondant DROP FOREIGN KEY FK_E4DD79A310E381F2');
        $this->addSql('ALTER TABLE localisation DROP FOREIGN KEY FK_BFD3CE8F10E381F2');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD210E381F2');
        $this->addSql('ALTER TABLE constat DROP FOREIGN KEY FK_F96EDD98DAAF40C5');
        $this->addSql('ALTER TABLE objet_mobilier DROP FOREIGN KEY FK_A38B3DB66BF700BD');
        $this->addSql('ALTER TABLE statut_depot DROP FOREIGN KEY FK_7569E4C2BF396750');
        $this->addSql('ALTER TABLE statut_propriete DROP FOREIGN KEY FK_D206CE51BF396750');
        $this->addSql('ALTER TABLE objet_mobilier DROP FOREIGN KEY FK_A38B3DB6BACD6074');
        $this->addSql('ALTER TABLE auteur DROP FOREIGN KEY FK_55AB140C54C8C93');
        $this->addSql('ALTER TABLE sous_type_constat DROP FOREIGN KEY FK_6EDFEA0816DE0DA9');
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C92C54C8C93');
        $this->addSql('ALTER TABLE deposant DROP FOREIGN KEY FK_64C4A25E4B42C9BD');
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY FK_20FD592CC54C8C93');
        $this->addSql('ALTER TABLE localisation DROP FOREIGN KEY FK_BFD3CE8FC54C8C93');
        $this->addSql('ALTER TABLE mouvement DROP FOREIGN KEY FK_5B51FC3EC54C8C93');
        $this->addSql('ALTER TABLE type_mouvement_action DROP FOREIGN KEY FK_6A1383156B927827');
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C92E8BC2E97');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE alerte');
        $this->addSql('DROP TABLE auteur');
        $this->addSql('DROP TABLE batiment');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commune');
        $this->addSql('DROP TABLE constat');
        $this->addSql('DROP TABLE correspondant');
        $this->addSql('DROP TABLE denomination');
        $this->addSql('DROP TABLE denomination_matiere_technique');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE deposant');
        $this->addSql('DROP TABLE domaine');
        $this->addSql('DROP TABLE epoque');
        $this->addSql('DROP TABLE etablissement');
        $this->addSql('DROP TABLE fichier_joint');
        $this->addSql('DROP TABLE localisation');
        $this->addSql('DROP TABLE log_oeuvre');
        $this->addSql('DROP TABLE matiere_technique');
        $this->addSql('DROP TABLE ministere');
        $this->addSql('DROP TABLE mobilier_bureau');
        $this->addSql('DROP TABLE mode_entree');
        $this->addSql('DROP TABLE mouvement');
        $this->addSql('DROP TABLE mouvement_correspondant');
        $this->addSql('DROP TABLE objet_mobilier');
        $this->addSql('DROP TABLE objet_mobilier_auteur');
        $this->addSql('DROP TABLE oeuvre_art');
        $this->addSql('DROP TABLE piece');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE responsable');
        $this->addSql('DROP TABLE responsable_batiment');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE sous_direction');
        $this->addSql('DROP TABLE sous_type_constat');
        $this->addSql('DROP TABLE statut');
        $this->addSql('DROP TABLE statut_depot');
        $this->addSql('DROP TABLE statut_propriete');
        $this->addSql('DROP TABLE style');
        $this->addSql('DROP TABLE type_auteur');
        $this->addSql('DROP TABLE type_constat');
        $this->addSql('DROP TABLE type_constat_action');
        $this->addSql('DROP TABLE type_deposant');
        $this->addSql('DROP TABLE type_etablissement');
        $this->addSql('DROP TABLE type_localisation');
        $this->addSql('DROP TABLE type_mouvement');
        $this->addSql('DROP TABLE type_mouvement_action');
        $this->addSql('DROP TABLE user');
    }
}
