<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220621102313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement_client (abonnement_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_2577B9CAF1D74413 (abonnement_id), INDEX IDX_2577B9CA19EB6921 (client_id), PRIMARY KEY(abonnement_id, client_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abonnement_client ADD CONSTRAINT FK_2577B9CAF1D74413 FOREIGN KEY (abonnement_id) REFERENCES abonnement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE abonnement_client ADD CONSTRAINT FK_2577B9CA19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BB2DA17977');
        $this->addSql('DROP INDEX IDX_351268BB2DA17977 ON abonnement');
        $this->addSql('ALTER TABLE abonnement DROP User');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE abonnement_client');
        $this->addSql('ALTER TABLE abonnement ADD User INT DEFAULT NULL');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BB2DA17977 FOREIGN KEY (User) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_351268BB2DA17977 ON abonnement (User)');
    }
}
