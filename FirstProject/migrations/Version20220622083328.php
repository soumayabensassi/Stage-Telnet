<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220622083328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE device ADD domaine_application_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68EE7E6E326 FOREIGN KEY (domaine_application_id) REFERENCES domaine_application (id)');
        $this->addSql('CREATE INDEX IDX_92FB68EE7E6E326 ON device (domaine_application_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68EE7E6E326');
        $this->addSql('DROP INDEX IDX_92FB68EE7E6E326 ON device');
        $this->addSql('ALTER TABLE device DROP domaine_application_id');
    }
}
