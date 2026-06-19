CREATE DATABASE IF NOT EXISTS fooddb;
USE fooddb;

CREATE TABLE IF NOT EXISTS restaurants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    cuisine VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT,
    opening_hours VARCHAR(255),
    image_url VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
);

INSERT INTO restaurants (name, cuisine, location, description, opening_hours, image_url) VALUES
('The Italian Place', 'Italian', '123 Pasta St', 'Authentic Italian pasta and pizza.', '10:00 AM - 10:00 PM', 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=500&h=300&fit=crop'),
('Sushi Master', 'Japanese', '456 Ocean Ave', 'Fresh sushi and sashimi prepared daily.', '11:00 AM - 9:00 PM', 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=500&h=300&fit=crop'),
('Burger Joint', 'American', '789 Main St', 'Juicy burgers with crispy fries.', '11:00 AM - 11:00 PM', 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=500&h=300&fit=crop'),
('Spicy Curry House', 'Indian', '321 Spice Ln', 'Traditional Indian curries with naan bread.', '12:00 PM - 10:00 PM', 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=500&h=300&fit=crop'),
('Green Leaf Salads', 'Healthy', '654 Fit Blvd', 'Fresh, organic salads and bowls.', '8:00 AM - 8:00 PM', 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500&h=300&fit=crop');
