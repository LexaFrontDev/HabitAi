<?php

declare(strict_types=1);

namespace Migrations\V1\Different;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Removes legacy notification and date fields from the tasks table.
 */
final class Version_20250701_030209_Remove_legacy_notification extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Removes notification_id, due_date, and date columns from the tasks table.';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('tasks')) {
            $table = $schema->getTable('tasks');
            $drops = [];
            if ($table->hasColumn('notification_id')) {
                $drops[] = 'DROP notification_id';
            }
            if ($table->hasColumn('due_date')) {
                $drops[] = 'DROP due_date';
            }
            if ($table->hasColumn('date')) {
                $drops[] = 'DROP date';
            }

            if (!empty($drops)) {
                $this->addSql('ALTER TABLE tasks '.implode(', ', $drops));
            }
        }
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('tasks')) {
            $table = $schema->getTable('tasks');
            $adds = [];
            if (!$table->hasColumn('notification_id')) {
                $adds[] = 'ADD notification_id INT DEFAULT NULL';
            }
            if (!$table->hasColumn('due_date')) {
                $adds[] = 'ADD due_date DATETIME DEFAULT NULL';
            }
            if (!$table->hasColumn('date')) {
                $adds[] = 'ADD date VARCHAR(255) DEFAULT NULL';
            }

            if (!empty($adds)) {
                $this->addSql('ALTER TABLE tasks '.implode(', ', $adds));
            }
        }
    }
}
