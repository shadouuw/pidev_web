<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210427230751 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY chat_ibfk_1');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY chat_ibfk_2');
        $this->addSql('ALTER TABLE chat CHANGE user_emut user_emut INT DEFAULT NULL, CHANGE user_receiv user_receiv INT DEFAULT NULL, CHANGE vu vu TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA1212F401 FOREIGN KEY (user_emut) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAA97EBBA8 FOREIGN KEY (user_receiv) REFERENCES user (id)');
        $this->addSql('ALTER TABLE classe CHANGE nom_class nom_class VARCHAR(255) NOT NULL, CHANGE id_utilisateur id_utilisateur INT NOT NULL');
        $this->addSql('ALTER TABLE concours CHANGE img img VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE jeux ADD CONSTRAINT FK_3755B50DFDCA8C9C FOREIGN KEY (cours) REFERENCES cours (id_cours)');
        $this->addSql('CREATE INDEX IDX_3755B50DFDCA8C9C ON jeux (cours)');
        $this->addSql('ALTER TABLE stages CHANGE source source VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE stages ADD CONSTRAINT FK_2FA26A6482E48DB5 FOREIGN KEY (jeu) REFERENCES jeux (id)');
        $this->addSql('CREATE INDEX IDX_2FA26A6482E48DB5 ON stages (jeu)');
        $this->addSql('ALTER TABLE user DROP classe_id, CHANGE mot_de_passe mot_de_passe VARCHAR(255) NOT NULL, CHANGE moyenne moyenne INT NOT NULL, CHANGE metier metier VARCHAR(50) DEFAULT \'to_update\' NOT NULL, CHANGE status status INT NOT NULL, CHANGE token token VARCHAR(50) DEFAULT \'to_update\' NOT NULL, CHANGE adresse adresse VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA1212F401');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAA97EBBA8');
        $this->addSql('ALTER TABLE chat CHANGE user_emut user_emut INT NOT NULL, CHANGE user_receiv user_receiv INT NOT NULL, CHANGE vu vu TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT chat_ibfk_1 FOREIGN KEY (user_emut) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT chat_ibfk_2 FOREIGN KEY (user_receiv) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classe CHANGE nom_class nom_class VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE id_utilisateur id_utilisateur INT DEFAULT NULL');
        $this->addSql('ALTER TABLE concours CHANGE img img VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE jeux DROP FOREIGN KEY FK_3755B50DFDCA8C9C');
        $this->addSql('DROP INDEX IDX_3755B50DFDCA8C9C ON jeux');
        $this->addSql('ALTER TABLE stages DROP FOREIGN KEY FK_2FA26A6482E48DB5');
        $this->addSql('DROP INDEX IDX_2FA26A6482E48DB5 ON stages');
        $this->addSql('ALTER TABLE stages CHANGE source source VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE user ADD classe_id INT DEFAULT NULL, CHANGE mot_de_passe mot_de_passe VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE moyenne moyenne INT DEFAULT NULL, CHANGE metier metier VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'to_update\' COLLATE `utf8mb4_general_ci`, CHANGE token token VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE status status INT DEFAULT NULL, CHANGE adresse adresse VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`');
    }
}
