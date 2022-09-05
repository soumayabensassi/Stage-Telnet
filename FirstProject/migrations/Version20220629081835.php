<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220629081835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE autorisation CHANGE bilanhydrique bilanhydrique TINYINT(1) DEFAULT NULL, CHANGE temperature_agrigole temperature_agrigole TINYINT(1) DEFAULT NULL, CHANGE temperature_sante temperature_sante TINYINT(1) DEFAULT NULL, CHANGE blood blood TINYINT(1) DEFAULT NULL, CHANGE heartbeat heartbeat TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE autorisation CHANGE bilanhydrique bilanhydrique TINYINT(1) NOT NULL, CHANGE temperature_agrigole temperature_agrigole TINYINT(1) NOT NULL, CHANGE temperature_sante temperature_sante TINYINT(1) NOT NULL, CHANGE blood blood TINYINT(1) NOT NULL, CHANGE heartbeat heartbeat TINYINT(1) NOT NULL');
    }
}
