<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220420121216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job_title (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(75) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE job_ttile');
        $this->addSql('ALTER TABLE application ADD applicant_id INT DEFAULT NULL, ADD offer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC197139001 FOREIGN KEY (applicant_id) REFERENCES applicant (id)');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC153C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC197139001 ON application (applicant_id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC153C674EE ON application (offer_id)');
        $this->addSql('ALTER TABLE company ADD workforce_id INT NOT NULL');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FA25BA942 FOREIGN KEY (workforce_id) REFERENCES workforce (id)');
        $this->addSql('CREATE INDEX IDX_4FBF094FA25BA942 ON company (workforce_id)');
        $this->addSql('ALTER TABLE offer ADD office_id INT DEFAULT NULL, ADD status_id INT NOT NULL, ADD level_of_study_id INT NOT NULL, ADD experience_id INT NOT NULL, ADD job_title_id INT NOT NULL, ADD contract_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EFFA0C224 FOREIGN KEY (office_id) REFERENCES office (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EAAD8935F FOREIGN KEY (level_of_study_id) REFERENCES level_of_study (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E46E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E6DD822C6 FOREIGN KEY (job_title_id) REFERENCES job_title (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873ECD1DF15B FOREIGN KEY (contract_type_id) REFERENCES contract_type (id)');
        $this->addSql('CREATE INDEX IDX_29D6873EFFA0C224 ON offer (office_id)');
        $this->addSql('CREATE INDEX IDX_29D6873E6BF700BD ON offer (status_id)');
        $this->addSql('CREATE INDEX IDX_29D6873EAAD8935F ON offer (level_of_study_id)');
        $this->addSql('CREATE INDEX IDX_29D6873E46E90E27 ON offer (experience_id)');
        $this->addSql('CREATE INDEX IDX_29D6873E6DD822C6 ON offer (job_title_id)');
        $this->addSql('CREATE INDEX IDX_29D6873ECD1DF15B ON offer (contract_type_id)');
        $this->addSql('ALTER TABLE office ADD company_id INT NOT NULL');
        $this->addSql('ALTER TABLE office ADD CONSTRAINT FK_74516B02979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_74516B02979B1AD6 ON office (company_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E6DD822C6');
        $this->addSql('CREATE TABLE job_ttile (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(75) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE job_title');
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC197139001');
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC153C674EE');
        $this->addSql('DROP INDEX IDX_A45BDDC197139001 ON application');
        $this->addSql('DROP INDEX IDX_A45BDDC153C674EE ON application');
        $this->addSql('ALTER TABLE application DROP applicant_id, DROP offer_id');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FA25BA942');
        $this->addSql('DROP INDEX IDX_4FBF094FA25BA942 ON company');
        $this->addSql('ALTER TABLE company DROP workforce_id');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EFFA0C224');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E6BF700BD');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EAAD8935F');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E46E90E27');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873ECD1DF15B');
        $this->addSql('DROP INDEX IDX_29D6873EFFA0C224 ON offer');
        $this->addSql('DROP INDEX IDX_29D6873E6BF700BD ON offer');
        $this->addSql('DROP INDEX IDX_29D6873EAAD8935F ON offer');
        $this->addSql('DROP INDEX IDX_29D6873E46E90E27 ON offer');
        $this->addSql('DROP INDEX IDX_29D6873E6DD822C6 ON offer');
        $this->addSql('DROP INDEX IDX_29D6873ECD1DF15B ON offer');
        $this->addSql('ALTER TABLE offer DROP office_id, DROP status_id, DROP level_of_study_id, DROP experience_id, DROP job_title_id, DROP contract_type_id');
        $this->addSql('ALTER TABLE office DROP FOREIGN KEY FK_74516B02979B1AD6');
        $this->addSql('DROP INDEX IDX_74516B02979B1AD6 ON office');
        $this->addSql('ALTER TABLE office DROP company_id');
    }
}
