CREATE DATABASE  restaurant;
USE restaurant;



CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE food_menu (
    id_Srilanka INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL
);
INSERT INTO food_menu (id_Srilanka, name, description, price, image) VALUES
(1, 'Sri Lankan Curry', 'A traditional curry with spices and coconut milk.', 1375.00, 'images/download (1).jpeg'),
(2, 'Hoppers', 'Bowl-shaped pancakes served with sambal.', 200.00, 'images/Sri+Lankan+hoppers.jpg'),
(3, 'Kottu Roti', 'Stir-fried flatbread with vegetables and meat.', 1500.00, 'images/istockphoto-1892520856-612x612.jpg'),
(4, 'Pol Sambol', 'Coconut mix with chili, onions, and lime.', 100.00, 'images/img86979.whqc_768x512q80.jpg'),
(5, 'Lamprais', 'Rice and curry wrapped in banana leaf.', 1000.00, 'images/Lamprais-fish.jpg');


CREATE TABLE chinese_cuisines (
    id_chinese INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL
);
INSERT INTO chinese_cuisines (id_chinese, name, description, price, image) VALUES
(100, 'Kung Pao Chicken', 'Spicy chicken with peanuts and vegetables.', 1200.00, 'images/Kung-Pao-Chicken-Reshoot-11_PS.jpg'),
(102, 'Spring Rolls', 'Fried rolls with vegetables and meat.', 2200.00, 'images/Crispy-Vegetable-Spring-Rolls-Recipe.jpg'),
(103, 'Fried Rice', 'Rice with eggs, vegetables, and meat.', 3000.00, 'images/Indo-Chinese-Fried-Rice-500x500.jpg'),
(104, 'Dim Sum', 'Steamed dumplings with meat or vegetables.', 4500.00, 'images/prawnporkandvegetabl_88714_16x9.jpg');


CREATE TABLE italy_cuisines (
    id_italy INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL
);
INSERT INTO italy_cuisines (id_italy, name, description, price, image) VALUES
(200, 'Margherita Pizza', 'Pizza with tomatoes, mozzarella, and basil.', 4900.00, 'images/pizzza.jpeg'),
(201, 'Lasagna', 'Pasta with meat sauce and cheese layers.', 5700.00, 'images/360_F_321330477_OZ99ygXNDUmwp2r6xafoHufhYuydMRFa.jpg'),
(202, 'Spaghetti Carbonara', 'Pasta with eggs, pancetta, and cheese.', 4500.00, 'images/carbonara-horizontal-square640-v2.jpg'),
(203, 'Tiramisu', 'Coffee-flavored dessert with mascarpone.', 2500.00, 'images/Tiramisu.jpg'),
(204, 'Risotto', 'Creamy rice with mushrooms and cheese.', 4600.00, 'images/10Risotto-gcmz-superJumbo.jpg');



CREATE TABLE  reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    reservation_date DATE NOT NULL,
    reservation_time TIME NOT NULL,
    guests INT NOT NULL,
    special_requests TEXT,
    status ENUM('Pending', 'Confirmed', 'Modified', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE  orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    food_item VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    special_requests TEXT,
    status ENUM('Pending', 'Confirmed', 'Modified', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
