<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240830093319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create `user` and `buyer` tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            '
            CREATE TABLE `buyer` (
                id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                name_name VARCHAR(255) NOT NULL,
                name_surname VARCHAR(255) NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql(
            '
            CREATE TABLE `user` (
                id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                buyer_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                role VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                email_value VARCHAR(255) NOT NULL,
                UNIQUE INDEX UNIQ_8D93D6496C755722 (buyer_id),
                UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email_value),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6496C755722 FOREIGN KEY (buyer_id) REFERENCES `buyer` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6496C755722');
        $this->addSql('DROP TABLE `buyer`');
        $this->addSql('DROP TABLE `user`');
    }
}
