<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210527111049 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_element ADD product_id INT DEFAULT NULL, ADD product_image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE cart_element ADD CONSTRAINT FK_BA9A963E4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_BA9A963E4584665A ON cart_element (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_element DROP FOREIGN KEY FK_BA9A963E4584665A');
        $this->addSql('DROP INDEX IDX_BA9A963E4584665A ON cart_element');
        $this->addSql('ALTER TABLE cart_element DROP product_id, DROP product_image');
    }
}
