<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230502145421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE casting ADD persons_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE casting ADD CONSTRAINT FK_D11BBA50FE812AD FOREIGN KEY (persons_id) REFERENCES person (id)');
        $this->addSql('CREATE INDEX IDX_D11BBA50FE812AD ON casting (persons_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE casting DROP FOREIGN KEY FK_D11BBA50FE812AD');
        $this->addSql('DROP INDEX IDX_D11BBA50FE812AD ON casting');
        $this->addSql('ALTER TABLE casting DROP persons_id');
    }
}
