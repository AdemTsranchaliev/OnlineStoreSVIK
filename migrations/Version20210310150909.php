<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210310150909 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD avg_rating TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE review ADD user_id INT DEFAULT NULL, ADD product_id INT DEFAULT NULL, ADD comment VARCHAR(255) NOT NULL, ADD rating VARCHAR(255) NOT NULL, CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C64584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_794381C6A76ED395 ON review (user_id)');
        $this->addSql('CREATE INDEX IDX_794381C64584665A ON review (product_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP avg_rating');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C64584665A');
        $this->addSql('DROP INDEX IDX_794381C6A76ED395 ON review');
        $this->addSql('DROP INDEX IDX_794381C64584665A ON review');
        $this->addSql('ALTER TABLE review DROP user_id, DROP product_id, DROP comment, DROP rating, CHANGE id id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
    }
}
