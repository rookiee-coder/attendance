<?php
include "conn.php";

if(isset($_POST["class_name"])) {
    $class_name = mysqli_real_escape_string($conn, $_POST["class_name"]);
    
    $sql = "INSERT INTO classes (class_name) VALUES ('$class_name')";
    
    if(mysqli_query($conn, $sql)) {
        echo "<script>
            alert('Class added successfully!');
            window.location.href='../manage_classes.php';
        </script>";
    } else {
        echo "<script>
            alert('Error adding class: " . mysqli_error($conn) . "');
            window.location.href='../manage_classes.php';
        </script>";
    }
    exit();
}
?> 