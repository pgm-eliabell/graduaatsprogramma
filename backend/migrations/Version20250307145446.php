<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250307145446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE portfolios (id SERIAL NOT NULL, user_id_id INT DEFAULT NULL, profession_title VARCHAR(50) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, layout_config JSON DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, visible BOOLEAN NOT NULL, tags TEXT DEFAULT NULL, views INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B81B226F9D86650F ON portfolios (user_id_id)');
        $this->addSql('COMMENT ON COLUMN portfolios.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN portfolios.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN portfolios.tags IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE portfolios ADD CONSTRAINT FK_B81B226F9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ALTER is_verified DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE portfolios DROP CONSTRAINT FK_B81B226F9D86650F');
        $this->addSql('DROP TABLE portfolios');
        $this->addSql('ALTER TABLE "user" ALTER is_verified SET DEFAULT false');
    }
}
