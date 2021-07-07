<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210531200238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, event_name VARCHAR(255) NOT NULL, event_date DATE NOT NULL, event_time TIME NOT NULL, event_address VARCHAR(255) NOT NULL, event_postal_code VARCHAR(255) NOT NULL, event_city VARCHAR(255) NOT NULL, event_country VARCHAR(255) NOT NULL, event_description LONGTEXT NOT NULL, event_created_at DATETIME NOT NULL, discriminator VARCHAR(255) NOT NULL, birthday_firstname VARCHAR(255) DEFAULT NULL, birthday_lastname VARCHAR(255) DEFAULT NULL, birthday_date DATE DEFAULT NULL, husband_firstname VARCHAR(255) DEFAULT NULL, husband_lastname VARCHAR(255) DEFAULT NULL, wife_firtname VARCHAR(255) DEFAULT NULL, wife_lastname VARCHAR(255) DEFAULT NULL, dead_firstname VARCHAR(255) DEFAULT NULL, dead_lastname VARCHAR(255) DEFAULT NULL, dead_birth_day DATE DEFAULT NULL, dead_death_day DATE DEFAULT NULL, dead_biography LONGTEXT DEFAULT NULL, relationship VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, event_id_id INT NOT NULL, message_text LONGTEXT NOT NULL, message_author VARCHAR(255) NOT NULL, message_date_time DATETIME NOT NULL, media_url VARCHAR(255) NOT NULL, INDEX IDX_B6BD307F3E5F2F7B (event_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F3E5F2F7B FOREIGN KEY (event_id_id) REFERENCES event (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F3E5F2F7B');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE message');
    }
}