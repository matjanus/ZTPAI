<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250605231036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE favourite_quiz DROP CONSTRAINT FK_F133DBD5322
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favourite_quiz ADD CONSTRAINT FK_F133DBD5322 FOREIGN KEY (patron_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quiz DROP CONSTRAINT FK_A412FA927E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quiz ADD CONSTRAINT FK_A412FA927E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_play DROP CONSTRAINT FK_2408927399E6F5DF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_play ADD CONSTRAINT FK_2408927399E6F5DF FOREIGN KEY (player_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favourite_quiz DROP CONSTRAINT fk_f133dbd5322
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favourite_quiz ADD CONSTRAINT fk_f133dbd5322 FOREIGN KEY (patron_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quiz DROP CONSTRAINT fk_a412fa927e3c61f9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quiz ADD CONSTRAINT fk_a412fa927e3c61f9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_play DROP CONSTRAINT fk_2408927399e6f5df
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_play ADD CONSTRAINT fk_2408927399e6f5df FOREIGN KEY (player_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }
}
