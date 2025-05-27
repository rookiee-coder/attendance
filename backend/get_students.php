<?php
include "conn.php";

if(isset($_GET["section_id"])) {
    $section_id = mysqli_real_escape_string($conn, $_GET["section_id"]);
    
    // First check if the section_id column exists
    $check_column = "SHOW COLUMNS FROM students LIKE 'section_id'";
    $column_result = mysqli_query($conn, $check_column);
    
    if (!$column_result || mysqli_num_rows($column_result) == 0) {
        echo '<div class="alert alert-warning">
                <h5>Database Update Required</h5>
                <p>The database needs to be updated to support sections. Please run the update script in the database folder.</p>
                <p>Error: section_id column not found in students table.</p>
              </div>';
        exit();
    }
    
    $sql = "SELECT s.*, c.course_name 
            FROM students s 
            JOIN courses c ON s.course_id = c.id 
            WHERE s.section_id = '$section_id' 
            ORDER BY s.fullname";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
        exit();
    }
    
    if(mysqli_num_rows($result) > 0) {
        echo '<table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Gender</th>
                        <th>Student ID</th>
                        <th>Course</th>
                        <th>Action</th>
                        <th>Attendance</th>
                    </tr>
                </thead>
                <tbody>';
        
        $counter = 1;
        while($row = mysqli_fetch_assoc($result)) {
            // Prepare data for JavaScript
            $studentData = array(
                'id' => $row['id'],
                'fullname' => $row['fullname'],
                'studentid' => $row['studentid'],
                'course_id' => $row['course_id'],
                'section_id' => $row['section_id'],
                'gender' => $row['gender']
            );
            
            echo '<tr>
                    <td>' . $counter . '</td>
                    <td>' . htmlspecialchars($row['fullname']) . '</td>
                    <td>' . htmlspecialchars($row['gender']) . '</td>
                    <td>' . htmlspecialchars($row['studentid']) . '</td>
                    <td>' . htmlspecialchars($row['course_name']) . '</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary" 
                                onclick="editStudent(' . htmlspecialchars(json_encode($studentData)) . ')" 
                                data-bs-toggle="modal" data-bs-target="#editStudentModal">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <a href="backend/delete_student.php?id=' . $row['id'] . '" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm(\'Are you sure you want to delete this student?\')">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </td>
                    <td>
                        <select class="form-select form-select-sm attendance-status" name="attendance[' . $row['id'] . ']">
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                            <option value="Late">Late</option>
                        </select>
                    </td>
                </tr>';
            $counter++;
        }
        
        echo '</tbody></table>';
        echo '<div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">Save Attendance</button>
              </div>';
    } else {
        echo '<div class="alert alert-info">No students found in this section.</div>';
    }
} else {
    echo '<div class="alert alert-warning">No section selected.</div>';
}
?> 