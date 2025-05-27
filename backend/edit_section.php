<?php
include "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $section_id = mysqli_real_escape_string($conn, $_POST['id']);
    $section_name = mysqli_real_escape_string($conn, $_POST['section_name']);

    // Validate required fields
    if (empty($section_id) || empty($section_name)) {
        echo "<script>
                alert('Please fill in all required fields');
                window.location.href = '../manage_courses.php';
              </script>";
        exit();
    }

    // Update section name only
    $sql = "UPDATE sections 
            SET section_name = '$section_name'
            WHERE id = '$section_id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Section updated successfully');
                window.location.href = '../manage_courses.php';
              </script>";
    } else {
        echo "<script>
                alert('Error: " . mysqli_error($conn) . "');
                window.location.href = '../manage_courses.php';
              </script>";
    }
} else {
    header("Location: ../manage_courses.php");
}
?> 