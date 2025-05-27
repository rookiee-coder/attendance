<?php
include "conn.php";

$sql = "CREATE TABLE IF NOT EXISTS attendance_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) NOT NULL,
    timestamp DATETIME NOT NULL,
    status ENUM('present', 'late', 'absent') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id)
)";

if(mysqli_query($conn, $sql)) {
    echo "Attendance records table created successfully";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}
?> 