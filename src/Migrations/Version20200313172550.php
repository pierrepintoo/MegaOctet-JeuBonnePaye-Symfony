<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200313172550 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jouer DROP FOREIGN KEY FK_825E5AEDC3A2C2A5');
        $this->addSql('DROP INDEX IDX_825E5AEDC3A2C2A5 ON jouer');
        $this->addSql('ALTER TABLE jouer ADD partie_id_id INT DEFAULT NULL, ADD user_id_id INT DEFAULT NULL, DROP partie_id, DROP user_id');
        $this->addSql('ALTER TABLE jouer ADD CONSTRAINT FK_825E5AED9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE jouer ADD CONSTRAINT FK_825E5AEDC3A2C2A5 FOREIGN KEY (partie_id_id) REFERENCES partie (id)');
        $this->addSql('CREATE INDEX IDX_825E5AED9D86650F ON jouer (user_id_id)');
        $this->addSql('CREATE INDEX IDX_825E5AEDC3A2C2A5 ON jouer (partie_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jouer DROP FOREIGN KEY FK_825E5AED9D86650F');
        $this->addSql('ALTER TABLE jouer DROP FOREIGN KEY FK_825E5AEDC3A2C2A5');
        $this->addSql('DROP INDEX IDX_825E5AED9D86650F ON jouer');
        $this->addSql('DROP INDEX IDX_825E5AEDC3A2C2A5 ON jouer');
        $this->addSql('ALTER TABLE jouer ADD partie_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, DROP partie_id_id, DROP user_id_id');
        $this->addSql('ALTER TABLE jouer ADD CONSTRAINT FK_825E5AEDC3A2C2A5 FOREIGN KEY (partie_id) REFERENCES partie (id)');
        $this->addSql('CREATE INDEX IDX_825E5AEDC3A2C2A5 ON jouer (partie_id)');
    }
}
