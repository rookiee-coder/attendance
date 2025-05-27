<?php
include "conn.php";

// Create classes table
$sql_classes = "CREATE TABLE IF NOT EXISTS classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if(mysqli_query($conn, $sql_classes)) {
    echo "Classes table created successfully<br>";
} else {
    echo "Error creating classes table: " . mysqli_error($conn) . "<br>";
}

// Create sections table
$sql_sections = "CREATE TABLE IF NOT EXISTS sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    section_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id)
)";

if(mysqli_query($conn, $sql_sections)) {
    echo "Sections table created successfully<br>";
} else {
    echo "Error creating sections table: " . mysqli_error($conn) . "<br>";
}

// Modify students table
$sql_alter_students = "ALTER TABLE students 
    DROP COLUMN block,
    ADD COLUMN section_id INT,
    ADD FOREIGN KEY (section_id) REFERENCES sections(id)";

if(mysqli_query($conn, $sql_alter_students)) {
    echo "Students table modified successfully<br>";
} else {
    echo "Error modifying students table: " . mysqli_error($conn) . "<br>";
}

echo "Tables created successfully";
?> 