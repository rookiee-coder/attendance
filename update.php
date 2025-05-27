<?php

include "backend/conn.php";

session_start();
$username = $_SESSION['username'];

if(isset($_GET['id']))
    {
        $id = $_GET['id'];

        $mysql1 = "SELECT * FROM students WHERE id=$id";
        $result = mysqli_query($conn, $mysql1);

        if(mysqli_num_rows($result) == 1)
        {
            $rows = mysqli_fetch_assoc($result);

            $fullname = $rows['fullname'];
            $studentid = $rows['studentid'];
            $course = $rows['course'];
            $gender = $rows['gender'];
        }

    }
    
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <script defer src="js/bootstrap.bundle.min.js"></script>

</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <h2>Welcome, <?php echo $username; ?>!</h2>
        </div>
        <div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    Select Block
  </button>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="#">BLOCK 1</a></li>
    <li><a class="dropdown-item" href="#">BLOCK 2</a></li>
    <li><a class="dropdown-item" href="#">BLOCK 3</a></li>
    <li><a class="dropdown-item" href="#">BLOCK 4</a></li>
    <li><a class="dropdown-item" href="#">BLOCK 5</a></li>
  </ul>
</div>
        <div class="header-buttons">
            <a class="action-btn edit-btn" href="">Add Student</a>
            <a class="logout-btn" href="backend/logout_user.php"
                onclick="return confirm('Are you sure you want to Logout?');"
            >Logout</a>
        </div>
    </div>

    <form method="POST" action="">
      <h2>Update Student</h2>

      <label>Student_Name</label>
      <input type="text" name="fullname" required value="<?php echo $fullname; ?>">

      <label>Student_ID</label>
      <input value="<?php echo $studentid;?>" type="text" name="studentid" required>

      <label>Course</label>
      <input value="<?php echo $course;?>" type="text" name="course" required>

      <label>Gender</label><br>

      <label class="radio-option">
      <input <?php if($gender == 'male')?>  value="Male" type="radio" name="gender" required> Male
      </label>

      <label class="radio-option">
      <input <?php if($gender == 'female') ?> value="Female" type="radio" name="gender" required> Female
      </label> <br>
      <input type="hidden" name="id" value="<?php echo $id;?>">
      <input type="submit" name="update"
      style="padding: 10px; width: 100%; background-color:#10b504;
        border: none; border-radius: 5px; color: white; cursor: pointer;
        font-weight: bold; transition: background-color 0.3s;">
   </form>
   <div>
    <center>
        <a href="index.php"><img src="img/arrow-back-regular-36.png" alt=""></a>
    </center>
   </div>

   <?php
    if(isset($_POST['update']))
    {
       
        $fullname = $_POST['fullname'];
        $studentid = $_POST['studentid'];
        $course = $_POST['course'];
        $gender = $_POST['gender'];


        $sql = "UPDATE students SET 
        fullname ='$fullname',
        course = '$course',
        studentid = '$studentid',
        gender = '$gender'
        WHERE id =$id;
        ";

        $res = mysqli_query($conn, $sql);

        if($res == TRUE)
        {
            echo "
            <script>
                alert('Successfully Update');
                window.location.href = '../attendance/index.php';
            </script>
            ";
            exit(); 
        }
        else
        {
            echo "
            <script>
                alert('Failed to Update');
                window.location.href = '../index.php';
            </script>
            ";
            exit(); 
        }
    }

?>

</body>
</html>
