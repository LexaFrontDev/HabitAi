<?php

declare(strict_types=1);

namespace Migrations\V1\Pomodoro;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250622052239_created_table_pomodoro_history extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создание таблицы pomodor_history с проверкой существования';
    }

    public function up(Schema $schema): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist(['pomodor_history'])) {
            $this->addSql(<<<'SQL'
                CREATE TABLE pomodor_history (
                    id INT AUTO_INCREMENT NOT NULL,
                    user_id INT NOT NULL,
                    time_focus INT NOT NULL,
                    period_label VARCHAR(10) NOT NULL,
                    focus_start DATETIME NOT NULL,
                    focus_end DATETIME NOT NULL,
                    create_date DATETIME NOT NULL,
                    update_date DATETIME DEFAULT NULL,
                    is_delete INT NOT NULL,
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
            SQL);
        }
    }

    public function down(Schema $schema): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if ($schemaManager->tablesExist(['pomodor_history'])) {
            $this->addSql('DROP TABLE pomodor_history');
        }
    }
}
