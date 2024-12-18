<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241021191503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, service_id INT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, prix DOUBLE PRECISION NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, INDEX IDX_351268BB19EB6921 (client_id), INDEX IDX_351268BBED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE abonnement_salle_de_sport (abonnement_id INT NOT NULL, salle_de_sport_id INT NOT NULL, INDEX IDX_B2CB8E08F1D74413 (abonnement_id), INDEX IDX_B2CB8E08264AE1D7 (salle_de_sport_id), PRIMARY KEY(abonnement_id, salle_de_sport_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, abonnement_id INT NOT NULL, client_id INT NOT NULL, montant_ht DOUBLE PRECISION NOT NULL, tva DOUBLE PRECISION NOT NULL, montant_total DOUBLE PRECISION NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_FE866410F1D74413 (abonnement_id), INDEX IDX_FE86641019EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salle_de_sport (id INT AUTO_INCREMENT NOT NULL, proprietaire_id INT NOT NULL, nom_salle VARCHAR(150) NOT NULL, adresse VARCHAR(255) NOT NULL, num_tel VARCHAR(15) NOT NULL, heure_ouverture TIME NOT NULL, heure_fermeture TIME NOT NULL, INDEX IDX_D533E78976C50E4A (proprietaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salle_de_sport_abonnement (salle_de_sport_id INT NOT NULL, abonnement_id INT NOT NULL, INDEX IDX_D68A01BE264AE1D7 (salle_de_sport_id), INDEX IDX_D68A01BEF1D74413 (abonnement_id), PRIMARY KEY(salle_de_sport_id, abonnement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, discriminator VARCHAR(255) NOT NULL, telephone VARCHAR(15) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BB19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BBED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE abonnement_salle_de_sport ADD CONSTRAINT FK_B2CB8E08F1D74413 FOREIGN KEY (abonnement_id) REFERENCES abonnement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE abonnement_salle_de_sport ADD CONSTRAINT FK_B2CB8E08264AE1D7 FOREIGN KEY (salle_de_sport_id) REFERENCES salle_de_sport (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410F1D74413 FOREIGN KEY (abonnement_id) REFERENCES abonnement (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641019EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE salle_de_sport ADD CONSTRAINT FK_D533E78976C50E4A FOREIGN KEY (proprietaire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE salle_de_sport_abonnement ADD CONSTRAINT FK_D68A01BE264AE1D7 FOREIGN KEY (salle_de_sport_id) REFERENCES salle_de_sport (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE salle_de_sport_abonnement ADD CONSTRAINT FK_D68A01BEF1D74413 FOREIGN KEY (abonnement_id) REFERENCES abonnement (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BB19EB6921');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BBED5CA9E6');
        $this->addSql('ALTER TABLE abonnement_salle_de_sport DROP FOREIGN KEY FK_B2CB8E08F1D74413');
        $this->addSql('ALTER TABLE abonnement_salle_de_sport DROP FOREIGN KEY FK_B2CB8E08264AE1D7');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410F1D74413');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641019EB6921');
        $this->addSql('ALTER TABLE salle_de_sport DROP FOREIGN KEY FK_D533E78976C50E4A');
        $this->addSql('ALTER TABLE salle_de_sport_abonnement DROP FOREIGN KEY FK_D68A01BE264AE1D7');
        $this->addSql('ALTER TABLE salle_de_sport_abonnement DROP FOREIGN KEY FK_D68A01BEF1D74413');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE abonnement_salle_de_sport');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE salle_de_sport');
        $this->addSql('DROP TABLE salle_de_sport_abonnement');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
