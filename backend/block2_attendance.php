<?php 

include "conn.php";

if(isset($_POST["submit"])){

    $attendance = $_POST['present'];
    $date = $_POST['date'];
    $id = $_POST['id'];

    $sql = "UPDATE students SET
     attendance = '$attendance',
     date = '$date'
     WHERE id='$id'";


    $res = mysqli_query ($conn, $sql);

    if($res==true){

        echo "
        <script>
            alert('Attendance Success!');
            window.location.href='../block2.php';
        </script>
        ";
        exit();
}

}
?>