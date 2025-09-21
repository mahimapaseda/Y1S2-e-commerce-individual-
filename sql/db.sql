-- Users table with index on email
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address VARCHAR(255),
    INDEX (email)  -- Index added for faster email lookups
);

-- Products table with index on name
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    INDEX (name)  -- Index added for faster product name lookups
);

-- Contact form submissions table
CREATE TABLE contact_form_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (email)  -- Index added for faster email lookups
);

-- Orders table with indexes on user_id and status
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    shipping_address VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    zip_code VARCHAR(20) NOT NULL,
    country VARCHAR(100) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'Pending',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (user_id),  -- Index added for faster user_id lookups
    INDEX (status)    -- Index added for faster status lookups
);

-- Order items table with indexes on order_id and product_id
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX (order_id),  -- Index added for faster order_id lookups
    INDEX (product_id) -- Index added for faster product_id lookups
);

-- Payments table with indexes on order_id
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    card_number VARCHAR(20) NOT NULL,
    expiry_date VARCHAR(10) NOT NULL,
    cvv VARCHAR(4) NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    INDEX (order_id)  -- Index added for faster order_id lookups
);


-- Insert sample data into users
INSERT INTO users (email, password, phone, address) VALUES
('admin@thelocalcart.com', '$2y$10$VPTZO8cc27cycIJyFVs7medUm0EoTKJ8q7pFuaOy99d6hW91bNYx6', '123-456-7890', '123 Admin St, Admin City, AC 12345'),
('user1@example.com', '$2y$10$eWxYfN0t.lIc2pA6/y5QheV5zdKZjd/7DbdI9Kz7S7uwiF1WxO5D6', '234-567-8901', '456 User Rd, User Town, UT 67890');

-- Insert sample data into products
INSERT INTO products (name, description, price, image) VALUES
('Product 1', 'Description for Product 1', 19.99, 'product1.jpg'),
('Product 2', 'Description for Product 2', 29.99, 'product2.jpg'),
('Product 3', 'Description for Product 3', 39.99, 'product3.jpg');

-- Insert sample data into contact_form_submissions
INSERT INTO contact_form_submissions (name, email, subject, message) VALUES
('John Doe', 'john.doe@example.com', 'Inquiry', 'This is a test message.'),
('Jane Smith', 'jane.smith@example.com', 'Support', 'Please help with my account.');

-- Insert sample data into orders
INSERT INTO orders (user_id, total_amount, shipping_address, city, zip_code, country, order_date, status) VALUES
(2, 59.97, '789 User Ave', 'User City', '54321', 'US', '2024-09-01 10:00:00', 'Completed'),
(2, 29.99, '789 User Ave', 'User City', '54321', 'US', '2024-09-02 11:30:00', 'Pending');

-- Insert sample data into order_items
INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
(1, 1, 1, 19.99),
(1, 2, 2, 29.99),
(2, 3, 1, 39.99);

-- Insert sample data into payments
INSERT INTO payments (order_id, payment_method, card_number, expiry_date, cvv, payment_date) VALUES
(1, 'Credit Card', '4111111111111111', '12/25', '123', '2024-09-01 10:05:00'),
(2, 'Credit Card', '4111111111111111', '12/25', '123', '2024-09-02 11:35:00');


SELECT * FROM orders WHERE user_id = 2;  -- Replace 2 with the ID of the logged-in user.
