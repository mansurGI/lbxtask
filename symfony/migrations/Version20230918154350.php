<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230918154350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, uid INT UNSIGNED NOT NULL, status VARCHAR(30) NULL, username VARCHAR(255) NOT NULL, prefix VARCHAR(10) NOT NULL, firstname VARCHAR(255) NOT NULL, middle_initial CHAR(1) NOT NULL, lastname VARCHAR(255) NOT NULL, gender CHAR(1) NOT NULL, email VARCHAR(255) NOT NULL, birth_date DATE NOT NULL, birth_time TIME NOT NULL, age DOUBLE PRECISION(5, 2) NOT NULL, join_date DATE NOT NULL, phone CHAR(12) NOT NULL, tenure DOUBLE PRECISION(5, 2) NOT NULL, place VARCHAR(255) NOT NULL, county VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zipcode INT UNSIGNED NOT NULL, region VARCHAR(10) NOT NULL, UNIQUE INDEX UNIQ_5D9F75A1539B0606 (uid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE employee');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
