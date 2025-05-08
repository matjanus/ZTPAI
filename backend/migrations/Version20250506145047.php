<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506145047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE session DROP CONSTRAINT fk_d044d5d479f37ae5
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_d044d5d479f37ae5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE session RENAME COLUMN id_user_id TO user_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE session ADD CONSTRAINT FK_D044D5D4A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D044D5D4A76ED395 ON session (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE session DROP CONSTRAINT FK_D044D5D4A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D044D5D4A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE session RENAME COLUMN user_id TO id_user_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE session ADD CONSTRAINT fk_d044d5d479f37ae5 FOREIGN KEY (id_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_d044d5d479f37ae5 ON session (id_user_id)
        SQL);
    }
}
