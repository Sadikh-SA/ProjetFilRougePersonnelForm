<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190813201710 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transaction ADD commission_ttc_id INT NOT NULL, DROP commission_ttc');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1F4F86F58 FOREIGN KEY (commission_ttc_id) REFERENCES commission (id)');
        $this->addSql('CREATE INDEX IDX_723705D1F4F86F58 ON transaction (commission_ttc_id)');
        $this->addSql('ALTER TABLE utilisateur CHANGE image_name image_name VARCHAR(255) NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1F4F86F58');
        $this->addSql('DROP INDEX IDX_723705D1F4F86F58 ON transaction');
        $this->addSql('ALTER TABLE transaction ADD commission_ttc DOUBLE PRECISION NOT NULL, DROP commission_ttc_id');
        $this->addSql('ALTER TABLE utilisateur CHANGE image_name image_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }
}
