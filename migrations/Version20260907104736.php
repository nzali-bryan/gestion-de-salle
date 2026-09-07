<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260907104736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE journee CHANGE caisse_fermeture caisse_fermeture INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sessions_jeu ADD CONSTRAINT FK_DCF5660CF066148 FOREIGN KEY (journee_id) REFERENCES journee (id)');
        $this->addSql('ALTER TABLE sessions_jeu ADD CONSTRAINT FK_DCF566072F9DD9F FOREIGN KEY (console_id) REFERENCES console (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE journee CHANGE caisse_fermeture caisse_fermeture INT NOT NULL');
        $this->addSql('ALTER TABLE sessions_jeu DROP FOREIGN KEY FK_DCF5660CF066148');
        $this->addSql('ALTER TABLE sessions_jeu DROP FOREIGN KEY FK_DCF566072F9DD9F');
    }
}
