<?php
include "conn.php";

if(isset($_GET["section_id"])) {
    $section_id = mysqli_real_escape_string($conn, $_GET["section_id"]);
    
    // Get all attendance records for this section, ordered by date (newest first)
    $sql = "SELECT a.*, s.fullname, s.studentid, s.gender, DATE(a.date) as attendance_date
            FROM attendance a
            JOIN students s ON a.student_id = s.id
            WHERE s.section_id = '$section_id'
            ORDER BY a.date DESC, s.fullname ASC";
            
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
        exit();
    }
    
    if(mysqli_num_rows($result) > 0) {
        $current_date = null;
        $attendance_data = array();
        
        // Group attendance records by date
        while($row = mysqli_fetch_assoc($result)) {
            $date = $row['attendance_date'];
            if (!isset($attendance_data[$date])) {
                $attendance_data[$date] = array();
            }
            $attendance_data[$date][] = $row;
        }
        
        // Display attendance records grouped by date
        foreach($attendance_data as $date => $records) {
            echo '<div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">' . date('F d, Y', strtotime($date)) . '</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Student ID</th>
                                        <th>Gender</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
            
            foreach($records as $record) {
                $status_class = '';
                switch($record['status']) {
                    case 'Present':
                        $status_class = 'success';
                        break;
                    case 'Absent':
                        $status_class = 'danger';
                        break;
                    case 'Late':
                        $status_class = 'warning';
                        break;
                }
                
                echo '<tr>
                        <td>' . htmlspecialchars($record['fullname']) . '</td>
                        <td>' . htmlspecialchars($record['studentid']) . '</td>
                        <td>' . htmlspecialchars($record['gender']) . '</td>
                        <td><span class="badge bg-' . $status_class . '">' . htmlspecialchars($record['status']) . '</span></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary" 
                                    onclick="editAttendance(' . $record['id'] . ', \'' . 
                                    htmlspecialchars($record['status'], ENT_QUOTES) . '\')" 
                                    data-bs-toggle="modal" data-bs-target="#editAttendanceModal">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </td>
                    </tr>';
            }
            
            echo '</tbody></table></div></div></div>';
        }
    } else {
        echo '<div class="alert alert-info">No attendance records found for this section.</div>';
    }
} else {
    echo '<div class="alert alert-warning">No section selected.</div>';
}
?> 