<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210519074531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE combination (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, attribute_item_id INT DEFAULT NULL, INDEX IDX_DE091AAF4584665A (product_id), INDEX IDX_DE091AAF99F30B9A (attribute_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE combination ADD CONSTRAINT FK_DE091AAF4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE combination ADD CONSTRAINT FK_DE091AAF99F30B9A FOREIGN KEY (attribute_item_id) REFERENCES attribute_item (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE combination');
    }
}
