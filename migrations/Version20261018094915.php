<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261018094915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, email VARCHAR(180) NOT NULL, telephone VARCHAR(20) DEFAULT NULL, adresse VARCHAR(255) NOT NULL, code_postal VARCHAR(5) NOT NULL, ville VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_C7440455E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE devis (id INT AUTO_INCREMENT NOT NULL, date_validite DATE NOT NULL, document_id INT NOT NULL, UNIQUE INDEX UNIQ_8B27C52BC33F7837 (document_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL, numero VARCHAR(20) NOT NULL, date_emission DATE NOT NULL, statut VARCHAR(20) NOT NULL, client_id INT NOT NULL, UNIQUE INDEX UNIQ_D8698A76F55AE19E (numero), INDEX IDX_D8698A7619EB6921 (client_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, date_echeance DATE NOT NULL, document_id INT NOT NULL, devis_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_FE866410C33F7837 (document_id), UNIQUE INDEX UNIQ_FE86641041DEFADA (devis_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE ligne_document (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, prix_unitaire_ht NUMERIC(10, 2) NOT NULL, taux_tva NUMERIC(4, 2) NOT NULL, document_id INT NOT NULL, produit_id INT NOT NULL, remise_id INT DEFAULT NULL, UNIQUE INDEX unique_document_produit (document_id, produit_id), INDEX IDX_87F39B40C33F7837 (document_id), INDEX IDX_87F39B40F347EFB (produit_id), INDEX IDX_87F39B404E47A399 (remise_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, date_paiement DATE NOT NULL, montant NUMERIC(10, 2) NOT NULL, mode_paiement VARCHAR(30) NOT NULL, facture_id INT NOT NULL, INDEX IDX_B1DC7A1E7F2DEE08 (facture_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(150) NOT NULL, description LONGTEXT DEFAULT NULL, prix_ht NUMERIC(10, 2) NOT NULL, taux_tva NUMERIC(4, 2) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE remise (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, taux NUMERIC(4, 2) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BC33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A7619EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410C33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641041DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('ALTER TABLE ligne_document ADD CONSTRAINT FK_87F39B40C33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE ligne_document ADD CONSTRAINT FK_87F39B40F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE ligne_document ADD CONSTRAINT FK_87F39B404E47A399 FOREIGN KEY (remise_id) REFERENCES remise (id)');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52BC33F7837');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A7619EB6921');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410C33F7837');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641041DEFADA');
        $this->addSql('ALTER TABLE ligne_document DROP FOREIGN KEY FK_87F39B40C33F7837');
        $this->addSql('ALTER TABLE ligne_document DROP FOREIGN KEY FK_87F39B40F347EFB');
        $this->addSql('ALTER TABLE ligne_document DROP FOREIGN KEY FK_87F39B404E47A399');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E7F2DEE08');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE ligne_document');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE remise');
        $this->addSql('DROP TABLE utilisateur');
    }
}
