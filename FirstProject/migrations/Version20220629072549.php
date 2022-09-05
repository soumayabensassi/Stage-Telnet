<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220629072549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE autorisation (id INT AUTO_INCREMENT NOT NULL, bilanhydrique TINYINT(1) NOT NULL, temperature_agrigole TINYINT(1) NOT NULL, temperature_sante TINYINT(1) NOT NULL, blood TINYINT(1) NOT NULL, heartbeat TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE device ADD autorisation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68E52C5E836 FOREIGN KEY (autorisation_id) REFERENCES autorisation (id)');
        $this->addSql('CREATE INDEX IDX_92FB68E52C5E836 ON device (autorisation_id)');
        $this->addSql('ALTER TABLE donnee CHANGE timestamp timestamp DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68E52C5E836');
        $this->addSql('DROP TABLE autorisation');
        $this->addSql('DROP INDEX IDX_92FB68E52C5E836 ON device');
        $this->addSql('ALTER TABLE device DROP autorisation_id');
        $this->addSql('ALTER TABLE donnee CHANGE timestamp timestamp DATETIME DEFAULT NULL');
    }
}
