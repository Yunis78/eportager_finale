<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210501170004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, nom VARCHAR(45) NOT NULL, INDEX IDX_497DD634727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, payed_at DATETIME NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_6EEAA67DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_product (id INT AUTO_INCREMENT NOT NULL, commande_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, unit_price DOUBLE PRECISION NOT NULL, INDEX IDX_25F1760D82EA2E54 (commande_id), INDEX IDX_25F1760D4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, producer_id INT DEFAULT NULL, user_id INT DEFAULT NULL, status VARCHAR(15) NOT NULL, message LONGTEXT NOT NULL, rate INT NOT NULL, INDEX IDX_9474526C89B658FE (producer_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, producer_id INT DEFAULT NULL, product_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, INDEX IDX_8C9F361089B658FE (producer_id), INDEX IDX_8C9F36104584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE producer (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, siret VARCHAR(20) NOT NULL, phone INT NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_976449DCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, producer_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, stock INT NOT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_D34A04ADBCF5E72D (categorie_id), INDEX IDX_D34A04AD89B658FE (producer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, given_name VARCHAR(50) NOT NULL, family_name VARCHAR(50) NOT NULL, address_street VARCHAR(100) DEFAULT NULL, address_country VARCHAR(100) DEFAULT NULL, address_complement VARCHAR(100) DEFAULT NULL, address_zipcode INT DEFAULT NULL, address_city VARCHAR(100) DEFAULT NULL, phone INT NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD634727ACA70 FOREIGN KEY (parent_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande_product ADD CONSTRAINT FK_25F1760D82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_product ADD CONSTRAINT FK_25F1760D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C89B658FE FOREIGN KEY (producer_id) REFERENCES producer (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F361089B658FE FOREIGN KEY (producer_id) REFERENCES producer (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F36104584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE producer ADD CONSTRAINT FK_976449DCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD89B658FE FOREIGN KEY (producer_id) REFERENCES producer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_497DD634727ACA70');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADBCF5E72D');
        $this->addSql('ALTER TABLE commande_product DROP FOREIGN KEY FK_25F1760D82EA2E54');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C89B658FE');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F361089B658FE');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD89B658FE');
        $this->addSql('ALTER TABLE commande_product DROP FOREIGN KEY FK_25F1760D4584665A');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F36104584665A');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE producer DROP FOREIGN KEY FK_976449DCA76ED395');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commande_product');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE producer');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE user');
    }
}
