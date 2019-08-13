<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190813185215 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, nom_envoyeur VARCHAR(255) NOT NULL, prenom_envoyeur VARCHAR(255) NOT NULL, adresse_envoyeur VARCHAR(255) DEFAULT NULL, tel_envoyeur VARCHAR(255) NOT NULL, cnienvoyeur VARCHAR(255) NOT NULL, nom_beneficiaire VARCHAR(255) NOT NULL, prenom_beneficiaire VARCHAR(255) NOT NULL, tel_beneficiaire VARCHAR(255) NOT NULL, adresse_beneficiaire VARCHAR(255) DEFAULT NULL, numero_transaction DOUBLE PRECISION NOT NULL, montant_envoyer DOUBLE PRECISION NOT NULL, commission_ttc DOUBLE PRECISION NOT NULL, total_envoyer DOUBLE PRECISION NOT NULL, montant_retirer DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_723705D13D880A1E (numero_transaction), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE utilisateur CHANGE image_name image_name VARCHAR(255) NULL, CHANGE updated_at updated_at DATETIME NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE transaction');
        $this->addSql('ALTER TABLE utilisateur CHANGE image_name image_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }
}
