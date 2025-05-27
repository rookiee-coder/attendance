<?php
include "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = mysqli_real_escape_string($conn, $_POST['id']);
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);

    // Validate required fields
    if (empty($course_id) || empty($course_name)) {
        echo "<script>
                alert('Please fill in all required fields');
                window.location.href = '../manage_courses.php';
              </script>";
        exit();
    }

    // Update course name
    $sql = "UPDATE courses 
            SET course_name = '$course_name'
            WHERE id = '$course_id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Course updated successfully');
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