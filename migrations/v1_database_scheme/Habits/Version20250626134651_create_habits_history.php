<?php

declare(strict_types=1);

namespace Migrations\V1\Habits;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250626134651Createhabitshistory extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создание таблицы habits_history с проверкой на существование';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if (!$sm->tablesExist(['habits_history'])) {
            $this->addSql(<<<'SQL'
                CREATE TABLE habits_history (
                    id INT AUTO_INCREMENT NOT NULL,
                    count INT NOT NULL,
                    habits_id INT NOT NULL,
                    count_end INT NOT NULL,
                    is_done TINYINT(1) NOT NULL,
                    created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
                    updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
                    is_deleted TINYINT(1) NOT NULL,
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
            SQL);
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['habits_history'])) {
            $this->addSql('DROP TABLE habits_history');
        }
    }
}
