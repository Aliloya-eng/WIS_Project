<?php
try{
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

if ($_SESSION['USER_TYPE']=="doctor")
{

		if(isset($_POST['add']))
	{
		$urgent="no";
		if(isset($_POST['urgent'])&&$_POST['urgent']=="yes")
		{
			$urgent="yes";
		}

		$conn = new PDO('mysql:host=localhost;port=3306;dbname=wis_hospital' , 'root');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$insert_stmt = $conn->prepare("INSERT INTO cases (case_id, patient_id, doctor_id, department, assessment_progress, state, diagnosis, in_out, room, case_entry_date, `urgent`, `plan`, `discharged`) VALUES (NULL, :patient_id, :doctor_id, :case_specialty, :symptoms, :current_state, :disease, :in_out, :bed_number, CURRENT_TIMESTAMP, :urgent, :plan, 'no')");
		$insert_stmt->execute(array(
			':patient_id'=>$_POST['patient_id'],
			':doctor_id'=>$_SESSION['USER_ID'],
			':case_specialty'=>$_POST['department'],
			':symptoms'=>$_POST['assessment'],
			':current_state'=>$_POST['state'],
			':disease'=>$_POST['diagnosis'],
			':in_out'=>$_POST['in_out'],
			':bed_number'=>$_POST['room'],
			':urgent'=>$urgent,
			':plan'=>$_POST['plan'],
		));

		$select_stmt = $conn->prepare("SELECT * FROM cases where doctor_id =:id order by case_entry_date desc limit 1");
		$select_stmt->execute(array(':id'=>$_SESSION['USER_ID']));
		$row=$select_stmt->fetch(PDO::FETCH_ASSOC);

		$_SESSION['message']="Case Added Successfuly";
		$case_link="case.php?CID=".$row['case_id'];
		header("Location:$case_link");
	}
	else
	{
?>

<!DOCTYPE html>
<html>
<head>
	<title>New Case Entry</title>
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

	<h1>New Case:</h1>
	<h5 style="color: red"><?=$error?></h5><h5 style="color: rgb(0,0,255);"><?=$message?></h5>

<?php
$message="";
$_SESSION['message']="";
$error="";
$_SESSION['error']="";

?>
<form method="post">
	<table style="width: 100%" border="1">
		<tr>
			<td class="right">Patient ID:</td>
			<td>
				<input required style="word-wrap: all; overflow: scroll; width: 99%; border-width: 2px; border-color: black" type="text" name="patient_id">
			</td>
			<td class="right">Doctor id:</td>
			<td><?=$_SESSION['USER_ID']?></td>
		</tr>
		<tr>
			<td class="right">Case entry date:</td><td>" AUTO "</td>
			<td class="right">Department:</td><td><input required style="word-wrap: all; overflow: scroll; width: 99%; border-width: 2px; border-color: black" type="text" name="department"></td>
		</tr>
		<tr>
			<td class="right">In/Out :</td>
			<td>
				<input required type="radio" name="in_out" value="in">in
				<input required type="radio" name="in_out" value="out">out
			</td>
			<td class="right">Room (if In-Patient):</td>
			<td>
				<input style="word-wrap: all; overflow: scroll; width: 99%; border-width: 2px; border-color: black" type="number" name="room">
			</td>
		</tr>
		<tr>
			<td class="right">Diagnosis: </td>
			<td>
				<input required style="word-wrap: all; overflow: scroll; width: 99%; border-width: 2px; border-color: black" type="text" name="diagnosis">
			</td>
			<td class="right">State: </td>
			<td>
				<input required style="word-wrap: all; overflow: scroll; width: 99%; border-width: 2px; border-color: black" type="text" name="state">
			</td>
		</tr>
		<tr style="height: 100px" >
			<td class="right" style="text-align: center;"> Assessment: </td>
			<td colspan="3">
				<input required style="word-wrap: all; overflow: scroll; width: 99%; height: 100px; border-width: 2px; border-color: black" type="text" name="assessment">
			</td>
		</tr>
		<tr style="height: 200px" >
			<td class="right" style="text-align: center;">Plan: </td>
			<td colspan="3">
				<input style="word-wrap: all; overflow: scroll; width: 99%; height: 200px; border-width: 2px; border-color: black;" type="text" name="plan">
			</td>
		</tr>
	</table>
	<br>
	<label><input type="checkbox" name="urgent" value="yes">Urgent</label>
	<input type="submit" name="add" value="Add Case" style="border-radius: 20%; float: right; margin-right: 20px; font-weight: bold">
	<button style="border-radius: 20%; float: right; margin-right: 20px; font-weight: bold" onclick="window.location.href='index.php';">Cancel</button>
</form>

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