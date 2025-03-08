<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250228153712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Changed table name from 'user' to 'users'
        $this->addSql('CREATE TABLE users (id SERIAL NOT NULL, name VARCHAR(24) NOT NULL, first_name VARCHAR(30) NOT NULL, last_name VARCHAR(30) NOT NULL, username VARCHAR(50) NOT NULL, email VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, profile_picture_path VARCHAR(255) DEFAULT NULL, bio VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN users.created_at IS '(DC2Type:datetime_immutable)'' );
        $this->addSql('COMMENT ON COLUMN users.updated_at IS '(DC2Type:datetime_immutable)'' );
    }

    public function down(Schema $schema): void
    {
        // Changed table name from 'user' to 'users'
        $this->addSql('DROP TABLE users');
    }
}
