<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210519154804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD configurable TINYINT(1) NOT NULL, CHANGE reference reference VARCHAR(255) DEFAULT NULL, CHANGE price_public_ttc price_public_ttc DOUBLE PRECISION DEFAULT NULL, CHANGE price_ce_ttc price_ce_ttc DOUBLE PRECISION DEFAULT NULL, CHANGE content content LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP configurable, CHANGE reference reference VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE price_public_ttc price_public_ttc DOUBLE PRECISION NOT NULL, CHANGE price_ce_ttc price_ce_ttc DOUBLE PRECISION NOT NULL, CHANGE content content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
