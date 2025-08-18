<?php

declare(strict_types=1);

namespace Migrations\V1\Different;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250627101043Createstoragetable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создание таблицы storage с типами файлов, UID и датой создания';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if (!$sm->tablesExist(['storage'])) {
            $this->addSql(<<<'SQL'
                CREATE TABLE storage (
                    id INT AUTO_INCREMENT NOT NULL,
                    full_path VARCHAR(255) NOT NULL,
                    type VARCHAR(50) NOT NULL,
                    uid VARCHAR(100) NOT NULL,
                    file_type VARCHAR(20) NOT NULL,
                    created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
            SQL);
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['storage'])) {
            $this->addSql('DROP TABLE storage');
        }
    }
}
