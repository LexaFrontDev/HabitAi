<?php

declare(strict_types=1);

namespace Migrations\V1\Tasks;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Adds user_id column to matric_column table
 */
final class Version20250708030931Editmatriccolumn extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user_id column to matric_column table';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('matric_column') && !$schema->getTable('matric_column')->hasColumn('user_id')) {
            $this->addSql('ALTER TABLE matric_column ADD user_id INT NOT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('matric_column') && $schema->getTable('matric_column')->hasColumn('user_id')) {
            $this->addSql('ALTER TABLE matric_column DROP user_id');
        }
    }
}
