<?php
include "conn.php";

if(isset($_POST["course_name"])) {
    $course_name = mysqli_real_escape_string($conn, $_POST["course_name"]);
    
    $sql = "INSERT INTO courses (course_name) VALUES ('$course_name')";
    
    if(mysqli_query($conn, $sql)) {
        echo "<script>
            alert('Course added successfully!');
            window.location.href='../manage_courses.php';
        </script>";
    } else {
        echo "<script>
            alert('Error adding course: " . mysqli_error($conn) . "');
            window.location.href='../manage_courses.php';
        </script>";
    }
    exit();
}
?> 