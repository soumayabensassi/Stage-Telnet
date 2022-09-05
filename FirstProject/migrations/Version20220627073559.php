<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220627073559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68EC310CCAD');
        $this->addSql('DROP INDEX IDX_92FB68EC310CCAD ON device');
        $this->addSql('ALTER TABLE device DROP donnee_id');
        $this->addSql('ALTER TABLE donnee ADD device_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE donnee ADD CONSTRAINT FK_8527605C94A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id)');
        $this->addSql('CREATE INDEX IDX_8527605C94A4C7D4 ON donnee (device_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE device ADD donnee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68EC310CCAD FOREIGN KEY (donnee_id) REFERENCES donnee (id)');
        $this->addSql('CREATE INDEX IDX_92FB68EC310CCAD ON device (donnee_id)');
        $this->addSql('ALTER TABLE donnee DROP FOREIGN KEY FK_8527605C94A4C7D4');
        $this->addSql('DROP INDEX IDX_8527605C94A4C7D4 ON donnee');
        $this->addSql('ALTER TABLE donnee DROP device_id');
    }
}
