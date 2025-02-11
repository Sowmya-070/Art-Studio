-- Drop database if exists
DROP DATABASE IF EXISTS online_art_studio;
CREATE DATABASE online_art_studio;
USE online_art_studio;

-- Users Table (Artists, Buyers, Admins)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('artist', 'buyer', 'admin') NOT NULL DEFAULT 'buyer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Artworks Table
CREATE TABLE artworks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    artist_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (artist_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Wishlist Table
CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    artwork_id INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (artwork_id) REFERENCES artworks(id) ON DELETE CASCADE
);

-- Reported Artworks Table
CREATE TABLE reported_artworks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    artwork_id INT NOT NULL,
    user_id INT NOT NULL,
    reason TEXT NOT NULL,
    reported_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (artwork_id) REFERENCES artworks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Orders Table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    artwork_id INT NOT NULL,
    status ENUM('pending', 'completed', 'canceled') NOT NULL DEFAULT 'pending',
    ordered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (artwork_id) REFERENCES artworks(id) ON DELETE CASCADE
);

-- Insert Admin User
INSERT INTO users (name, email, password, role) 
VALUES ('Admin', 'admin@example.com', '$2y$10$X9lZWmGZ5HvuWcSt/FWUpOTiJ..7Tjq4p7s2wQ94ZeAfmOd1qGdKS', 'admin');

-- Insert Sample Artists
INSERT INTO users (name, email, password, role) 
VALUES 
('Artist One', 'artist1@example.com', '$2y$10$X9lZWmGZ5HvuWcSt/FWUpOTiJ..7Tjq4p7s2wQ94ZeAfmOd1qGdKS', 'artist'),
('Artist Two', 'artist2@example.com', '$2y$10$X9lZWmGZ5HvuWcSt/FWUpOTiJ..7Tjq4p7s2wQ94ZeAfmOd1qGdKS', 'artist');

-- Insert Sample Buyers
INSERT INTO users (name, email, password, role) 
VALUES 
('Buyer One', 'buyer1@example.com', '$2y$10$X9lZWmGZ5HvuWcSt/FWUpOTiJ..7Tjq4p7s2wQ94ZeAfmOd1qGdKS', 'buyer'),
('Buyer Two', 'buyer2@example.com', '$2y$10$X9lZWmGZ5HvuWcSt/FWUpOTiJ..7Tjq4p7s2wQ94ZeAfmOd1qGdKS', 'buyer');

-- Insert Sample Artworks
INSERT INTO artworks (title, description, image, price, artist_id)
VALUES 
('Sunset Painting', 'A beautiful sunset artwork.', 'sunset.jpg', 50.00, 2),
('Abstract Art', 'A creative abstract painting.', 'abstract.jpg', 75.00, 3);

-- Insert Sample Wishlist
INSERT INTO wishlist (user_id, artwork_id)
VALUES 
(4, 1),
(5, 2);

-- Insert Sample Reports
INSERT INTO reported_artworks (artwork_id, user_id, reason)
VALUES 
(1, 4, 'Inappropriate content');

-- Insert Sample Orders
INSERT INTO orders (buyer_id, artwork_id, status)
VALUES 
(4, 1, 'completed'),
(5, 2, 'pending');

-- CRUD Operations

-- 1. Retrieve all users
SELECT * FROM users;

-- 2. Retrieve all artworks
SELECT artworks.*, users.name AS artist_name FROM artworks JOIN users ON artworks.artist_id = users.id;

-- 3. Retrieve wishlist for a user
SELECT artworks.* FROM wishlist 
JOIN artworks ON wishlist.artwork_id = artworks.id 
WHERE wishlist.user_id = 4;

-- 4. Retrieve reported artworks
SELECT reported_artworks.*, artworks.title, users.name AS reported_by 
FROM reported_artworks 
JOIN artworks ON reported_artworks.artwork_id = artworks.id
JOIN users ON reported_artworks.user_id = users.id;

-- 5. Retrieve orders for a buyer
SELECT orders.*, artworks.title, users.name AS artist_name FROM orders 
JOIN artworks ON orders.artwork_id = artworks.id
JOIN users ON artworks.artist_id = users.id
WHERE orders.buyer_id = 4;

-- 6. Delete a user
DELETE FROM users WHERE id = 3;

-- 7. Delete an artwork
DELETE FROM artworks WHERE id = 2;

-- 8. Delete a wishlist item
DELETE FROM wishlist WHERE user_id = 4 AND artwork_id = 1;

-- 9. Delete a reported artwork
DELETE FROM reported_artworks WHERE artwork_id = 1;

-- 10. Update order status
UPDATE orders SET status = 'completed' WHERE id = 1;
