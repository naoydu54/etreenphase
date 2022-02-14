<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210602212047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actualities_products (actuality_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_E5E7E274B84BD854 (actuality_id), INDEX IDX_E5E7E2744584665A (product_id), PRIMARY KEY(actuality_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE actualities_products ADD CONSTRAINT FK_E5E7E274B84BD854 FOREIGN KEY (actuality_id) REFERENCES actuality (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE actualities_products ADD CONSTRAINT FK_E5E7E2744584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE actualities_products');
    }
}
