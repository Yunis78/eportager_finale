<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210501204521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE producer_file');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE producer_file (producer_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_A7DBCC589B658FE (producer_id), INDEX IDX_A7DBCC593CB796C (file_id), PRIMARY KEY(producer_id, file_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE producer_file ADD CONSTRAINT FK_A7DBCC589B658FE FOREIGN KEY (producer_id) REFERENCES producer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producer_file ADD CONSTRAINT FK_A7DBCC593CB796C FOREIGN KEY (file_id) REFERENCES file (id) ON DELETE CASCADE');
    }
}
