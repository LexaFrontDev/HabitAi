<?php

declare(strict_types=1);

namespace Migrations\V1\Users;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250705100411Addedsometablesinusers extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds "is_new", "email_check", "user_country", and "is_lang" columns to the "users" table, if it exists.';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();
        if ($sm->tablesExist(['users'])) {
            $table = $sm->introspectTable('users');

            if (!$table->hasColumn('is_new')) {
                $this->addSql('ALTER TABLE users ADD COLUMN is_new BOOLEAN NOT NULL');
            }
            if (!$table->hasColumn('email_check')) {
                $this->addSql('ALTER TABLE users ADD COLUMN email_check INTEGER NOT NULL');
            }
            if (!$table->hasColumn('user_country')) {
                $this->addSql('ALTER TABLE users ADD COLUMN user_country VARCHAR(100) DEFAULT NULL');
            }
            if (!$table->hasColumn('is_lang')) {
                $this->addSql('ALTER TABLE users ADD COLUMN is_lang INTEGER NOT NULL');
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['users'])) {
            $table = $sm->introspectTable('users');

            if ($table->hasColumn('is_new')) {
                $this->addSql('ALTER TABLE users DROP COLUMN is_new');
            }
            if ($table->hasColumn('email_check')) {
                $this->addSql('ALTER TABLE users DROP COLUMN email_check');
            }
            if ($table->hasColumn('user_country')) {
                $this->addSql('ALTER TABLE users DROP COLUMN user_country');
            }
            if ($table->hasColumn('is_lang')) {
                $this->addSql('ALTER TABLE users DROP COLUMN is_lang');
            }
        }
    }
}
