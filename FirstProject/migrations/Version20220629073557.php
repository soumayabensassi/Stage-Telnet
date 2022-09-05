<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220629073557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE autorisation DROP INDEX IDX_9A4313494A4C7D4, ADD UNIQUE INDEX UNIQ_9A4313494A4C7D4 (device_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE autorisation DROP INDEX UNIQ_9A4313494A4C7D4, ADD INDEX IDX_9A4313494A4C7D4 (device_id)');
    }
}
