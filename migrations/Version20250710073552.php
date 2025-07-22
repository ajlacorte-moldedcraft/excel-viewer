<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250710073552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, province INT DEFAULT NULL, code VARCHAR(10) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, is_municipality TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_2D5B02344ADAD40B (province), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE province (id INT AUTO_INCREMENT NOT NULL, region INT DEFAULT NULL, code VARCHAR(10) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_4ADAD40B77153098 (code), INDEX IDX_4ADAD40BF62F176 (region), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(10) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_F62F17677153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE city ADD CONSTRAINT FK_2D5B02344ADAD40B FOREIGN KEY (province) REFERENCES province (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE province ADD CONSTRAINT FK_4ADAD40BF62F176 FOREIGN KEY (region) REFERENCES region (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE city DROP FOREIGN KEY FK_2D5B02344ADAD40B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE province DROP FOREIGN KEY FK_4ADAD40BF62F176
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE city
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE province
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE region
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
