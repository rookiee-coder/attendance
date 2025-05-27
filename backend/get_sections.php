<?php
include "conn.php";

if(isset($_GET["course_id"])) {
    $course_id = mysqli_real_escape_string($conn, $_GET["course_id"]);
    
    $sql = "SELECT * FROM sections WHERE course_id = '$course_id' ORDER BY section_name";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error: ' . mysqli_error($conn)]);
        exit();
    }
    
    $sections = array();
    while($row = mysqli_fetch_assoc($result)) {
        $sections[] = array(
            "id" => $row["id"],
            "section_name" => $row["section_name"]
        );
    }
    
    header('Content-Type: application/json');
    echo json_encode($sections);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No course_id provided']);
}
?> 