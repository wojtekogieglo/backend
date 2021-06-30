<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210630163406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(10) NOT NULL, f_name VARCHAR(100) NOT NULL, l_name VARCHAR(100) NOT NULL, state SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_product (person_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_F1D4B724217BBB47 (person_id), INDEX IDX_F1D4B7244584665A (product_id), PRIMARY KEY(person_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, info LONGTEXT NOT NULL, public_date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE person_product ADD CONSTRAINT FK_F1D4B724217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_product ADD CONSTRAINT FK_F1D4B7244584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person_product DROP FOREIGN KEY FK_F1D4B724217BBB47');
        $this->addSql('ALTER TABLE person_product DROP FOREIGN KEY FK_F1D4B7244584665A');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE person_product');
        $this->addSql('DROP TABLE product');
    }
}
