<?php
include "conn.php";

if(isset($_POST["course_id"]) && isset($_POST["section_name"])) {
    $course_id = mysqli_real_escape_string($conn, $_POST["course_id"]);
    $section_name = mysqli_real_escape_string($conn, $_POST["section_name"]);
    
    $sql = "INSERT INTO sections (course_id, section_name) VALUES ('$course_id', '$section_name')";
    
    if(mysqli_query($conn, $sql)) {
        echo "<script>
            alert('Section added successfully!');
            window.location.href='../manage_courses.php';
        </script>";
    } else {
        echo "<script>
            alert('Error adding section: " . mysqli_error($conn) . "');
            window.location.href='../manage_courses.php';
        </script>";
    }
    exit();
}
?> 