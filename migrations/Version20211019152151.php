<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211019152151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('CREATE SEQUENCE bilemo_client_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE bilemo_phone_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE bilemo_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE bilemo_client (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BEDC5C89F85E0677 ON bilemo_client (username)');
        $this->addSql('CREATE TABLE bilemo_phone (id INT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, color VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE bilemo_user (id INT NOT NULL, client_id INT DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, birthday_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_69C6AC2519EB6921 ON bilemo_user (client_id)');
        $this->addSql('ALTER TABLE bilemo_user ADD CONSTRAINT FK_69C6AC2519EB6921 FOREIGN KEY (client_id) REFERENCES bilemo_client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE bilemo_user DROP CONSTRAINT FK_69C6AC2519EB6921');
        $this->addSql('DROP SEQUENCE bilemo_client_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE bilemo_phone_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE bilemo_user_id_seq CASCADE');
        $this->addSql('DROP TABLE bilemo_client');
        $this->addSql('DROP TABLE bilemo_phone');
        $this->addSql('DROP TABLE bilemo_user');
    }
}
