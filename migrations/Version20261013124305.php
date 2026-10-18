<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261013124305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sessions_jeu ADD CONSTRAINT FK_DCF5660CF066148 FOREIGN KEY (journee_id) REFERENCES journee (id)');
        $this->addSql('ALTER TABLE sessions_jeu ADD CONSTRAINT FK_DCF566072F9DD9F FOREIGN KEY (console_id) REFERENCES console (id)');
        $this->addSql('ALTER TABLE transaction ADD sessions_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1F17C4D8C FOREIGN KEY (sessions_id) REFERENCES sessions_jeu (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_723705D1F17C4D8C ON transaction (sessions_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sessions_jeu DROP FOREIGN KEY FK_DCF5660CF066148');
        $this->addSql('ALTER TABLE sessions_jeu DROP FOREIGN KEY FK_DCF566072F9DD9F');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1F17C4D8C');
        $this->addSql('DROP INDEX UNIQ_723705D1F17C4D8C ON transaction');
        $this->addSql('ALTER TABLE transaction DROP sessions_id');
    }
}
