<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200316175628 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jouer (id INT AUTO_INCREMENT NOT NULL, partie_id INT DEFAULT NULL, user_id INT DEFAULT NULL, classement INT NOT NULL, argent DOUBLE PRECISION DEFAULT NULL, pion INT DEFAULT NULL, box INT DEFAULT NULL, de INT DEFAULT NULL, tour INT DEFAULT NULL, INDEX IDX_825E5AEDE075F7A4 (partie_id), INDEX IDX_825E5AEDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jouer ADD CONSTRAINT FK_825E5AEDE075F7A4 FOREIGN KEY (partie_id) REFERENCES partie (id)');
        $this->addSql('ALTER TABLE jouer ADD CONSTRAINT FK_825E5AEDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE partie_user');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE partie_user (partie_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D4C3EF8E075F7A4 (partie_id), INDEX IDX_D4C3EF8A76ED395 (user_id), PRIMARY KEY(partie_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE partie_user ADD CONSTRAINT FK_D4C3EF8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partie_user ADD CONSTRAINT FK_D4C3EF8E075F7A4 FOREIGN KEY (partie_id) REFERENCES partie (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE jouer');
    }
}
