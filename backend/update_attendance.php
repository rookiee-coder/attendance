<?php
include "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attendance_id = mysqli_real_escape_string($conn, $_POST['attendance_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Validate status
    $valid_statuses = array('Present', 'Absent', 'Late');
    if (!in_array($status, $valid_statuses)) {
        echo "<script>
                alert('Invalid status selected');
                window.location.href = '../index.php';
              </script>";
        exit();
    }

    // Update attendance record
    $sql = "UPDATE attendance SET status = '$status' WHERE id = '$attendance_id'";

    if (mysqli_query($conn, $sql)) {
        // Set success message in session
        session_start();
        $_SESSION['message'] = "Attendance updated successfully";
        $_SESSION['message_type'] = "success";
    } else {
        // Set error message in session
        session_start();
        $_SESSION['message'] = "Error updating attendance: " . mysqli_error($conn);
        $_SESSION['message_type'] = "danger";
    }

    // Redirect back to index
    header("Location: ../index.php");
    exit();
} else {
    header("Location: ../index.php");
    exit();
}
?> 