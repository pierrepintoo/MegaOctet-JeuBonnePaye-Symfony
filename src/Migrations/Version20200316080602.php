<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200316080602 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jouers (id INT AUTO_INCREMENT NOT NULL, partie_id INT DEFAULT NULL, user_id INT DEFAULT NULL, classement INT NOT NULL, argent INT DEFAULT NULL, cartes LONGTEXT NOT NULL, pion VARCHAR(20) DEFAULT NULL, box VARCHAR(255) DEFAULT NULL, de INT DEFAULT NULL, tour INT DEFAULT NULL, INDEX IDX_C5370F94E075F7A4 (partie_id), INDEX IDX_C5370F94A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jouers ADD CONSTRAINT FK_C5370F94E075F7A4 FOREIGN KEY (partie_id) REFERENCES partie (id)');
        $this->addSql('ALTER TABLE jouers ADD CONSTRAINT FK_C5370F94A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE Jouers');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Jouers (id INT NOT NULL, partie_id INT DEFAULT NULL, user_id INT DEFAULT NULL, classement INT DEFAULT NULL, argent INT DEFAULT NULL, cartes VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, pion INT DEFAULT NULL, box VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, de INT DEFAULT NULL, tour INT DEFAULT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE jouers');
    }
}
