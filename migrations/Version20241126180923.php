<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241126180923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement_service (abonnement_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_81CF27BAF1D74413 (abonnement_id), INDEX IDX_81CF27BAED5CA9E6 (service_id), PRIMARY KEY(abonnement_id, service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abonnement_service ADD CONSTRAINT FK_81CF27BAF1D74413 FOREIGN KEY (abonnement_id) REFERENCES abonnement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE abonnement_service ADD CONSTRAINT FK_81CF27BAED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BBED5CA9E6');
        $this->addSql('DROP INDEX IDX_351268BBED5CA9E6 ON abonnement');
        $this->addSql('ALTER TABLE abonnement DROP service_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement_service DROP FOREIGN KEY FK_81CF27BAF1D74413');
        $this->addSql('ALTER TABLE abonnement_service DROP FOREIGN KEY FK_81CF27BAED5CA9E6');
        $this->addSql('DROP TABLE abonnement_service');
        $this->addSql('ALTER TABLE abonnement ADD service_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BBED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('CREATE INDEX IDX_351268BBED5CA9E6 ON abonnement (service_id)');
    }
}
