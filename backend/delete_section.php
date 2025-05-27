<?php
include "conn.php";

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // First delete all attendance records for students in this section
        $delete_attendance = "DELETE a FROM attendance a 
                            JOIN students s ON a.student_id = s.id 
                            WHERE s.section_id = '$id'";
        mysqli_query($conn, $delete_attendance);
        
        // Then delete all students in this section
        $delete_students = "DELETE FROM students WHERE section_id = '$id'";
        mysqli_query($conn, $delete_students);
        
        // Finally delete the section
        $delete_section = "DELETE FROM sections WHERE id = '$id'";
        mysqli_query($conn, $delete_section);
        
        // If everything is successful, commit the transaction
        mysqli_commit($conn);
        
        // Set success message in session
        session_start();
        $_SESSION['message'] = "Section and all associated data deleted successfully";
        $_SESSION['message_type'] = "success";
    } catch (Exception $e) {
        // If there's an error, rollback the transaction
        mysqli_rollback($conn);
        
        // Set error message in session
        session_start();
        $_SESSION['message'] = "Error deleting section: " . $e->getMessage();
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