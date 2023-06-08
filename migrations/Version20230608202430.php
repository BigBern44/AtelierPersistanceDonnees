<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230608202430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emprunt CHANGE date_emprunt date_emprunt DATETIME NOT NULL, CHANGE date_fin_prevue date_fin_prevue DATETIME NOT NULL, CHANGE date_retour date_retour DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE livre ADD image_url VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emprunt CHANGE date_emprunt date_emprunt DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE date_fin_prevue date_fin_prevue DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE date_retour date_retour DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE livre DROP image_url');
    }
}
