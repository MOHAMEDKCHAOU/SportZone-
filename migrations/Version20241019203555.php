<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241019203555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Check and create abonnement table if not exists.';
    }

    public function up(Schema $schema): void
    {
        // Check if the 'abonnement' table exists
        $tableExists = $this->connection->executeQuery('SHOW TABLES LIKE "abonnement"')->fetchOne();

        // If the table does not exist, create it
        if (!$tableExists) {
            $this->addSql('CREATE TABLE abonnement (
                id INT AUTO_INCREMENT NOT NULL,
                nom VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                prix FLOAT NOT NULL,
                date_debut DATETIME NOT NULL,
                date_fin DATETIME NOT NULL,
                client_id INT NOT NULL,
                service_id INT NOT NULL,
                PRIMARY KEY(id),
                CONSTRAINT FK_CLIENT FOREIGN KEY (client_id) REFERENCES client(id),
                CONSTRAINT FK_SERVICE FOREIGN KEY (service_id) REFERENCES service(id)
            ) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        }

        // Other alterations or SQL statements you might want to run
        $this->addSql('ALTER TABLE user CHANGE telephone telephone VARCHAR(15) DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // Drop the table if it exists in the down migration
        $this->addSql('DROP TABLE IF EXISTS abonnement');

        // Revert the other changes made in up()
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user CHANGE telephone telephone VARCHAR(15) DEFAULT \'NULL\'');
    }
}
