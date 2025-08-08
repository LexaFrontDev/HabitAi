<?php

declare(strict_types=1);

namespace Migrations\V1\Pomodoro;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250623021008_added_pomodoro_history_default_null extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Изменение target_id в pomodor_history на DEFAULT NULL';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['pomodor_history'])) {
            $columns = $sm->introspectTable('pomodor_history')->getColumns();

            if (isset($columns['target_id'])) {
                $this->addSql('ALTER TABLE pomodor_history CHANGE target_id target_id INT DEFAULT NULL');
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['pomodor_history'])) {
            $columns = $sm->introspectTable('pomodor_history')->getColumns();

            if (isset($columns['target_id'])) {
                $this->addSql('ALTER TABLE pomodor_history CHANGE target_id target_id INT NOT NULL');
            }
        }
    }

}
