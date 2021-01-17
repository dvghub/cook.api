<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210117163449 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE extra ADD recipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE extra ADD CONSTRAINT FK_4D3F0D6559D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_4D3F0D6559D8A214 ON extra (recipe_id)');
        $this->addSql('ALTER TABLE ingredient ADD recipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF787059D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6BAF787059D8A214 ON ingredient (recipe_id)');
        $this->addSql('ALTER TABLE step ADD recipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE step ADD CONSTRAINT FK_43B9FE3C59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_43B9FE3C59D8A214 ON step (recipe_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE extra DROP CONSTRAINT FK_4D3F0D6559D8A214');
        $this->addSql('DROP INDEX IDX_4D3F0D6559D8A214');
        $this->addSql('ALTER TABLE extra DROP recipe_id');
        $this->addSql('ALTER TABLE ingredient DROP CONSTRAINT FK_6BAF787059D8A214');
        $this->addSql('DROP INDEX IDX_6BAF787059D8A214');
        $this->addSql('ALTER TABLE ingredient DROP recipe_id');
        $this->addSql('ALTER TABLE step DROP CONSTRAINT FK_43B9FE3C59D8A214');
        $this->addSql('DROP INDEX IDX_43B9FE3C59D8A214');
        $this->addSql('ALTER TABLE step DROP recipe_id');
    }
}
