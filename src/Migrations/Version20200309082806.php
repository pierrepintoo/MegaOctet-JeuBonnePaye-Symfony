<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200309082806 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jouer (id INT AUTO_INCREMENT NOT NULL, partie_id_id INT DEFAULT NULL, joueur_id_id INT DEFAULT NULL, classement INT DEFAULT NULL, argent INT DEFAULT NULL, cartes LONGTEXT DEFAULT NULL, pion VARCHAR(255) DEFAULT NULL, box INT DEFAULT NULL, de INT DEFAULT NULL, tour INT DEFAULT NULL, INDEX IDX_825E5AEDC3A2C2A5 (partie_id_id), INDEX IDX_825E5AEDA7F12751 (joueur_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jouer ADD CONSTRAINT FK_825E5AEDC3A2C2A5 FOREIGN KEY (partie_id_id) REFERENCES partie (id)');
        $this->addSql('ALTER TABLE jouer ADD CONSTRAINT FK_825E5AEDA7F12751 FOREIGN KEY (joueur_id_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE partie ADD partie_etat VARCHAR(255) DEFAULT NULL, ADD partie_date_fin VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE jouer');
        $this->addSql('ALTER TABLE partie DROP partie_etat, DROP partie_date_fin');
    }
}
