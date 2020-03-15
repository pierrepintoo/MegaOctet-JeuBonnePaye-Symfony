<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200313170726 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jouer DROP FOREIGN KEY FK_825E5AEDA9E2D76C');
        $this->addSql('DROP INDEX IDX_825E5AEDA9E2D76C ON jouer');
        $this->addSql('ALTER TABLE jouer DROP joueur_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jouer ADD joueur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jouer ADD CONSTRAINT FK_825E5AEDA9E2D76C FOREIGN KEY (joueur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_825E5AEDA9E2D76C ON jouer (joueur_id)');
    }
}
