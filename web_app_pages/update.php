<?php 

try{
///////////////////////////////////////////////// Chacking //////////////////////////////////////////////////////
///////////////////////////////////////////////// Chacking //////////////////////////////////////////////////////
///////////////////////////////////////////////// Chacking //////////////////////////////////////////////////////

session_start();

if (empty($_SESSION['USER_ID']))
{
	header("Location:logout.php");
}
if (empty($_GET['CID']))
{
	$_SESSION['error']="please make sure you choose a valid case";
	header("Location:index.php");
}
$case_id=$_GET['CID'];
$doctor_id=$_SESSION['USER_ID'];

if($_SESSION['USER_TYPE']!=='doctor')
{
	$_SESSION['error']="sorry, you don't have permession to update this case! if you feel that you are seeing this by mistake please contact the IT department";
	$case_link="case.php?CID=".$case_id;
	header("Location:$case_link");
}
else
{

	$conn = new PDO('mysql:host=localhost;port=3306;dbname=wis_hospital' , 'root');
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$select_stmt = $conn->prepare("SELECT * FROM cases where case_id = :id");
	$select_stmt->execute(array(':id'=>$case_id));
	$row=$select_stmt->fetch(PDO::FETCH_ASSOC);
	if ($row['doctor_id']!==$doctor_id)
	{
	$_SESSION['error']="sorry, you don't have permession to update this case! if you feel that you are seeing this by mistake please contact the IT department";
	header("Location:index.php");
	}

////////////////////////////////////////           Creating Report         ////////////////////////////////////////////
////////////////////////////////////////           Creating Report         ////////////////////////////////////////////
////////////////////////////////////////           Creating Report         ////////////////////////////////////////////

	if(isset($_POST['update']))
	{
		$urgent="no";
		if(isset($_POST['urgent'])&&$_POST['urgent']=="yes")
		{
			$urgent="yes";
			$update_stmt = $conn->prepare("UPDATE cases set urgent=:urgent where case_id = :id");
			$update_stmt->execute(array(':urgent'=>$urgent,':id'=>$case_id));
		}

		$conn = new PDO('mysql:host=localhost;port=3306;dbname=wis_hospital' , 'root');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$insert_stmt = $conn->prepare("INSERT INTO reports (patient_id,doctor_id,case_id,plan,diagnosis,state,assessment_progress) VALUES (:patient_id,:doctor_id,:case_id,:plan,:diagnosis,:current_state,:assessment_progress)");
		$insert_stmt->execute(array(
			':patient_id'=>$row['patient_id'],
			':doctor_id'=>$row['doctor_id'],
			':case_id'=>$case_id,
			':plan'=>$_POST['plan'],
			':diagnosis'=>$_POST['diagnosis'],
			':current_state'=>$_POST['current_state'],
			':assessment_progress'=>$_POST['assessment_progress'],
		));

		$_SESSION['message']="Updated Successfuly";
		$case_link="case.php?CID=".$case_id;
		header("Location:$case_link");
	}
////////////////////////////////////////////      Preview Update Table   //////////////////////////////////////////////////
////////////////////////////////////////////      Preview Update Table   //////////////////////////////////////////////////
////////////////////////////////////////////      Preview Update Table   //////////////////////////////////////////////////

	else
	{
	$report_select_stmt = $conn->prepare("SELECT * FROM reports where case_id = :id order by report_date desc limit 1");
	$report_select_stmt->execute(array(':id'=>$case_id));
	$report_row=$report_select_stmt->fetch(PDO::FETCH_ASSOC);

 ?>

<!DOCTYPE html>
<html>
<head>
	<title>Case Update</title>
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
	<h1>Case <?=$row['case_id']?> Update</h1>

	<form method="post">
		<table style="width: 100%" border="1">
			<tr>
				<td class="right">Patient id:</td><td><?=$row['patient_id']?></td>
				<td class="right">Doctor id:</td><td><?=$row['doctor_id']?></td>
			</tr>
			<tr>
				<td class="right">Case entry date:</td><td><?=$row['case_entry_date']?></td>
				<td class="right">Department:</td><td><?=$row['department']?></td>
			</tr>
			<tr>
				<td class="right">in/out :</td><td><?=$row['in_out']?></td>
<?php if($row['in_out']=="in"){ ?>
				<td class="right">Room :</td><td><?=$row['room']?></td>
<?php }elseif($row['in_out']=="out"){ ?>
				<td> </td><td> </td>
<?php } ?>
			</tr>
			<tr>
				<td class="right">Diagnosis: </td>
				<td>
					<input style="word-wrap: all; overflow: scroll; width: 99%; border-width: 2px; border-color: black" type="text" name="diagnosis" value=<?=$report_row['diagnosis']?>>
				</td>
				<td class="right">State: </td>
				<td>
					<input style="word-wrap: all; overflow: scroll; width: 99%; border-width: 2px; border-color: black" type="text" name="current_state" value=<?=$report_row['state']?>>
				</td>
			</tr>
			<tr style="height: 100px" >
				<td class="right" style="text-align: center;">assessment_progress: </td>
				<td colspan="3">
					<input style="word-wrap: all; overflow: scroll; width: 99.5%; border-width: 2px; border-color: black; height: 100px;" type="text" name="assessment_progress" value=<?=$report_row['assessment_progress']?>>
				</td>
			</tr>
			<tr style="height: 200px" >
				<td class="right" style="text-align: center;">Plan: </td>
				<td colspan="3">
					<input style="word-wrap: all; overflow: scroll; width: 99.5%; border-width: 2px; border-color: black; height: 200px;" type="text" name="plan" value=<?=$report_row['plan']?>>
				</td>
			</tr>
		</table>
		<br>
		<label><input type="checkbox" name="urgent" value="yes">Urgent</label>
		<input type="submit" name="update" value="Update" style="border-radius: 20%; float: right; margin-right: 20px; font-weight: bold">
		<button style="border-radius: 20%; float: right; margin-right: 20px; font-weight: bold" onclick="history.go(-1);">Cancel</button>
	</form>
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