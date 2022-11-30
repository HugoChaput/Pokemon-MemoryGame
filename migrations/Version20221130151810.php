<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221130151810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_user ADD game_id INT NOT NULL');
        $this->addSql('ALTER TABLE game_user ADD CONSTRAINT FK_6686BA65E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('CREATE INDEX IDX_6686BA65E48FD905 ON game_user (game_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_user DROP FOREIGN KEY FK_6686BA65E48FD905');
        $this->addSql('DROP INDEX IDX_6686BA65E48FD905 ON game_user');
        $this->addSql('ALTER TABLE game_user DROP game_id');
    }
}
