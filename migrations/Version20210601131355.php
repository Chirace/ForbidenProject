<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210601131355 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE administrateur (id INT AUTO_INCREMENT NOT NULL, personne_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_32EB52E8A21BD112 (personne_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, depositaire_id INT DEFAULT NULL, etudiant_id INT DEFAULT NULL, intitule VARCHAR(255) NOT NULL, etat_document VARCHAR(255) NOT NULL, commentaire VARCHAR(255) DEFAULT NULL, date_depot VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D8698A766104D4C3 (depositaire_id), INDEX IDX_D8698A76DDEAB1A3 (etudiant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, localite VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant (id INT AUTO_INCREMENT NOT NULL, formation_id INT DEFAULT NULL, personne_id INT DEFAULT NULL, entreprise_id INT DEFAULT NULL, planning_id INT DEFAULT NULL, type_contrat VARCHAR(255) DEFAULT NULL, note_suivi INT DEFAULT NULL, moyenne DOUBLE PRECISION DEFAULT NULL, INDEX IDX_717E22E35200282E (formation_id), UNIQUE INDEX UNIQ_717E22E3A21BD112 (personne_id), INDEX IDX_717E22E3A4AEAFEA (entreprise_id), INDEX IDX_717E22E33D865311 (planning_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluateur (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, INDEX IDX_BE15FA85A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planning (id INT AUTO_INCREMENT NOT NULL, date_passage DATETIME DEFAULT NULL, heure_passage DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responsable_ue (id INT AUTO_INCREMENT NOT NULL, personne_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_C2B5F0B9A21BD112 (personne_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scolarite (id INT AUTO_INCREMENT NOT NULL, personne_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_276250ABA21BD112 (personne_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tuteur (id INT AUTO_INCREMENT NOT NULL, personne_id INT DEFAULT NULL, commentaire VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_56412268A21BD112 (personne_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_document (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(255) NOT NULL, date_limite DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE administrateur ADD CONSTRAINT FK_32EB52E8A21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A766104D4C3 FOREIGN KEY (depositaire_id) REFERENCES personne (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E35200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3A21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E33D865311 FOREIGN KEY (planning_id) REFERENCES planning (id)');
        $this->addSql('ALTER TABLE evaluateur ADD CONSTRAINT FK_BE15FA85A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE responsable_ue ADD CONSTRAINT FK_C2B5F0B9A21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id)');
        $this->addSql('ALTER TABLE scolarite ADD CONSTRAINT FK_276250ABA21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id)');
        $this->addSql('ALTER TABLE tuteur ADD CONSTRAINT FK_56412268A21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3A4AEAFEA');
        $this->addSql('ALTER TABLE evaluateur DROP FOREIGN KEY FK_BE15FA85A4AEAFEA');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76DDEAB1A3');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E35200282E');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E33D865311');
        $this->addSql('DROP TABLE administrateur');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE evaluateur');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE planning');
        $this->addSql('DROP TABLE responsable_ue');
        $this->addSql('DROP TABLE scolarite');
        $this->addSql('DROP TABLE tuteur');
        $this->addSql('DROP TABLE type_document');
    }
}
