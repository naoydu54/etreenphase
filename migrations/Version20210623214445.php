<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623214445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tutorialAndRecipes_products (tutorial_and_recipe_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_C368D543292B09BC (tutorial_and_recipe_id), INDEX IDX_C368D5434584665A (product_id), PRIMARY KEY(tutorial_and_recipe_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tutorialAndRecipes_products ADD CONSTRAINT FK_C368D543292B09BC FOREIGN KEY (tutorial_and_recipe_id) REFERENCES tutorial_and_recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tutorialAndRecipes_products ADD CONSTRAINT FK_C368D5434584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE tutorial_and_recipe_has_product');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tutorial_and_recipe_has_product (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, tutorial_and_recipe_id INT DEFAULT NULL, INDEX IDX_96A6D4B9292B09BC (tutorial_and_recipe_id), INDEX IDX_96A6D4B94584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tutorial_and_recipe_has_product ADD CONSTRAINT FK_96A6D4B9292B09BC FOREIGN KEY (tutorial_and_recipe_id) REFERENCES tutorial_and_recipe (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tutorial_and_recipe_has_product ADD CONSTRAINT FK_96A6D4B94584665A FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE tutorialAndRecipes_products');
    }
}
