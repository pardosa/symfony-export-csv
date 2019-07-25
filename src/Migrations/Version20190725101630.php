<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190725101630 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE address (address_id INT AUTO_INCREMENT NOT NULL, street VARCHAR(140) NOT NULL, postcode VARCHAR(140) NOT NULL, suburb VARCHAR(140) NOT NULL, state VARCHAR(140) NOT NULL, PRIMARY KEY(address_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brand (brand_id INT AUTO_INCREMENT NOT NULL, name VARCHAR(140) NOT NULL, PRIMARY KEY(brand_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, name VARCHAR(140) NOT NULL, INDEX IDX_64C19C14584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (customer_id INT NOT NULL, address_id INT DEFAULT NULL, first_name VARCHAR(140) NOT NULL, last_name VARCHAR(140) NOT NULL, email VARCHAR(140) NOT NULL, phone VARCHAR(140) NOT NULL, INDEX IDX_81398E09F5B7AF75 (address_id), PRIMARY KEY(customer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discount (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, type VARCHAR(140) NOT NULL, value NUMERIC(5, 2) NOT NULL, priority INT NOT NULL, INDEX IDX_E1E0B40E8D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (item_id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, order_id INT DEFAULT NULL, quantity INT NOT NULL, unit_price NUMERIC(10, 0) NOT NULL, INDEX IDX_1F1B251E4584665A (product_id), INDEX IDX_1F1B251E8D9F6D38 (order_id), PRIMARY KEY(item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE t_order (order_id INT NOT NULL, customer_id INT DEFAULT NULL, order_date VARCHAR(140) NOT NULL, shipping_price NUMERIC(10, 0) NOT NULL, UNIQUE INDEX UNIQ_4B98F5E19395C3F3 (customer_id), PRIMARY KEY(order_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (product_id INT NOT NULL, brand_id INT DEFAULT NULL, title VARCHAR(140) NOT NULL, image VARCHAR(140) NOT NULL, thumbnail VARCHAR(140) NOT NULL, url VARCHAR(140) NOT NULL, created_at VARCHAR(140) NOT NULL, subtitle VARCHAR(140) DEFAULT NULL, upc VARCHAR(140) DEFAULT NULL, gtin14 INT DEFAULT NULL, UNIQUE INDEX UNIQ_D34A04AD44F5D008 (brand_id), PRIMARY KEY(product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C14584665A FOREIGN KEY (product_id) REFERENCES product (product_id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (address_id)');
        $this->addSql('ALTER TABLE discount ADD CONSTRAINT FK_E1E0B40E8D9F6D38 FOREIGN KEY (order_id) REFERENCES t_order (order_id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E4584665A FOREIGN KEY (product_id) REFERENCES product (product_id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E8D9F6D38 FOREIGN KEY (order_id) REFERENCES t_order (order_id)');
        $this->addSql('ALTER TABLE t_order ADD CONSTRAINT FK_4B98F5E19395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (customer_id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (brand_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09F5B7AF75');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD44F5D008');
        $this->addSql('ALTER TABLE t_order DROP FOREIGN KEY FK_4B98F5E19395C3F3');
        $this->addSql('ALTER TABLE discount DROP FOREIGN KEY FK_E1E0B40E8D9F6D38');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E8D9F6D38');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C14584665A');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E4584665A');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE discount');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE t_order');
        $this->addSql('DROP TABLE product');
    }
}
