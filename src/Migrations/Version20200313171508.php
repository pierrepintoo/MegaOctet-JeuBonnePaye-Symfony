<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200313171508 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jouer DROP FOREIGN KEY FK_825E5AEDA7F12751');
        $this->addSql('DROP TABLE joueur');
        $this->addSql('DROP INDEX IDX_825E5AEDA7F12751 ON jouer');
        $this->addSql('ALTER TABLE jouer CHANGE joueur_id_id user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jouer ADD CONSTRAINT FK_825E5AED9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_825E5AED9D86650F ON jouer (user_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE joueur (id INT AUTO_INCREMENT NOT NULL, joueur_pseudo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, joueur_mdp VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, joueur_email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, joueur_prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, joueur_nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, joueur_adresse VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, joueur_type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE jouer DROP FOREIGN KEY FK_825E5AED9D86650F');
        $this->addSql('DROP INDEX IDX_825E5AED9D86650F ON jouer');
        $this->addSql('ALTER TABLE jouer CHANGE user_id_id joueur_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jouer ADD CONSTRAINT FK_825E5AEDA7F12751 FOREIGN KEY (joueur_id_id) REFERENCES joueur (id)');
        $this->addSql('CREATE INDEX IDX_825E5AEDA7F12751 ON jouer (joueur_id_id)');
    }
}
