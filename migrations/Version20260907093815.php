<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260907093815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sessions_jeu ADD journee_id INT NOT NULL, ADD console_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sessions_jeu ADD CONSTRAINT FK_DCF5660CF066148 FOREIGN KEY (journee_id) REFERENCES journee (id)');
        $this->addSql('ALTER TABLE sessions_jeu ADD CONSTRAINT FK_DCF566072F9DD9F FOREIGN KEY (console_id) REFERENCES console (id)');
        $this->addSql('CREATE INDEX IDX_DCF5660CF066148 ON sessions_jeu (journee_id)');
        $this->addSql('CREATE INDEX IDX_DCF566072F9DD9F ON sessions_jeu (console_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sessions_jeu DROP FOREIGN KEY FK_DCF5660CF066148');
        $this->addSql('ALTER TABLE sessions_jeu DROP FOREIGN KEY FK_DCF566072F9DD9F');
        $this->addSql('DROP INDEX IDX_DCF5660CF066148 ON sessions_jeu');
        $this->addSql('DROP INDEX IDX_DCF566072F9DD9F ON sessions_jeu');
        $this->addSql('ALTER TABLE sessions_jeu DROP journee_id, DROP console_id');
    }
}
