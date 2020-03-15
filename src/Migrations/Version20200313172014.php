<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200313172014 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jouer ADD CONSTRAINT FK_825E5AEDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_825E5AEDA76ED395 ON jouer (user_id)');
        $this->addSql('ALTER TABLE jouer RENAME INDEX idx_825e5aedc3a2c2a5 TO IDX_825E5AEDE075F7A4');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jouer DROP FOREIGN KEY FK_825E5AEDA76ED395');
        $this->addSql('DROP INDEX IDX_825E5AEDA76ED395 ON jouer');
        $this->addSql('ALTER TABLE jouer RENAME INDEX idx_825e5aede075f7a4 TO IDX_825E5AEDC3A2C2A5');
    }
}
