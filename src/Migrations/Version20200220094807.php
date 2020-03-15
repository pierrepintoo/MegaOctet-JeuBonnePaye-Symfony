<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200220094807 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE carte (id INT AUTO_INCREMENT NOT NULL, carte_nom VARCHAR(255) DEFAULT NULL, carte_image_recto VARCHAR(255) DEFAULT NULL, carte_image_verso VARCHAR(255) DEFAULT NULL, carte_type VARCHAR(255) DEFAULT NULL, carte_effet VARCHAR(255) DEFAULT NULL, carte_montant INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partie (id INT AUTO_INCREMENT NOT NULL, partie_date_debut DATE DEFAULT NULL, partie_qui_joue LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', partie_gagnant VARCHAR(255) DEFAULT NULL, partie_pioche VARCHAR(255) DEFAULT NULL, partie_defausse VARCHAR(255) DEFAULT NULL, partie_cagnotte INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE joueur (id INT AUTO_INCREMENT NOT NULL, joueur_pseudo VARCHAR(255) NOT NULL, joueur_mdp VARCHAR(255) NOT NULL, joueur_email VARCHAR(255) NOT NULL, joueur_prenom VARCHAR(255) NOT NULL, joueur_nom VARCHAR(255) NOT NULL, joueur_adresse VARCHAR(255) DEFAULT NULL, joueur_type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE box (id INT AUTO_INCREMENT NOT NULL, box_position INT DEFAULT NULL, box_heure VARCHAR(255) DEFAULT NULL, box_image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE carte');
        $this->addSql('DROP TABLE partie');
        $this->addSql('DROP TABLE joueur');
        $this->addSql('DROP TABLE box');
    }
}
