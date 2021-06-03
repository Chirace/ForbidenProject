<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210602195457 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE document_etudiant (document_id INT NOT NULL, etudiant_id INT NOT NULL, INDEX IDX_FEBFD3BAC33F7837 (document_id), INDEX IDX_FEBFD3BADDEAB1A3 (etudiant_id), PRIMARY KEY(document_id, etudiant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE document_etudiant ADD CONSTRAINT FK_FEBFD3BAC33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_etudiant ADD CONSTRAINT FK_FEBFD3BADDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76DDEAB1A3');
        $this->addSql('DROP INDEX IDX_D8698A76DDEAB1A3 ON document');
        $this->addSql('ALTER TABLE document DROP etudiant_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE document_etudiant');
        $this->addSql('ALTER TABLE document ADD etudiant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('CREATE INDEX IDX_D8698A76DDEAB1A3 ON document (etudiant_id)');
    }
}
