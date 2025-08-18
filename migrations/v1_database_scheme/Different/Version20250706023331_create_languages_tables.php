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
        return 'Creates language and language_page_translation tables for multilingual page support.';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if (!$sm->tablesExist(['language'])) {
            $this->addSql(<<<'SQL'
                CREATE TABLE language (
                    id INT AUTO_INCREMENT NOT NULL,
                    prefix VARCHAR(10) NOT NULL,
                    name VARCHAR(100) NOT NULL,
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
            SQL);
        }

        if (!$sm->tablesExist(['language_page_translation'])) {
            $this->addSql(<<<'SQL'
                CREATE TABLE language_page_translation (
                    id INT AUTO_INCREMENT NOT NULL,
                    language_id INT NOT NULL,
                    page_name VARCHAR(100) NOT NULL,
                    page_translate JSON NOT NULL,
                    INDEX IDX_AFF4D9D182F1BAF4 (language_id),
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
            SQL);

            $this->addSql(<<<'SQL'
                ALTER TABLE language_page_translation
                ADD CONSTRAINT FK_AFF4D9D182F1BAF4
                FOREIGN KEY (language_id) REFERENCES language (id)
            SQL);
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['language_page_translation'])) {
            $this->addSql(<<<'SQL'
                ALTER TABLE language_page_translation DROP FOREIGN KEY FK_AFF4D9D182F1BAF4
            SQL);
            $this->addSql('DROP TABLE language_page_translation');
        }

        if ($sm->tablesExist(['language'])) {
            $this->addSql('DROP TABLE language');
        }
    }
}
