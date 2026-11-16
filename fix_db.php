<?php
require_once 'db.php';

$conn->query("DROP TABLE IF EXISTS vehicles");

$create = "CREATE TABLE vehicles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  brand VARCHAR(80) NOT NULL,
  type ENUM('Car', 'Bike', 'Scooter') NOT NULL,
  price INT NOT NULL,
  mileage DECIMAL(5,2) NOT NULL,
  maint INT NOT NULL DEFAULT 2000,
  ins INT NOT NULL DEFAULT 4000,
  image VARCHAR(500) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$insert = "INSERT INTO vehicles (name, brand, type, price, mileage, maint, ins, image) VALUES
('Hero Splendor Plus', 'Hero', 'Bike', 75000, 60.00, 1500, 2800, 'https://upload.wikimedia.org/wikipedia/commons/a/a0/Hero_Splendor_Plus_i3s_and_IBS_2018.jpg'),
('Honda Activa 6G', 'Honda', 'Scooter', 85000, 45.00, 1500, 3500, 'https://upload.wikimedia.org/wikipedia/commons/d/dd/Honda_Activa_6G.jpg'),
('Maruti Swift', 'Maruti Suzuki', 'Car', 115000, 22.00, 4500, 7500, 'https://upload.wikimedia.org/wikipedia/commons/b/b7/Suzuki_Swift_front.jpg'),
('Royal Enfield Hunter 350', 'Royal Enfield', 'Bike', 150000, 36.00, 3500, 5500, 'https://upload.wikimedia.org/wikipedia/commons/4/42/Hunter_350_side_view_India_Model.png'),
('Bajaj Pulsar NS160', 'Bajaj', 'Bike', 135000, 42.00, 2800, 4800, 'https://upload.wikimedia.org/wikipedia/commons/d/df/BAJAJ_Pulsar_NS160.jpg'),
('Tata Punch', 'Tata', 'Car', 620000, 20.00, 5500, 14000, 'https://upload.wikimedia.org/wikipedia/commons/0/04/Tata_punch.ev.jpg'),
('Hyundai Creta', 'Hyundai', 'Car', 1100000, 18.00, 7000, 22000, 'https://upload.wikimedia.org/wikipedia/commons/7/7f/2021_Hyundai_Creta_SX%28O%29_CRDi_%28India%29_front_view.jpg')";

if ($conn->query($create) && $conn->query($insert)) {
    $dbName = $conn->query("SELECT DATABASE()")->fetch_row()[0];
    echo "<h2>✅ Fixed database: <b>$dbName</b>!</h2>";
    echo "<p>All columns (mileage, maint, ins, image) and 7 vehicles are now ready.</p>";
    echo "<a href='admin/add_vehicle.php'>Go to Add Vehicle</a> | <a href='index.php'>Go to Home</a>";
} else {
    echo "Error: " . $conn->error;
}
?>