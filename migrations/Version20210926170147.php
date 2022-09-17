<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210926170147 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shopping_cart (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, coocie_id VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, model_size VARCHAR(255) NOT NULL, quantity INT NOT NULL, product_id INT NOT NULL, INDEX IDX_72AAD4F6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shopping_cart_product (shopping_cart_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_FA1F5E6C45F80CD (shopping_cart_id), INDEX IDX_FA1F5E6C4584665A (product_id), PRIMARY KEY(shopping_cart_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE shopping_cart_product ADD CONSTRAINT FK_FA1F5E6C45F80CD FOREIGN KEY (shopping_cart_id) REFERENCES shopping_cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shopping_cart_product ADD CONSTRAINT FK_FA1F5E6C4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C14584665A');
        $this->addSql('ALTER TABLE category CHANGE sex sex VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX fk_64c19c14584665a ON category');
        $this->addSql('CREATE INDEX IDX_64C19C14584665A ON category (product_id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C14584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE `order` ADD way_of_delivery VARCHAR(255) DEFAULT NULL, ADD deliver_number VARCHAR(255) DEFAULT NULL, ADD deliver VARCHAR(255) DEFAULT NULL, ADD zip_code VARCHAR(255) NOT NULL, ADD order_json VARCHAR(255) NOT NULL, ADD status VARCHAR(255) NOT NULL, DROP new_or_archived, DROP confirmed, DROP coocie_id, DROP model_size, CHANGE office office VARCHAR(255) NOT NULL, CHANGE order_on order_on DATETIME NOT NULL, CHANGE price price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE product ADD model VARCHAR(255) NOT NULL, ADD name VARCHAR(255) DEFAULT NULL, ADD color_code VARCHAR(255) DEFAULT NULL, ADD statuses VARCHAR(255) DEFAULT NULL, ADD pictures VARCHAR(255) DEFAULT NULL, DROP model_number, DROP bought_counter, DROP photo_count, CHANGE color color VARCHAR(255) DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE is_deleted is_deleted TINYINT(1) DEFAULT NULL, CHANGE is_in_promotion is_in_promotion TINYINT(1) DEFAULT NULL, CHANGE is_new is_new TINYINT(1) DEFAULT NULL, CHANGE outside outside VARCHAR(255) DEFAULT NULL, CHANGE avg_rating avg_rating INT DEFAULT NULL, CHANGE inside inside VARCHAR(255) DEFAULT NULL, CHANGE discount_price discount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE review ADD name VARCHAR(255) DEFAULT NULL, ADD email VARCHAR(255) DEFAULT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE product_id product_id INT DEFAULT NULL, CHANGE rating rating INT NOT NULL, CHANGE published_on published_on DATETIME DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_794381C6A76ED395 ON review (user_id)');
        $this->addSql('CREATE INDEX IDX_794381C64584665A ON review (product_id)');
        $this->addSql('ALTER TABLE user DROP registered_on');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shopping_cart_product DROP FOREIGN KEY FK_FA1F5E6C45F80CD');
        $this->addSql('DROP TABLE shopping_cart');
        $this->addSql('DROP TABLE shopping_cart_product');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C14584665A');
        $this->addSql('ALTER TABLE category CHANGE sex sex VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX idx_64c19c14584665a ON category');
        $this->addSql('CREATE INDEX FK_64C19C14584665A ON category (product_id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C14584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE `order` ADD new_or_archived TINYINT(1) NOT NULL, ADD confirmed TINYINT(1) NOT NULL, ADD coocie_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD model_size VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP way_of_delivery, DROP deliver_number, DROP deliver, DROP zip_code, DROP order_json, DROP status, CHANGE office office VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE order_on order_on DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE price price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD bought_counter VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD photo_count INT NOT NULL, DROP name, DROP color_code, DROP statuses, DROP pictures, CHANGE color color VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE title title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE is_deleted is_deleted TINYINT(1) NOT NULL, CHANGE is_in_promotion is_in_promotion TINYINT(1) NOT NULL, CHANGE is_new is_new TINYINT(1) NOT NULL, CHANGE avg_rating avg_rating TINYINT(1) DEFAULT \'0\', CHANGE inside inside VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE outside outside VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE model model_number VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE discount discount_price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C64584665A');
        $this->addSql('DROP INDEX IDX_794381C6A76ED395 ON review');
        $this->addSql('DROP INDEX IDX_794381C64584665A ON review');
        $this->addSql('ALTER TABLE review DROP name, DROP email, CHANGE id id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\', CHANGE user_id user_id INT NOT NULL, CHANGE product_id product_id INT NOT NULL, CHANGE rating rating VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE published_on published_on INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD registered_on DATETIME DEFAULT CURRENT_TIMESTAMP');
    }
}
