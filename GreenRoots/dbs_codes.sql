CREATE TABLE products (
    product_id VARCHAR(10) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    details TEXT,
    quantity VARCHAR(50),
    price VARCHAR(50),
    season VARCHAR(50),
    icon VARCHAR(10),
    status ENUM('active', 'low', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE locations (
    location_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(10),
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    location_name VARCHAR(100),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

CREATE TABLE devices (
    device_id VARCHAR(10) PRIMARY KEY,
    product_id VARCHAR(10),
    status ENUM('active', 'inactive', 'maintenance') DEFAULT 'active',
    last_ping TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

INSERT INTO products (product_id, name, details, quantity, price, season, icon) VALUES
('RICE001', 'Basmati Rice', 'Premium Basmati Rice from Rajshahi Division', '500 tons', '৳65/kg', 'Year-round', '🌾'),
('JUTE002', 'Raw Jute', 'High-quality Jute from Khulna Division', '300 tons', '৳45/kg', 'July-October', '🌿'),
('TEA003', 'Green Tea', 'Organic Green Tea from Sylhet Division', '200 tons', '৳400/kg', 'March-November', '🍃');

INSERT INTO locations (product_id, latitude, longitude, location_name) VALUES
('RICE001', 24.3745, 88.6042, 'Rajshahi Division'),
('JUTE002', 23.0000, 89.5403, 'Khulna Division'),
('TEA003', 24.8949, 91.8687, 'Sylhet Division');