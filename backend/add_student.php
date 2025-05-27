<?php
include "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $studentid = mysqli_real_escape_string($conn, $_POST['studentid']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    $section_id = mysqli_real_escape_string($conn, $_POST['section_id']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    // Validate required fields
    if (empty($fullname) || empty($studentid) || empty($course_id) || empty($gender)) {
        echo "<script>
                alert('Please fill in all required fields');
                window.location.href = '../index.php';
              </script>";
        exit();
    }

    // Check if student ID already exists
    $check_sql = "SELECT * FROM students WHERE studentid = '$studentid'";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>
                alert('Student ID already exists');
                window.location.href = '../index.php';
              </script>";
        exit();
    }

    // Insert new student
    $sql = "INSERT INTO students (fullname, studentid, course_id, section_id, gender) 
            VALUES ('$fullname', '$studentid', '$course_id', " . 
            ($section_id ? "'$section_id'" : "NULL") . ", '$gender')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Student added successfully');
                window.location.href = '../index.php';
              </script>";
    } else {
        echo "<script>
                alert('Error: " . mysqli_error($conn) . "');
                window.location.href = '../index.php';
              </script>";
    }
} else {
    header("Location: ../index.php");
}
?>





