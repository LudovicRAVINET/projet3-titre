<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210531153154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE birthday (id INT AUTO_INCREMENT NOT NULL, birthday_firstname VARCHAR(255) NOT NULL, birthday_lastname VARCHAR(255) NOT NULL, birthday_date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mourning (id INT AUTO_INCREMENT NOT NULL, dead_firstname VARCHAR(255) NOT NULL, dead_lastname VARCHAR(255) NOT NULL, dead_birth_day DATE NOT NULL, dead_death_day DATE NOT NULL, dead_biography LONGTEXT NOT NULL, relationship VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wedding (id INT AUTO_INCREMENT NOT NULL, husband_firstname VARCHAR(255) NOT NULL, husband_lastname VARCHAR(255) NOT NULL, wife_firtname VARCHAR(255) NOT NULL, wife_lastname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event DROP b_firstname, DROP b_lastname, DROP b_birth_date, DROP w_spouse1_firstname, DROP w_spouse1_lastname, DROP w_spouse2_firstname, DROP w_spouse2_lastname, DROP m_firstname, DROP m_lastname, DROP m_death_date, DROP m_birth_date, DROP m_biography, DROP m_relationship');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE birthday');
        $this->addSql('DROP TABLE mourning');
        $this->addSql('DROP TABLE wedding');
        $this->addSql('ALTER TABLE event ADD b_firstname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD b_lastname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD b_birth_date DATE DEFAULT NULL, ADD w_spouse1_firstname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD w_spouse1_lastname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD w_spouse2_firstname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD w_spouse2_lastname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD m_firstname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD m_lastname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD m_death_date DATE DEFAULT NULL, ADD m_birth_date DATE DEFAULT NULL, ADD m_biography LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD m_relationship VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
