<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220214155821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE companies_menus ADD CONSTRAINT FK_91FC472B979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE companies_menus ADD CONSTRAINT FK_91FC472BCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE company_has_address ADD CONSTRAINT FK_736EBFC2979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93A977936C FOREIGN KEY (tree_root) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93727ACA70 FOREIGN KEY (parent_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93C4663E4 FOREIGN KEY (page_id) REFERENCES page (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398D7707B45 FOREIGN KEY (order_status_id) REFERENCES order_status (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE orders_carts ADD CONSTRAINT FK_FAAE50D8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders_carts ADD CONSTRAINT FK_FAAE50D1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_element ADD CONSTRAINT FK_B73AF7728D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_has_status ADD CONSTRAINT FK_E4F88FD2D7707B45 FOREIGN KEY (order_status_id) REFERENCES order_status (id)');
        $this->addSql('ALTER TABLE product_menu ADD CONSTRAINT FK_F0ED18324584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_menu ADD CONSTRAINT FK_F0ED1832CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_files ADD CONSTRAINT FK_E66CBE894584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_files ADD CONSTRAINT FK_E66CBE8993CB796C FOREIGN KEY (file_id) REFERENCES file (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_has_attribute_item ADD CONSTRAINT FK_28E368354584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_has_attribute_item ADD CONSTRAINT FK_28E3683599F30B9A FOREIGN KEY (attribute_item_id) REFERENCES attribute_item (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tutorialandrecipes_products ADD CONSTRAINT FK_C368D543292B09BC FOREIGN KEY (tutorial_and_recipe_id) REFERENCES tutorial_and_recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tutorialandrecipes_products ADD CONSTRAINT FK_C368D5434584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE users_carts ADD CONSTRAINT FK_A977EEE5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_carts ADD CONSTRAINT FK_A977EEE51AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE companies_menus DROP FOREIGN KEY FK_91FC472B979B1AD6');
        $this->addSql('ALTER TABLE companies_menus DROP FOREIGN KEY FK_91FC472BCCD7E912');
        $this->addSql('ALTER TABLE company_has_address DROP FOREIGN KEY FK_736EBFC2979B1AD6');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93A977936C');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93727ACA70');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93C4663E4');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398D7707B45');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398979B1AD6');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE order_element DROP FOREIGN KEY FK_B73AF7728D9F6D38');
        $this->addSql('ALTER TABLE order_has_status DROP FOREIGN KEY FK_E4F88FD2D7707B45');
        $this->addSql('ALTER TABLE orders_carts DROP FOREIGN KEY FK_FAAE50D8D9F6D38');
        $this->addSql('ALTER TABLE orders_carts DROP FOREIGN KEY FK_FAAE50D1AD5CDBF');
        $this->addSql('ALTER TABLE product_has_attribute_item DROP FOREIGN KEY FK_28E368354584665A');
        $this->addSql('ALTER TABLE product_has_attribute_item DROP FOREIGN KEY FK_28E3683599F30B9A');
        $this->addSql('ALTER TABLE product_menu DROP FOREIGN KEY FK_F0ED18324584665A');
        $this->addSql('ALTER TABLE product_menu DROP FOREIGN KEY FK_F0ED1832CCD7E912');
        $this->addSql('ALTER TABLE products_files DROP FOREIGN KEY FK_E66CBE894584665A');
        $this->addSql('ALTER TABLE products_files DROP FOREIGN KEY FK_E66CBE8993CB796C');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE tutorialAndRecipes_products DROP FOREIGN KEY FK_C368D543292B09BC');
        $this->addSql('ALTER TABLE tutorialAndRecipes_products DROP FOREIGN KEY FK_C368D5434584665A');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649979B1AD6');
        $this->addSql('ALTER TABLE user DROP deleted_at');
        $this->addSql('ALTER TABLE users_carts DROP FOREIGN KEY FK_A977EEE5A76ED395');
        $this->addSql('ALTER TABLE users_carts DROP FOREIGN KEY FK_A977EEE51AD5CDBF');
    }
}
