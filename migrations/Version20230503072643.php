<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503072643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE season ADD movies_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA953F590A4 FOREIGN KEY (movies_id) REFERENCES movie (id)');
        $this->addSql('CREATE INDEX IDX_F0E45BA953F590A4 ON season (movies_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE season DROP FOREIGN KEY FK_F0E45BA953F590A4');
        $this->addSql('DROP INDEX IDX_F0E45BA953F590A4 ON season');
        $this->addSql('ALTER TABLE season DROP movies_id');
    }
}
