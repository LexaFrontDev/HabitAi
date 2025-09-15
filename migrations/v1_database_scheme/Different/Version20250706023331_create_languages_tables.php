<?php

declare(strict_types=1);

namespace Migrations\V1\Different;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Creates "language" and "language_page_translation" tables with foreign key constraint.
 */
final class Version20250706023331Createlanguagestables extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates language and language_page_translation tables for multilingual page support (PostgreSQL).';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if (!$sm->tablesExist(['language'])) {
            $this->addSql(<<<'SQL'
                CREATE TABLE language (
                    id SERIAL PRIMARY KEY,
                    prefix VARCHAR(10) NOT NULL,
                    name VARCHAR(100) NOT NULL
                )
            SQL);
        }

        if (!$sm->tablesExist(['language_page_translation'])) {
            $this->addSql(<<<'SQL'
                CREATE TABLE language_page_translation (
                    id SERIAL PRIMARY KEY,
                    language_id INTEGER NOT NULL,
                    page_name VARCHAR(100) NOT NULL,
                    page_translate JSONB NOT NULL,
                    CONSTRAINT fk_language FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE
                )
            SQL);

            // Индекс для ускорения поиска по language_id
            $this->addSql('CREATE INDEX idx_language_translation_language_id ON language_page_translation (language_id)');
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['language_page_translation'])) {
            $this->addSql('DROP TABLE language_page_translation');
        }

        if ($sm->tablesExist(['language'])) {
            $this->addSql('DROP TABLE language');
        }
    }
}
