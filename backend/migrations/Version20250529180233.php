<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250529180233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_f133dbd5322 RENAME TO idx_patron
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_d9f6ee47853cd175 RENAME TO idx_quiz
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_username ON "user" (username)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_email ON "user" (email)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_2408927399e6f5df RENAME TO idx_player
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_quiz RENAME TO idx_d9f6ee47853cd175
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_player RENAME TO idx_2408927399e6f5df
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_username
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_email
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_patron RENAME TO idx_f133dbd5322
        SQL);
    }
}
