<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623002838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_tutorial_and_recipe (product_id INT NOT NULL, tutorial_and_recipe_id INT NOT NULL, INDEX IDX_46B27A904584665A (product_id), INDEX IDX_46B27A90292B09BC (tutorial_and_recipe_id), PRIMARY KEY(product_id, tutorial_and_recipe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_tutorial_and_recipe ADD CONSTRAINT FK_46B27A904584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_tutorial_and_recipe ADD CONSTRAINT FK_46B27A90292B09BC FOREIGN KEY (tutorial_and_recipe_id) REFERENCES tutorial_and_recipe (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE products_tutorialAndRecipes');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE products_tutorialAndRecipes (product_id INT NOT NULL, tutorial_and_recipe_id INT NOT NULL, INDEX IDX_33F309D8292B09BC (tutorial_and_recipe_id), INDEX IDX_33F309D84584665A (product_id), PRIMARY KEY(product_id, tutorial_and_recipe_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE products_tutorialAndRecipes ADD CONSTRAINT FK_33F309D8292B09BC FOREIGN KEY (tutorial_and_recipe_id) REFERENCES tutorial_and_recipe (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_tutorialAndRecipes ADD CONSTRAINT FK_33F309D84584665A FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE product_tutorial_and_recipe');
    }
}
