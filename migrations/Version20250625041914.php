<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250625041914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE habits ADD notification_date VARCHAR(255) NOT NULL, CHANGE notification_id user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purposes ADD habits_id INT NOT NULL, ADD type VARCHAR(255) NOT NULL, ADD count INT NOT NULL, DROP cup_count, DROP millimeter_count, DROP minute_count, DROP hour_count, DROP kilometer_count, DROP pages_count, DROP new_count, CHANGE check_manually check_manually TINYINT(1) NOT NULL, CHANGE check_auto check_auto TINYINT(1) NOT NULL, CHANGE check_close check_close TINYINT(1) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE habits DROP notification_date, CHANGE user_id notification_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purposes ADD cup_count INT DEFAULT NULL, ADD millimeter_count INT DEFAULT NULL, ADD minute_count INT DEFAULT NULL, ADD hour_count INT DEFAULT NULL, ADD kilometer_count INT DEFAULT NULL, ADD pages_count INT DEFAULT NULL, ADD new_count INT DEFAULT NULL, DROP habits_id, DROP type, DROP count, CHANGE check_manually check_manually TINYINT(1) DEFAULT NULL, CHANGE check_auto check_auto TINYINT(1) DEFAULT NULL, CHANGE check_close check_close TINYINT(1) DEFAULT NULL
        SQL);
    }
}
