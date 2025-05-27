<?php
include "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
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

    // First get the current student's data
    $current_sql = "SELECT studentid FROM students WHERE id = '$id'";
    $current_result = mysqli_query($conn, $current_sql);
    $current_student = mysqli_fetch_assoc($current_result);

    // Only check for duplicate if the student ID has changed
    if ($current_student['studentid'] !== $studentid) {
        $check_sql = "SELECT * FROM students WHERE studentid = '$studentid' AND id != '$id'";
        $check_result = mysqli_query($conn, $check_sql);
        
        if (mysqli_num_rows($check_result) > 0) {
            echo "<script>
                    alert('Student ID already exists');
                    window.location.href = '../index.php';
                  </script>";
            exit();
        }
    }

    // Update student
    $sql = "UPDATE students 
            SET fullname = '$fullname',
                studentid = '$studentid',
                course_id = '$course_id',
                section_id = " . ($section_id ? "'$section_id'" : "NULL") . ",
                gender = '$gender'
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Student updated successfully');
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