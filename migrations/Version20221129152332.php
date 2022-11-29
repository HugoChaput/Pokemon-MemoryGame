<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221129152332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_pokemon (game_id INT NOT NULL, pokemon_id INT NOT NULL, INDEX IDX_F71EEFDCE48FD905 (game_id), INDEX IDX_F71EEFDC2FE71C3E (pokemon_id), PRIMARY KEY(game_id, pokemon_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_pokemon ADD CONSTRAINT FK_F71EEFDCE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_pokemon ADD CONSTRAINT FK_F71EEFDC2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_pokemon DROP FOREIGN KEY FK_F71EEFDCE48FD905');
        $this->addSql('ALTER TABLE game_pokemon DROP FOREIGN KEY FK_F71EEFDC2FE71C3E');
        $this->addSql('DROP TABLE game_pokemon');
    }
}
