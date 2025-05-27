<?php
include "conn.php";

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // First, delete all attendance records for this student
        $delete_attendance = "DELETE FROM attendance WHERE student_id = '$id'";
        if (!mysqli_query($conn, $delete_attendance)) {
            throw new Exception("Error deleting attendance records: " . mysqli_error($conn));
        }
        
        // Then delete the student
        $delete_student = "DELETE FROM students WHERE id = '$id'";
        if (!mysqli_query($conn, $delete_student)) {
            throw new Exception("Error deleting student: " . mysqli_error($conn));
        }
        
        // If we get here, commit the transaction
        mysqli_commit($conn);
        
        // Set success message in session
        session_start();
        $_SESSION['message'] = "Student deleted successfully";
        $_SESSION['message_type'] = "success";
        
        // Redirect back to index
        header("Location: ../index.php");
        exit();
        
    } catch (Exception $e) {
        // If there's an error, rollback the transaction
        mysqli_rollback($conn);
        
        // Set error message in session
        session_start();
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
        
        // Redirect back to index
        header("Location: ../index.php");
        exit();
    }
} else {
    // If no ID provided, redirect to index
    header("Location: ../index.php");
    exit();
}
?> 