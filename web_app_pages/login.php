<?php 
	try{
session_start();

$message="";
if (isset($_SESSION['message']))
{
	$message=$_SESSION['message'];
	$_SESSION['message']="";
}
$error="";
if (isset($_SESSION['error']))
{
	$error=$_SESSION['error'];
}

if (isset($_SESSION['USER_ID']) && $_SESSION['USER_TYPE']==="doctor")
{
	?>
	<section style="width: 30%; text-align: center;">
	<p style="margin: 20px">you are already signied-in, Do you want to log-out ?</p><br>
	<a href="logout.php" style="margin: 40px">Yes, logout</a><a href="#" onclick="history.go(-1); return false;" style="margin: 40px">No, go back</a>
	</section>
	<?php
	return;
}
elseif (isset($_POST['Login'])&&isset($_POST['ID'])&&$_POST['Full_Name']&&$_POST['Password']&&$_POST['User_Type'])
{
	$id=$_POST['ID'];
	$name=$_POST['Full_Name'];
	$password=md5($_POST['Password']);
	$user_type=$_POST['User_Type'];

	if ($user_type=="doctor")
	{
		$conn = new PDO('mysql:host=localhost;port=3306;dbname=wis_hospital' , 'root');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$select_stmt = $conn->prepare("SELECT * FROM doctors where doctor_id = :id ");
		$select_stmt->execute(array(':id'=>$id));
		if($row=$select_stmt->fetch(PDO::FETCH_ASSOC))
		{
			if ($row['doctor_id']==$id && $row['name']==$name && $row['password']===$password)
			{
				$_SESSION['USER_TYPE']="doctor";
				$_SESSION['USER_ID']=$id;
				header("Location: index.php");
			}
					else
		{
			$_SESSION['error']="wrong input, please make sure you entered the right information and you choose the right user type (doctor/patient)";
			header("Location: login.php");	
	
		}

		}
		else
		{
			$_SESSION['error']="wrong input, please make sure you entered the right information and you choose the right user type (doctor/patient)";
			header("Location: login.php");	
	
		}

		
	}

	elseif ($user_type=="patient")
	{
		$conn = new PDO('mysql:host=localhost;port=3306;dbname=wis_hospital' , 'root');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$select_stmt = $conn->prepare("SELECT * FROM patients where patient_id = :id ");
		$select_stmt->execute(array(':id'=>$id));
		if($row=$select_stmt->fetch(PDO::FETCH_ASSOC))
		{
			if ($row['patient_id']==$id && $row['full_name']==$name && $row['password']===$password)
			{
				// $_SESSION['USER_TYPE']="patient";
				// $_SESSION['USER_ID']=$id;
			$_SESSION['error']="patient portal is not ready yet , sorry for the inconvenient";				
				header("Location: login.php");
			}
					else
		{
			$_SESSION['error']="wrong input, please make sure you entered the right information and you choose the right user type (doctor/patient)";
			header("Location: login.php");	
	
		}

		}
		else
		{
			$_SESSION['error']="wrong input, please make sure you entered the right information and you choose the right user type (doctor/patient)";
			header("Location: login.php");	
	
		}
	}
	else
	{
	$_SESSION['error']="wrong input, please make sure you entered the right information and you choose the right user type (doctor/patient)";
	header("Location: login.php");	
	}
}
else
{
?>

<!DOCTYPE html>
<html>
<head>
	<title>WIS_EHR Login</title>
</head>
<body>
	<section style="width: 80%; margin: 0 auto; margin-top: 10%; text-align: center;">
		<h1 style="font-size: 250%">Welcome to WIS_EHR Hospital</h1>
		<p style="color: red"><?=$error?></p><h5 style="color: rgb(0,0,255)"><?=$message?></h5>
<?php
	$message="";
	session_destroy();
?>
		<h3 style="font-size: 150%">Please Login</h3>
		<form method="post">
			<table style="font-size: 150%; margin: 0 auto;" cellpadding="5px">
				<tr>
					<td align="right" style="width: 30%">
						<label for="Full_Name">Full Name: </label>
					</td>
					<td>
						<input type="text" id="Full_Name" name="Full_Name" required style="font-size: 100%; width: 100%">
					</td>
				</tr>
				<tr>
					<td align="right" style="width: 30%">
						<label for="ID">ID: </label>
					</td>
					<td>
						<input type="number" id="ID" name="ID" required style="font-size: 100%; width: 100%">
					</td>
				</tr>
				<tr>
					<td align="right" style="width: 30%">
						<label for="Password">Password: </label>
					</td>
					<td>
						<input type="password" id="Password" name="Password" required style="font-size: 100%; width: 100%">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<span>Are you a:</span>
						<label><input type="radio" name="User_Type" value="patient" required>Patient</label>
						<label><input type="radio" name="User_Type" value="doctor" required>Doctor</label>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" name="Login" value="Login" style="font-size: 100%; width: 100%; ">
					</td>
				</tr>
			</table>
		</form>
		<p style="text-align: center;">If you don't have an account yet, please please contact the IT department</p>
	</section>
</body>
</html>

<?php 
}
}
catch (Exception $ex)
{
	echo ("internal error, please contact support");
	error_log("page_name, SQL error=" . $ex->getMessage());
	return;
}

?>
