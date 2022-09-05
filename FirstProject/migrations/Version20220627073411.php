<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220627073411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE donnee (id INT AUTO_INCREMENT NOT NULL, timestamp DATETIME NOT NULL, data VARBINARY(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE device ADD donnee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68EC310CCAD FOREIGN KEY (donnee_id) REFERENCES donnee (id)');
        $this->addSql('CREATE INDEX IDX_92FB68EC310CCAD ON device (donnee_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68EC310CCAD');
        $this->addSql('DROP TABLE donnee');
        $this->addSql('DROP INDEX IDX_92FB68EC310CCAD ON device');
        $this->addSql('ALTER TABLE device DROP donnee_id');
    }
}
