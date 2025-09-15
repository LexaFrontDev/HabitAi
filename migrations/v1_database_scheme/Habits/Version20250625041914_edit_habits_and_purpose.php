<?php

declare(strict_types=1);

namespace Migrations\V1\Habits;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250625041914Edithabitsandpurpose extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Изменение таблиц habits и purposes: переименование поля, добавление и удаление колонок (PostgreSQL)';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        // --- HABITS ---
        if ($sm->tablesExist(['habits'])) {
            $columns = $sm->introspectTable('habits')->getColumns();

            if (!isset($columns['notification_date'])) {
                $this->addSql('ALTER TABLE habits ADD COLUMN notification_date VARCHAR(255) NOT NULL');
            }

            if (isset($columns['notification_id'])) {
                // Переименование notification_id → user_id
                $this->addSql('ALTER TABLE habits RENAME COLUMN notification_id TO user_id');
                // Установка DEFAULT NULL
                $this->addSql('ALTER TABLE habits ALTER COLUMN user_id DROP NOT NULL');
            }
        }

        // --- PURPOSES ---
        if ($sm->tablesExist(['purposes'])) {
            $columns = $sm->introspectTable('purposes')->getColumns();


            if (!isset($columns['habits_id'])) {
                $this->addSql('ALTER TABLE purposes ADD COLUMN habits_id INTEGER NOT NULL');
            }
            if (!isset($columns['type'])) {
                $this->addSql('ALTER TABLE purposes ADD COLUMN type VARCHAR(255) NOT NULL');
            }
            if (!isset($columns['count'])) {
                $this->addSql('ALTER TABLE purposes ADD COLUMN count INTEGER NOT NULL');
            }


            foreach (['cup_count', 'millimeter_count', 'minute_count', 'hour_count', 'kilometer_count', 'pages_count', 'new_count'] as $col) {
                if (isset($columns[$col])) {
                    $this->addSql("ALTER TABLE purposes DROP COLUMN $col");
                }
            }


            foreach (['check_manually', 'check_auto', 'check_close'] as $col) {
                if (isset($columns[$col])) {
                    $this->addSql("ALTER TABLE purposes ALTER COLUMN $col SET NOT NULL");
                    $this->addSql("ALTER TABLE purposes ALTER COLUMN $col TYPE BOOLEAN USING $col::BOOLEAN");
                }
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        // --- HABITS ---
        if ($sm->tablesExist(['habits'])) {
            $columns = $sm->introspectTable('habits')->getColumns();

            if (isset($columns['notification_date'])) {
                $this->addSql('ALTER TABLE habits DROP COLUMN notification_date');
            }
            if (isset($columns['user_id'])) {
                $this->addSql('ALTER TABLE habits RENAME COLUMN user_id TO notification_id');
                $this->addSql('ALTER TABLE habits ALTER COLUMN notification_id SET DEFAULT NULL');
            }
        }

        // --- PURPOSES ---
        if ($sm->tablesExist(['purposes'])) {
            $columns = $sm->introspectTable('purposes')->getColumns();


            foreach (['cup_count', 'millimeter_count', 'minute_count', 'hour_count', 'kilometer_count', 'pages_count', 'new_count'] as $col) {
                if (!isset($columns[$col])) {
                    $this->addSql("ALTER TABLE purposes ADD COLUMN $col INTEGER DEFAULT NULL");
                }
            }


            foreach (['habits_id', 'type', 'count'] as $col) {
                if (isset($columns[$col])) {
                    $this->addSql("ALTER TABLE purposes DROP COLUMN $col");
                }
            }


            foreach (['check_manually', 'check_auto', 'check_close'] as $col) {
                if (isset($columns[$col])) {
                    $this->addSql("ALTER TABLE purposes ALTER COLUMN $col DROP NOT NULL");
                }
            }
        }
    }
}
