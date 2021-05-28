<?php
try{
///////////////////////////////////////////////// Chacking //////////////////////////////////////////////////////
///////////////////////////////////////////////// Chacking //////////////////////////////////////////////////////
///////////////////////////////////////////////// Chacking //////////////////////////////////////////////////////

session_start();

$message="";
if (isset($_SESSION['message']))
{
	$message=$_SESSION['message'];
}
$error="";
if (isset($_SESSION['error']))
{
	$error=$_SESSION['error'];
}

if (empty($_SESSION['USER_ID']))
{
	header("Location:logout.php");
}
else
{
	$id=$_SESSION['USER_ID'];

	if($_SESSION['USER_TYPE']=="patient")
	{
		$_SESSION['error']="patients' page is not ready yet, sorry for the inconvenience, our engineers are on it";
		header("Location:login.php");
	}

///////////////////////////////////////////////  Patients Table Preview //////////////////////////////////////////////////
///////////////////////////////////////////////  Patients Table Preview //////////////////////////////////////////////////
///////////////////////////////////////////////  Patients Table Preview //////////////////////////////////////////////////

	if($_SESSION['USER_TYPE']=="doctor")
	{
		$conn = new PDO('mysql:host=localhost;port=3306;dbname=wis_hospital' , 'root');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$select_stmt = $conn->prepare("SELECT * FROM cases where doctor_id = :id and discharged = 'no' order by urgent");
		$select_stmt->execute(array(':id'=>$id));
		$counter=0;
?>

<!DOCTYPE html>
<html>
<head>
	<title>WIS_EHR Doctor Cases</title>
		<style type="text/css">
			.right{text-align: right; font-weight: bold;}
			ul li
			{
				display: inline-block;
				width: 33%;
				text-align: center;
				line-height: 200%;
				background-color: rgba(100,200,255,0.8);
				border-radius: 15px;
				font-weight: bolder;
				font-size: large;
			}
		</style>

</head>
<body style="padding: 20px">
	<nav>
		<ul style="width: 99%; padding: 0">
			<li onclick="history.go(-1);" >Back</li>
			<li onclick="window.location.href='index.php'">HOME</li>
			<li onclick="window.location.href='logout.php'">LOGOUT</li>
		</ul>
	</nav>
<header style="padding-right: 5%; padding-left: 5%">
	<h1 style="display: inline-block;">Welcome Doctor</h1>
	<button style="float:right; vertical-align: middle; font-size: large; font-weight: bold; line-height: 300%; border-radius: 25%; width: 15%; margin-right: 5%" onclick="window.location.href='search.php'">Search</button>
	<button style="float:right; vertical-align: middle; font-size: large; font-weight: bold; line-height: 300%; border-radius: 25%; width: 15%" onclick="window.location.href='add.php'">Add New Case</button>
</header>
	<p style="color: red"><?=$error?></p><h5 style="color: rgb(0,0,255)"><?=$message?></h5>
<?php
	$message="";
	$_SESSION['message']="";
	$error="";
	$_SESSION['error']="";
?>
	<p>here are all the cases that are under your suppervision:</p>
	<table style="width: 99%; text-align: center;" border="1">
		<tr>
			<th>N</th><th>case</th><th>patient</th><th>current state</th><th>in|out</th><th>entry date</th>
		</tr>
		
<?php
		while($row=$select_stmt->fetch(PDO::FETCH_ASSOC))
		{
			if($row['discharged']=="yes")
			{
				continue;
			}
			$counter++;
			$case_id=$row['case_id'];
			$case_link="case.php?CID=".$case_id;
			if($row['urgent']=="yes")
			{
				?>
				<tr style="color: red">
				<?php
			}
			else
			{
				?>
				<tr>
				<?php
			}
?>
			<td><?=$counter?></td>
<!--  -->
<!--  -->
			<td><a href=<?=$case_link?>><?=$case_id?></a></td>
<!--  -->
<!--  -->
			<td><?=$row['patient_id']?></td>
			<td><?=$row['state']?></td>
			<td><?=$row['in_out']?></td>
			<td><?=$row['case_entry_date']?></td>
		</tr>
<?php
		}
?>
	</table>
</body>
</html>

<?php
	}
}
}
catch (Exception $ex)
{
	echo ("internal error, please contact support");
	error_log("page_name, SQL error=" . $ex->getMessage());
	return;
}

?>