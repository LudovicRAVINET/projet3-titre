<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210531153316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE mourning');
        $this->addSql('DROP TABLE wedding');
        $this->addSql('ALTER TABLE event ADD husband_firstname VARCHAR(255) DEFAULT NULL, ADD husband_lastname VARCHAR(255) DEFAULT NULL, ADD wife_firtname VARCHAR(255) DEFAULT NULL, ADD wife_lastname VARCHAR(255) DEFAULT NULL, ADD dead_firstname VARCHAR(255) DEFAULT NULL, ADD dead_lastname VARCHAR(255) DEFAULT NULL, ADD dead_birth_day DATE DEFAULT NULL, ADD dead_death_day DATE DEFAULT NULL, ADD dead_biography LONGTEXT DEFAULT NULL, ADD relationship VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mourning (id INT AUTO_INCREMENT NOT NULL, dead_firstname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, dead_lastname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, dead_birth_day DATE NOT NULL, dead_death_day DATE NOT NULL, dead_biography LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, relationship VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE wedding (id INT AUTO_INCREMENT NOT NULL, husband_firstname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, husband_lastname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, wife_firtname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, wife_lastname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE event DROP husband_firstname, DROP husband_lastname, DROP wife_firtname, DROP wife_lastname, DROP dead_firstname, DROP dead_lastname, DROP dead_birth_day, DROP dead_death_day, DROP dead_biography, DROP relationship');
    }
}
