<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250526080324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE access (id SERIAL NOT NULL, access_name VARCHAR(10) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE quiz (id SERIAL NOT NULL, owner_id INT NOT NULL, access_id INT NOT NULL, quiz_name VARCHAR(40) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A412FA927E3C61F9 ON quiz (owner_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A412FA924FEA67CF ON quiz (access_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE quiz_vocabulary (id SERIAL NOT NULL, quiz_id INT NOT NULL, word TEXT NOT NULL, translation TEXT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D9F6EE47853CD175 ON quiz_vocabulary (quiz_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_play (id SERIAL NOT NULL, player_id INT NOT NULL, quiz_id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2408927399E6F5DF ON user_play (player_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_24089273853CD175 ON user_play (quiz_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_rating (id SERIAL NOT NULL, rater_id INT NOT NULL, quiz_id INT NOT NULL, liked BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BDDB3D1F3FC1CD0A ON user_rating (rater_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BDDB3D1F853CD175 ON user_rating (quiz_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quiz ADD CONSTRAINT FK_A412FA927E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quiz ADD CONSTRAINT FK_A412FA924FEA67CF FOREIGN KEY (access_id) REFERENCES access (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quiz_vocabulary ADD CONSTRAINT FK_D9F6EE47853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_play ADD CONSTRAINT FK_2408927399E6F5DF FOREIGN KEY (player_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_play ADD CONSTRAINT FK_24089273853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_rating ADD CONSTRAINT FK_BDDB3D1F3FC1CD0A FOREIGN KEY (rater_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_rating ADD CONSTRAINT FK_BDDB3D1F853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quiz DROP CONSTRAINT FK_A412FA927E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quiz DROP CONSTRAINT FK_A412FA924FEA67CF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quiz_vocabulary DROP CONSTRAINT FK_D9F6EE47853CD175
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_play DROP CONSTRAINT FK_2408927399E6F5DF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_play DROP CONSTRAINT FK_24089273853CD175
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_rating DROP CONSTRAINT FK_BDDB3D1F3FC1CD0A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_rating DROP CONSTRAINT FK_BDDB3D1F853CD175
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE access
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE quiz
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE quiz_vocabulary
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_play
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_rating
        SQL);
    }
}
