<?php

declare(strict_types=1);

namespace Migrations\V1\Tasks;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Creates tables for matrix column definitions and task-column junctions.
 */
final class Version_20250708_021744_Create_matric_column extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create matric_column and matric_junction tables for Eisenhower matrix structure';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('matric_column')) {
            $this->addSql(<<<'SQL'
                CREATE TABLE matric_column (
                    id INT AUTO_INCREMENT NOT NULL,
                    first_column_name VARCHAR(255) NOT NULL,
                    second_column_name VARCHAR(255) NOT NULL,
                    third_column_name VARCHAR(255) NOT NULL,
                    fourth_column_name VARCHAR(255) NOT NULL,
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
            SQL);
        }

        if (!$schema->hasTable('matric_junction')) {
            $this->addSql(<<<'SQL'
                CREATE TABLE matric_junction (
                    id INT AUTO_INCREMENT NOT NULL,
                    column_number INT NOT NULL,
                    task_number INT NOT NULL,
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
            SQL);
        }
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('matric_junction')) {
            $this->addSql('DROP TABLE matric_junction');
        }

        if ($schema->hasTable('matric_column')) {
            $this->addSql('DROP TABLE matric_column');
        }
    }
}
