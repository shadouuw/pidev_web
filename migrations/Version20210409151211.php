<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210409151211 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE class');
        $this->addSql('ALTER TABLE concours CHANGE is_done is_done INT NOT NULL');
        $this->addSql('ALTER TABLE jeux CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE stages CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE test CHANGE id_test id_test INT AUTO_INCREMENT NOT NULL, CHANGE is_compt is_compt INT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE mot_de_passe mot_de_passe VARCHAR(15) DEFAULT NULL, CHANGE moyenne moyenne INT NOT NULL, CHANGE role role INT DEFAULT NULL, CHANGE metier metier VARCHAR(50) DEFAULT \'to_update\' NOT NULL, CHANGE status status INT NOT NULL, CHANGE img img VARCHAR(200) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE class (id_class INT AUTO_INCREMENT NOT NULL, nom_class VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, age INT NOT NULL, id_utilisateur INT DEFAULT NULL, PRIMARY KEY(id_class)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE concours CHANGE is_done is_done INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE jeux CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE stages CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE test CHANGE id_test id_test INT NOT NULL, CHANGE is_compt is_compt INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE mot_de_passe mot_de_passe VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE moyenne moyenne INT DEFAULT 0, CHANGE role role INT DEFAULT 0, CHANGE metier metier VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT \'to_update\' COLLATE `utf8mb4_general_ci`, CHANGE status status INT DEFAULT NULL, CHANGE img img VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`');
    }
}
