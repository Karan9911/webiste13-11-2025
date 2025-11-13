<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

echo "<h2>Running Session Migration...</h2>";

try {
    $db = Database::getInstance()->getConnection();

    echo "<p>Creating spa_sessions table...</p>";
    $sql1 = "CREATE TABLE IF NOT EXISTS spa_sessions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        image VARCHAR(255),
        therapy_time VARCHAR(50) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        description TEXT,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $db->exec($sql1);
    echo "<p style='color: green;'>✓ spa_sessions table created successfully</p>";

    echo "<p>Creating session_bookings table...</p>";
    $sql2 = "CREATE TABLE IF NOT EXISTS session_bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        session_id INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        spa_address TEXT NOT NULL,
        message TEXT,
        status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (session_id) REFERENCES spa_sessions(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $db->exec($sql2);
    echo "<p style='color: green;'>✓ session_bookings table created successfully</p>";

    echo "<p>Inserting default session data...</p>";
    $checkStmt = $db->query("SELECT COUNT(*) as count FROM spa_sessions");
    $count = $checkStmt->fetch(PDO::FETCH_ASSOC)['count'];

    if ($count == 0) {
        $sql3 = "INSERT INTO spa_sessions (name, image, therapy_time, price, description) VALUES
        ('Session Green', '', '60 minutes', 2500.00, 'Refreshing wellness session focusing on natural therapies and relaxation techniques. Perfect for quick rejuvenation.'),
        ('Session Yellow', '', '90 minutes', 3500.00, 'Energizing spa experience with premium treatments and therapeutic massage. Ideal for stress relief.'),
        ('Session Red', '', '120 minutes', 4500.00, 'Intensive full-body treatment combining multiple massage techniques. Deep tissue and aromatherapy included.'),
        ('Session Rainbow', '', '150 minutes', 6000.00, 'Ultimate luxury spa package with comprehensive wellness treatments. Complete relaxation and rejuvenation experience.')";

        $db->exec($sql3);
        echo "<p style='color: green;'>✓ Default session data inserted successfully</p>";
    } else {
        echo "<p style='color: blue;'>ℹ Default sessions already exist. Skipping insert.</p>";
    }

    echo "<h3 style='color: green;'>Migration completed successfully!</h3>";
    echo "<p><a href='admin/sessions.php'>Go to Admin Sessions Page</a></p>";
    echo "<p><a href='therapist-details.php?id=1'>View Therapist Details Page (with sessions)</a></p>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
