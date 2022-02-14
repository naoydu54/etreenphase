<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210602214714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actuality_has_product (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, actuality_id INT DEFAULT NULL, INDEX IDX_807308CB4584665A (product_id), INDEX IDX_807308CBB84BD854 (actuality_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE actuality_has_product ADD CONSTRAINT FK_807308CB4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE actuality_has_product ADD CONSTRAINT FK_807308CBB84BD854 FOREIGN KEY (actuality_id) REFERENCES actuality (id)');
        $this->addSql('DROP TABLE actualities_products');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actualities_products (actuality_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_E5E7E2744584665A (product_id), INDEX IDX_E5E7E274B84BD854 (actuality_id), PRIMARY KEY(actuality_id, product_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE actualities_products ADD CONSTRAINT FK_E5E7E2744584665A FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE actualities_products ADD CONSTRAINT FK_E5E7E274B84BD854 FOREIGN KEY (actuality_id) REFERENCES actuality (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE actuality_has_product');
    }
}
