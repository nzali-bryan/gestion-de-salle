<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261013122859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, montan INT NOT NULL, type VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, creatad_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE sessions_jeu ADD CONSTRAINT FK_DCF5660CF066148 FOREIGN KEY (journee_id) REFERENCES journee (id)');
        $this->addSql('ALTER TABLE sessions_jeu ADD CONSTRAINT FK_DCF566072F9DD9F FOREIGN KEY (console_id) REFERENCES console (id)');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE transaction');
        $this->addSql('ALTER TABLE sessions_jeu DROP FOREIGN KEY FK_DCF5660CF066148');
        $this->addSql('ALTER TABLE sessions_jeu DROP FOREIGN KEY FK_DCF566072F9DD9F');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
