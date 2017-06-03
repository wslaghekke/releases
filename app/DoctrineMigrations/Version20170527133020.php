<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170527133020 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tenant (id INT AUTO_INCREMENT NOT NULL, addon_key VARCHAR(255) NOT NULL, client_key VARCHAR(255) NOT NULL, public_key VARCHAR(255) NOT NULL, shared_secret VARCHAR(255) NOT NULL, server_version VARCHAR(255) NOT NULL, plugins_version VARCHAR(255) NOT NULL, base_url VARCHAR(255) NOT NULL, product_type VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, event_type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_white_listed TINYINT(1) DEFAULT \'0\' NOT NULL, white_listed_until DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_4E59C462AD34380D (client_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tenant');
    }
}
