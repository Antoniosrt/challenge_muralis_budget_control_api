CREATE DATABASE muralis_challenge;
USE muralis_challenge;

CREATE TABLE address (
    id INT AUTO_INCREMENT PRIMARY KEY,
    state VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    neighborhood VARCHAR(255),
    street VARCHAR(255) NOT NULL,
    number VARCHAR(255) NOT NULL,
    complement VARCHAR(255)
);

CREATE TABLE payment_type (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(255) NOT NULL
);

CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description VARCHAR(255)
);

CREATE TABLE expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    amount DECIMAL(10,2) NOT NULL,
    purchase_date DATETIME NOT NULL,
    description TEXT,
    payment_type_id INT NOT NULL,
    category_id INT NOT NULL,
    address_id INT NOT NULL,
    FOREIGN KEY (payment_type_id) REFERENCES payment_type(id),
    FOREIGN KEY (category_id) REFERENCES category(id),
    FOREIGN KEY (address_id) REFERENCES address(id)
);
INSERT INTO payment_type (type) 
VALUES 
('Crédito'),
('Pix'),
('Débito'),
('Dinheiro');
