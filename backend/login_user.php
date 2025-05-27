<?php
include 'conn.php';
session_start();
if(isset($_POST['submit'])){

	$username=$_POST['username'];
	$password=$_POST['password'];

	$sql = "SELECT * FROM users WHERE username='$username'";
	$result = mysqli_query($conn,$sql);

	if(mysqli_num_rows($result)==1){
		$user=mysqli_fetch_assoc($result);

		if(password_verify($password,$user['password'])){

			$_SESSION['username'] = $user['username'];
			$_SESSION['email'] = $user['email'];
			$_SESSION['id'] = $user['id'];

			header("location:../index.php");
			exit();
		}else {
			echo "
			<script>
				alert('Incorrect Password');
				window.location.href='../login.php';
			</script>
			";
		}
	}else {
		echo "
		<script>
			alert('No user found with that email');
			window.location.href='../login.php';
		</script>
		";
	}
	}else {
		echo "
		<script>
			alert('Login Failed Please Try Again');
			window.location.href='../login.php';
		</script
		";
}
?>