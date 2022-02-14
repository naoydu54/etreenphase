<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210517180136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD order_has_status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398E731DA20 FOREIGN KEY (order_has_status_id) REFERENCES order_has_status (id)');
        $this->addSql('CREATE INDEX IDX_F5299398E731DA20 ON `order` (order_has_status_id)');
        $this->addSql('ALTER TABLE order_has_status ADD order_status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_has_status ADD CONSTRAINT FK_E4F88FD2D7707B45 FOREIGN KEY (order_status_id) REFERENCES order_status (id)');
        $this->addSql('CREATE INDEX IDX_E4F88FD2D7707B45 ON order_has_status (order_status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398E731DA20');
        $this->addSql('DROP INDEX IDX_F5299398E731DA20 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP order_has_status_id');
        $this->addSql('ALTER TABLE order_has_status DROP FOREIGN KEY FK_E4F88FD2D7707B45');
        $this->addSql('DROP INDEX IDX_E4F88FD2D7707B45 ON order_has_status');
        $this->addSql('ALTER TABLE order_has_status DROP order_status_id');
    }
}
