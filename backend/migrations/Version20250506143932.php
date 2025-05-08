<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506143932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE role ADD role_name VARCHAR(10) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE session ALTER last_activity TYPE DATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ALTER password TYPE VARCHAR(80)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ALTER password TYPE VARCHAR(60)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE session ALTER last_activity TYPE TIMESTAMP(0) WITHOUT TIME ZONE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE role DROP role_name
        SQL);
    }
}
