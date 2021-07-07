<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210702092311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, event_name VARCHAR(255) NOT NULL, event_date DATE NOT NULL, event_time TIME NOT NULL, event_address VARCHAR(255) NOT NULL, event_postal_code VARCHAR(255) NOT NULL, event_city VARCHAR(255) NOT NULL, event_country VARCHAR(255) NOT NULL, event_description LONGTEXT DEFAULT NULL, event_created_at DATETIME NOT NULL, event_picture VARCHAR(255) DEFAULT NULL, discriminator VARCHAR(255) NOT NULL, birthday_firstname VARCHAR(255) DEFAULT NULL, birthday_lastname VARCHAR(255) DEFAULT NULL, birthday_date DATE DEFAULT NULL, husband_firstname VARCHAR(255) DEFAULT NULL, husband_lastname VARCHAR(255) DEFAULT NULL, wife_firstname VARCHAR(255) DEFAULT NULL, wife_lastname VARCHAR(255) DEFAULT NULL, dead_firstname VARCHAR(255) DEFAULT NULL, dead_lastname VARCHAR(255) DEFAULT NULL, dead_birth_day DATE DEFAULT NULL, dead_death_day DATE DEFAULT NULL, dead_biography LONGTEXT DEFAULT NULL, relationship VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gender (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, event_id_id INT NOT NULL, message_text LONGTEXT NOT NULL, message_author VARCHAR(255) NOT NULL, message_date_time DATETIME NOT NULL, media_url VARCHAR(255) DEFAULT NULL, INDEX IDX_B6BD307F3E5F2F7B (event_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, subscription_id INT DEFAULT NULL, gender_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, google_id VARCHAR(255) DEFAULT NULL, birth_date DATE NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6499A1887DC (subscription_id), INDEX IDX_8D93D649708A0E0 (gender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F3E5F2F7B FOREIGN KEY (event_id_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649708A0E0 FOREIGN KEY (gender_id) REFERENCES gender (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F3E5F2F7B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649708A0E0');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499A1887DC');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE gender');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE user');
    }
}
