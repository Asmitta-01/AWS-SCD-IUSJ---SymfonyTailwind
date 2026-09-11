<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260911102723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, description LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_64C19C15E237E06 (name), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(30) DEFAULT NULL, company_name VARCHAR(180) DEFAULT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX idx_customer_status (status), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(180) NOT NULL, sku VARCHAR(80) NOT NULL, description LONGTEXT DEFAULT NULL, price NUMERIC(12, 2) NOT NULL, stock_quantity INT NOT NULL, minimum_stock INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, category_id INT NOT NULL, UNIQUE INDEX UNIQ_D34A04ADF9038C4 (sku), INDEX idx_product_stock (stock_quantity), INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE sale (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(40) NOT NULL, sale_date DATETIME NOT NULL, status VARCHAR(255) NOT NULL, subtotal NUMERIC(12, 2) NOT NULL, total NUMERIC(12, 2) NOT NULL, created_at DATETIME NOT NULL, customer_id INT NOT NULL, UNIQUE INDEX UNIQ_E54BC005AEA34913 (reference), INDEX idx_sale_date (sale_date), INDEX idx_sale_status (status), INDEX IDX_E54BC0059395C3F3 (customer_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE sale_item (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, unit_price NUMERIC(12, 2) NOT NULL, total NUMERIC(12, 2) NOT NULL, sale_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_A35551FB4A7E4868 (sale_id), INDEX IDX_A35551FB4584665A (product_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE transactions (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(40) NOT NULL, amount NUMERIC(12, 2) NOT NULL, payment_method VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, transaction_date DATETIME NOT NULL, sale_id INT NOT NULL, customer_id INT NOT NULL, UNIQUE INDEX UNIQ_EAA81A4CAEA34913 (reference), INDEX idx_transaction_date (transaction_date), INDEX idx_transaction_status (status), INDEX IDX_EAA81A4C4A7E4868 (sale_id), INDEX IDX_EAA81A4C9395C3F3 (customer_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC0059395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE sale_item ADD CONSTRAINT FK_A35551FB4A7E4868 FOREIGN KEY (sale_id) REFERENCES sale (id)');
        $this->addSql('ALTER TABLE sale_item ADD CONSTRAINT FK_A35551FB4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C4A7E4868 FOREIGN KEY (sale_id) REFERENCES sale (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE sale DROP FOREIGN KEY FK_E54BC0059395C3F3');
        $this->addSql('ALTER TABLE sale_item DROP FOREIGN KEY FK_A35551FB4A7E4868');
        $this->addSql('ALTER TABLE sale_item DROP FOREIGN KEY FK_A35551FB4584665A');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C4A7E4868');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C9395C3F3');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE sale');
        $this->addSql('DROP TABLE sale_item');
        $this->addSql('DROP TABLE transactions');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
