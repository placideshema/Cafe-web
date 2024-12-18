-- Create menu table
CREATE TABLE menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255)
);

-- Updated menu items including Chai, Black Tea, and African Coffee
INSERT INTO menu (name, description, price, image) VALUES 
('Classic Burger', 'Juicy beef patty with lettuce, tomato, and special sauce', 8.99, 'burger.jpg'),
('Veggie Wrap', 'Fresh vegetables and hummus in a spinach tortilla', 7.50, 'veggie_wrap.jpg'),
('Chicken Salad', 'Grilled chicken over mixed greens with house dressing', 9.25, 'chicken_salad.jpg'),
('Margherita Pizza', 'Thin crust pizza with fresh mozzarella and basil', 10.99, 'pizza.jpg'),
('Smoothie Bowl', 'Açai base with fresh fruits and granola', 6.75, 'smoothie_bowl.jpg'),
('Chai Latte', 'Spiced tea blend with steamed milk, warming and aromatic', 4.50, 'chai.jpg'),
('Black Tea', 'Classic English-style black tea, robust and full-bodied', 3.25, 'black_tea.jpg'),
('African Coffee', 'Single-origin coffee from Ethiopian highlands, rich and complex', 4.75, 'african_coffee.jpg');