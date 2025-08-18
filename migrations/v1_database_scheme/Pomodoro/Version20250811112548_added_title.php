<?php

declare(strict_types=1);

namespace Migrations\V1\Pomodoro;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250811112548AddedTitle extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Добавляет колонку title в таблицу pomodoro_history, если её нет';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();
        $columns = $sm->listTableColumns('pomodoro_history');

        if (!array_key_exists('title', $columns)) {
            $this->addSql('ALTER TABLE pomodoro_history ADD title VARCHAR(120) NOT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();
        $columns = $sm->listTableColumns('pomodoro_history');

        if (array_key_exists('title', $columns)) {
            $this->addSql('ALTER TABLE pomodoro_history DROP title');
        }
    }
}
