<?php

declare(strict_types=1);

namespace Migrations\V1\Users;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250705100411Addedsometablesinusers extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds "is_new", "email_check", "user_country", and "is_lang" columns to the "Users" table, if it exists.';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['Users'])) {
            $table = $sm->introspectTable('Users');
            $sqlParts = [];

            if (! $table->hasColumn('is_new')) {
                $sqlParts[] = 'ADD is_new TINYINT(1) NOT NULL';
            }
            if (! $table->hasColumn('email_check')) {
                $sqlParts[] = 'ADD email_check INT NOT NULL';
            }
            if (! $table->hasColumn('user_country')) {
                $sqlParts[] = 'ADD user_country VARCHAR(100) DEFAULT NULL';
            }
            if (! $table->hasColumn('is_lang')) {
                $sqlParts[] = 'ADD is_lang INT NOT NULL';
            }

            if (!empty($sqlParts)) {
                $this->addSql('ALTER TABLE Users '.implode(', ', $sqlParts));
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['Users'])) {
            $table = $sm->introspectTable('Users');
            $sqlParts = [];

            if ($table->hasColumn('is_new')) {
                $sqlParts[] = 'DROP is_new';
            }
            if ($table->hasColumn('email_check')) {
                $sqlParts[] = 'DROP email_check';
            }
            if ($table->hasColumn('user_country')) {
                $sqlParts[] = 'DROP user_country';
            }
            if ($table->hasColumn('is_lang')) {
                $sqlParts[] = 'DROP is_lang';
            }

            if (!empty($sqlParts)) {
                $this->addSql('ALTER TABLE Users '.implode(', ', $sqlParts));
            }
        }
    }
}
