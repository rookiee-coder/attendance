<?php
include "conn.php";

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // First get all sections in this course
        $sections_sql = "SELECT id FROM sections WHERE course_id = '$id'";
        $sections_result = mysqli_query($conn, $sections_sql);
        
        // Delete attendance records for all students in this course
        $delete_attendance = "DELETE a FROM attendance a 
                            JOIN students s ON a.student_id = s.id 
                            JOIN sections sec ON s.section_id = sec.id 
                            WHERE sec.course_id = '$id'";
        mysqli_query($conn, $delete_attendance);
        
        // Delete all students in this course
        $delete_students = "DELETE s FROM students s 
                           JOIN sections sec ON s.section_id = sec.id 
                           WHERE sec.course_id = '$id'";
        mysqli_query($conn, $delete_students);
        
        // Delete all sections in this course
        $delete_sections = "DELETE FROM sections WHERE course_id = '$id'";
        mysqli_query($conn, $delete_sections);
        
        // Finally delete the course
        $delete_course = "DELETE FROM courses WHERE id = '$id'";
        mysqli_query($conn, $delete_course);
        
        // If everything is successful, commit the transaction
        mysqli_commit($conn);
        
        // Set success message in session
        session_start();
        $_SESSION['message'] = "Course and all associated data deleted successfully";
        $_SESSION['message_type'] = "success";
    } catch (Exception $e) {
        // If there's an error, rollback the transaction
        mysqli_rollback($conn);
        
        // Set error message in session
        session_start();
        $_SESSION['message'] = "Error deleting course: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
    
    // Redirect back to manage courses page
    header("Location: ../manage_courses.php");
    exit();
} else {
    header("Location: ../manage_courses.php");
    exit();
}
?> 