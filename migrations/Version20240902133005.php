<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240902133005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add `goods`, `goods_code`, `purchase` and `rental` tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE `goods` (
            id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                name VARCHAR(255) NOT NULL,
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                price_purchase INT NOT NULL,
                price_four_hours INT NOT NULL,
                price_eight_hours INT NOT NULL,
                price_twelve_hours INT NOT NULL,
                price_twenty_four_hours INT NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql(
            'CREATE TABLE `goods_code` (
            id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
            goods_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
            buyer_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
            deal_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
            deal_type VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            UNIQUE INDEX UNIQ_44FD2B1AF60E2305 (deal_id),
            INDEX IDX_44FD2B1AB7683595 (goods_id),
            INDEX IDX_44FD2B1A6C755722 (buyer_id),
            PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql(
            'CREATE TABLE `purchase` (
                id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                goods_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                buyer_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                price INT NOT NULL,
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                INDEX IDX_6117D13BB7683595 (goods_id),
                INDEX IDX_6117D13B6C755722 (buyer_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql(
            'CREATE TABLE `rental` (
                id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                goods_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                buyer_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                duration VARCHAR(255) NOT NULL,
                price INT NOT NULL,
                start_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                end_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                INDEX IDX_1619C27DB7683595 (goods_id),
                INDEX IDX_1619C27D6C755722 (buyer_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql('ALTER TABLE `goods_code` ADD CONSTRAINT FK_44FD2B1AB7683595 FOREIGN KEY (goods_id) REFERENCES `goods` (id)');
        $this->addSql('ALTER TABLE `goods_code` ADD CONSTRAINT FK_44FD2B1A6C755722 FOREIGN KEY (buyer_id) REFERENCES `buyer` (id)');

        $this->addSql('ALTER TABLE `purchase` ADD CONSTRAINT FK_6117D13BB7683595 FOREIGN KEY (goods_id) REFERENCES `goods` (id)');
        $this->addSql('ALTER TABLE `purchase` ADD CONSTRAINT FK_6117D13B6C755722 FOREIGN KEY (buyer_id) REFERENCES `buyer` (id)');

        $this->addSql('ALTER TABLE `rental` ADD CONSTRAINT FK_1619C27DB7683595 FOREIGN KEY (goods_id) REFERENCES `goods` (id)');
        $this->addSql('ALTER TABLE `rental` ADD CONSTRAINT FK_1619C27D6C755722 FOREIGN KEY (buyer_id) REFERENCES `buyer` (id)');

        $this->addSql('ALTER TABLE buyer ADD money_amount INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `goods_code` DROP FOREIGN KEY FK_44FD2B1AB7683595');
        $this->addSql('ALTER TABLE `goods_code` DROP FOREIGN KEY FK_44FD2B1A6C755722');

        $this->addSql('ALTER TABLE `purchase` DROP FOREIGN KEY FK_6117D13BB7683595');
        $this->addSql('ALTER TABLE `purchase` DROP FOREIGN KEY FK_6117D13B6C755722');

        $this->addSql('ALTER TABLE `rental` DROP FOREIGN KEY FK_1619C27DB7683595');
        $this->addSql('ALTER TABLE `rental` DROP FOREIGN KEY FK_1619C27D6C755722');

        $this->addSql('DROP TABLE `goods`');
        $this->addSql('DROP TABLE `goods_code`');
        $this->addSql('DROP TABLE `purchase`');
        $this->addSql('DROP TABLE `rental`');
        $this->addSql('ALTER TABLE `buyer` DROP money_amount');
    }
}