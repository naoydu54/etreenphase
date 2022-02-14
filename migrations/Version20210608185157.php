<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210608185157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company ADD zip_code VARCHAR(255) NOT NULL, ADD social_reason VARCHAR(255) NOT NULL, ADD legal_form VARCHAR(255) NOT NULL, ADD effective INT NOT NULL, ADD siret VARCHAR(255) NOT NULL, ADD siret_concerned VARCHAR(255) NOT NULL, ADD naf_ape VARCHAR(255) NOT NULL, ADD firstname VARCHAR(255) NOT NULL, ADD lasname VARCHAR(255) NOT NULL, ADD number_phone VARCHAR(255) NOT NULL, ADD day_of_permanence VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company DROP zip_code, DROP social_reason, DROP legal_form, DROP effective, DROP siret, DROP siret_concerned, DROP naf_ape, DROP firstname, DROP lasname, DROP number_phone, DROP day_of_permanence, DROP email');
    }
}
