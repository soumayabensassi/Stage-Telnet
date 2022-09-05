<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220627134945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donnee DROP FOREIGN KEY FK_8527605C94A4C7D4');
        $this->addSql('ALTER TABLE donnee ADD CONSTRAINT FK_8527605C94A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donnee DROP FOREIGN KEY FK_8527605C94A4C7D4');
        $this->addSql('ALTER TABLE donnee ADD CONSTRAINT FK_8527605C94A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
