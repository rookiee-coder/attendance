<?php 

include "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the current date
    $date = date('Y-m-d');
    $section_id = mysqli_real_escape_string($conn, $_POST['section_id']);
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Delete any existing attendance for this section and date
        $delete_sql = "DELETE a FROM attendance a 
                      JOIN students s ON a.student_id = s.id 
                      WHERE s.section_id = '$section_id' 
                      AND a.date = '$date'";
        mysqli_query($conn, $delete_sql);
        
        // Insert new attendance records
        if (isset($_POST['attendance']) && is_array($_POST['attendance'])) {
            foreach ($_POST['attendance'] as $student_id => $status) {
                $student_id = mysqli_real_escape_string($conn, $student_id);
                $status = mysqli_real_escape_string($conn, $status);
                
                $sql = "INSERT INTO attendance (student_id, status, date) 
                        VALUES ('$student_id', '$status', '$date')";
                
                if (!mysqli_query($conn, $sql)) {
                    throw new Exception("Error saving attendance: " . mysqli_error($conn));
                }
            }
        }
        
        // Commit transaction
        mysqli_commit($conn);
        
        // Set success message in session
        session_start();
        $_SESSION['message'] = "Attendance saved successfully";
        $_SESSION['message_type'] = "success";
        
        // Redirect with success parameter
        header("Location: ../index.php?success=1");
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        
        // Set error message in session
        session_start();
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
        
        // Redirect with error parameter
        header("Location: ../index.php?error=1");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}

function determineAttendanceStatus($timestamp) {
    $class_start_time = strtotime('08:00:00'); // Set your class start time
    $late_threshold = strtotime('08:15:00'); // Set your late threshold
    
    $current_time = strtotime(date('H:i:s', strtotime($timestamp)));
    
    if($current_time <= $class_start_time) {
        return 'present';
    } elseif($current_time <= $late_threshold) {
        return 'late';
    } else {
        return 'absent';
    }
}
?>