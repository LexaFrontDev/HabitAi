<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250627040414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_user ON habits_history (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_recorded_at ON habits_history (recorded_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_user_date ON habits_history (user_id, recorded_at)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX idx_user ON habits_history
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_recorded_at ON habits_history
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_user_date ON habits_history
        SQL);
    }
}
