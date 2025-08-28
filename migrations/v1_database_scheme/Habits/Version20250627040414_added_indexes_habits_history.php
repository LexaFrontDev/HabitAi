<?php

declare(strict_types=1);

namespace Migrations\V1\Habits;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250627040414Addedindexeshabitshistory extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создание индексов по полям user_id, recorded_at в таблице habits_history с безопасной проверкой';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['habits_history'])) {
            $table = $sm->introspectTable('habits_history');
            $indexes = $table->getIndexes();

            $indexNames = array_map(fn ($index) => $index->getName(), $indexes);

            if (!in_array('idx_user', $indexNames, true)) {
                $this->addSql('CREATE INDEX idx_user ON habits_history (user_id)');
            }

            if (!in_array('idx_recorded_at', $indexNames, true)) {
                $this->addSql('CREATE INDEX idx_recorded_at ON habits_history (recorded_at)');
            }

            if (!in_array('idx_user_date', $indexNames, true)) {
                $this->addSql('CREATE INDEX idx_user_date ON habits_history (user_id, recorded_at)');
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['habits_history'])) {
            $table = $sm->introspectTable('habits_history');
            $indexes = $table->getIndexes();
            $indexNames = array_map(fn ($index) => $index->getName(), $indexes);

            if (in_array('idx_user', $indexNames, true)) {
                $this->addSql('DROP INDEX idx_user ON habits_history');
            }

            if (in_array('idx_recorded_at', $indexNames, true)) {
                $this->addSql('DROP INDEX idx_recorded_at ON habits_history');
            }

            if (in_array('idx_user_date', $indexNames, true)) {
                $this->addSql('DROP INDEX idx_user_date ON habits_history');
            }
        }
    }
}
