<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220629073408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE autorisation ADD device_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE autorisation ADD CONSTRAINT FK_9A4313494A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id)');
        $this->addSql('CREATE INDEX IDX_9A4313494A4C7D4 ON autorisation (device_id)');
        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68E52C5E836');
        $this->addSql('DROP INDEX IDX_92FB68E52C5E836 ON device');
        $this->addSql('ALTER TABLE device DROP autorisation_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE autorisation DROP FOREIGN KEY FK_9A4313494A4C7D4');
        $this->addSql('DROP INDEX IDX_9A4313494A4C7D4 ON autorisation');
        $this->addSql('ALTER TABLE autorisation DROP device_id');
        $this->addSql('ALTER TABLE device ADD autorisation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68E52C5E836 FOREIGN KEY (autorisation_id) REFERENCES autorisation (id)');
        $this->addSql('CREATE INDEX IDX_92FB68E52C5E836 ON device (autorisation_id)');
    }
}
