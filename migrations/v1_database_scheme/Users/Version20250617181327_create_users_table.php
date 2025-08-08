<?php

declare(strict_types=1);

namespace Migrations\V1\Users;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250617181327_create_users_table extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создание таблиц Users и messenger_messages, если они ещё не существуют';
    }

    public function up(Schema $schema): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist(['Users'])) {
            $this->addSql(<<<'SQL'
                CREATE TABLE Users (
                    id INT AUTO_INCREMENT NOT NULL,
                    name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    premium INT NOT NULL,
                    role VARCHAR(35) NOT NULL,
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
            SQL);
        }

        if (!$schemaManager->tablesExist(['messenger_messages'])) {
            $this->addSql(<<<'SQL'
                CREATE TABLE messenger_messages (
                    id BIGINT AUTO_INCREMENT NOT NULL,
                    body LONGTEXT NOT NULL,
                    headers LONGTEXT NOT NULL,
                    queue_name VARCHAR(190) NOT NULL,
                    created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
                    available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
                    delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
                    INDEX IDX_75EA56E0FB7336F0 (queue_name),
                    INDEX IDX_75EA56E0E3BD61CE (available_at),
                    INDEX IDX_75EA56E016BA31DB (delivered_at),
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
            SQL);
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS Users');
        $this->addSql('DROP TABLE IF EXISTS messenger_messages');
    }
}
