<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250918141129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE infos_jeu (id INT AUTO_INCREMENT NOT NULL, liste_vocabulaire_id INT NOT NULL, utilisateur_id INT NOT NULL, date_dernier_jeu DATE DEFAULT NULL, best_scores JSON NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_92D584C4E279D0F6 (liste_vocabulaire_id), INDEX IDX_92D584C4FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE langue (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, maj_importante TINYINT(1) NOT NULL, caracteres_speciaux JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liste_vocabulaire (id INT AUTO_INCREMENT NOT NULL, createur_id INT NOT NULL, titre VARCHAR(255) NOT NULL, date_derniere_modif DATE NOT NULL, public_statut TINYINT(1) NOT NULL, INDEX IDX_C264029173A201E5 (createur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liste_vocabulaire_langue (liste_vocabulaire_id INT NOT NULL, langue_id INT NOT NULL, INDEX IDX_DEE5B98EE279D0F6 (liste_vocabulaire_id), INDEX IDX_DEE5B98E2AADBACD (langue_id), PRIMARY KEY(liste_vocabulaire_id, langue_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, liste_vocabulaire_id INT DEFAULT NULL, utilisateur_id INT NOT NULL, montant_note INT DEFAULT NULL, INDEX IDX_CFBDFA14E279D0F6 (liste_vocabulaire_id), INDEX IDX_CFBDFA14FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE traduction (id INT AUTO_INCREMENT NOT NULL, liste_vocabulaire_id INT NOT NULL, mot_langue1 VARCHAR(255) NOT NULL, mot_langue2 VARCHAR(255) NOT NULL, INDEX IDX_CF8C03A8E279D0F6 (liste_vocabulaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur_liste_vocabulaire (utilisateur_id INT NOT NULL, liste_vocabulaire_id INT NOT NULL, INDEX IDX_E0DF6725FB88E14F (utilisateur_id), INDEX IDX_E0DF6725E279D0F6 (liste_vocabulaire_id), PRIMARY KEY(utilisateur_id, liste_vocabulaire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE infos_jeu ADD CONSTRAINT FK_92D584C4E279D0F6 FOREIGN KEY (liste_vocabulaire_id) REFERENCES liste_vocabulaire (id)');
        $this->addSql('ALTER TABLE infos_jeu ADD CONSTRAINT FK_92D584C4FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE liste_vocabulaire ADD CONSTRAINT FK_C264029173A201E5 FOREIGN KEY (createur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE liste_vocabulaire_langue ADD CONSTRAINT FK_DEE5B98EE279D0F6 FOREIGN KEY (liste_vocabulaire_id) REFERENCES liste_vocabulaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE liste_vocabulaire_langue ADD CONSTRAINT FK_DEE5B98E2AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14E279D0F6 FOREIGN KEY (liste_vocabulaire_id) REFERENCES liste_vocabulaire (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE traduction ADD CONSTRAINT FK_CF8C03A8E279D0F6 FOREIGN KEY (liste_vocabulaire_id) REFERENCES liste_vocabulaire (id)');
        $this->addSql('ALTER TABLE utilisateur_liste_vocabulaire ADD CONSTRAINT FK_E0DF6725FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_liste_vocabulaire ADD CONSTRAINT FK_E0DF6725E279D0F6 FOREIGN KEY (liste_vocabulaire_id) REFERENCES liste_vocabulaire (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE infos_jeu DROP FOREIGN KEY FK_92D584C4E279D0F6');
        $this->addSql('ALTER TABLE infos_jeu DROP FOREIGN KEY FK_92D584C4FB88E14F');
        $this->addSql('ALTER TABLE liste_vocabulaire DROP FOREIGN KEY FK_C264029173A201E5');
        $this->addSql('ALTER TABLE liste_vocabulaire_langue DROP FOREIGN KEY FK_DEE5B98EE279D0F6');
        $this->addSql('ALTER TABLE liste_vocabulaire_langue DROP FOREIGN KEY FK_DEE5B98E2AADBACD');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14E279D0F6');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14FB88E14F');
        $this->addSql('ALTER TABLE traduction DROP FOREIGN KEY FK_CF8C03A8E279D0F6');
        $this->addSql('ALTER TABLE utilisateur_liste_vocabulaire DROP FOREIGN KEY FK_E0DF6725FB88E14F');
        $this->addSql('ALTER TABLE utilisateur_liste_vocabulaire DROP FOREIGN KEY FK_E0DF6725E279D0F6');
        $this->addSql('DROP TABLE infos_jeu');
        $this->addSql('DROP TABLE langue');
        $this->addSql('DROP TABLE liste_vocabulaire');
        $this->addSql('DROP TABLE liste_vocabulaire_langue');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE traduction');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE utilisateur_liste_vocabulaire');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
