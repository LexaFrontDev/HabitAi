<?php

declare(strict_types=1);

namespace Migrations\V1\Habits;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250625041914Edithabitsandpurpose extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Изменение таблиц Habits и purposes: переименование поля, добавление и удаление колонок';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['Habits'])) {
            $columns = $sm->introspectTable('Habits')->getColumns();

            $sqlParts = [];

            if (!isset($columns['notification_date'])) {
                $sqlParts[] = 'ADD notification_date VARCHAR(255) NOT NULL';
            }

            if (isset($columns['notification_id'])) {
                $sqlParts[] = 'CHANGE notification_id user_id INT DEFAULT NULL';
            }

            if (!empty($sqlParts)) {
                $this->addSql('ALTER TABLE Habits '.implode(', ', $sqlParts));
            }
        }

        if ($sm->tablesExist(['purposes'])) {
            $columns = $sm->introspectTable('purposes')->getColumns();

            $addParts = [];
            $dropParts = [];

            if (!isset($columns['habits_id'])) {
                $addParts[] = 'ADD habits_id INT NOT NULL';
            }
            if (!isset($columns['type'])) {
                $addParts[] = 'ADD type VARCHAR(255) NOT NULL';
            }
            if (!isset($columns['count'])) {
                $addParts[] = 'ADD count INT NOT NULL';
            }

            foreach (['cup_count', 'millimeter_count', 'minute_count', 'hour_count', 'kilometer_count', 'pages_count', 'new_count'] as $col) {
                if (isset($columns[$col])) {
                    $dropParts[] = 'DROP '.$col;
                }
            }

            $changeParts = [
                'CHANGE check_manually check_manually TINYINT(1) NOT NULL',
                'CHANGE check_auto check_auto TINYINT(1) NOT NULL',
                'CHANGE check_close check_close TINYINT(1) NOT NULL',
            ];

            $sqlParts = array_merge($addParts, $dropParts, $changeParts);

            if (!empty($sqlParts)) {
                $this->addSql('ALTER TABLE purposes '.implode(', ', $sqlParts));
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['Habits'])) {
            $this->addSql(<<<'SQL'
            ALTER TABLE Habits
                DROP notification_date,
                CHANGE user_id notification_id INT DEFAULT NULL
        SQL);
        }

        if ($sm->tablesExist(['purposes'])) {
            $columns = $sm->introspectTable('purposes')->getColumns();

            $addParts = [];
            $dropParts = [];

            foreach (['cup_count', 'millimeter_count', 'minute_count', 'hour_count', 'kilometer_count', 'pages_count', 'new_count'] as $col) {
                if (!isset($columns[$col])) {
                    $addParts[] = 'ADD '.$col.' INT DEFAULT NULL';
                }
            }

            foreach (['habits_id', 'type', 'count'] as $col) {
                if (isset($columns[$col])) {
                    $dropParts[] = 'DROP '.$col;
                }
            }

            $changeParts = [
                'CHANGE check_manually check_manually TINYINT(1) DEFAULT NULL',
                'CHANGE check_auto check_auto TINYINT(1) DEFAULT NULL',
                'CHANGE check_close check_close TINYINT(1) DEFAULT NULL',
            ];

            $sqlParts = array_merge($addParts, $dropParts, $changeParts);

            if (!empty($sqlParts)) {
                $this->addSql('ALTER TABLE purposes '.implode(', ', $sqlParts));
            }
        }
    }
}
