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
if (empty($_GET['CID']))
{
	$_SESSION['error']="please make sure you choose a valid case";
	header("Location:index.php");
}
else
{
	$case_id=$_GET['CID'];
	$doctor_id=$_SESSION['USER_ID'];

	$conn = new PDO('mysql:host=localhost;port=3306;dbname=wis_hospital' , 'root');
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$select_stmt = $conn->prepare("SELECT * FROM cases where case_id = :id");
	$select_stmt->execute(array(':id'=>$case_id));
	$row=$select_stmt->fetch(PDO::FETCH_ASSOC);
	if ($row['doctor_id']!==$doctor_id)
	{
	$_SESSION['error']="sorry, you don't have permession to update a case! if you feel that you are seeing this by mistake please contact the IT deppartment";
	header("Location:index.php");
	}
	

///////////////////////////////////// Case Preview  ////////////////////////////--------------------------->
///////////////////////////////////// Case Preview  ////////////////////////////--------------------------->
///////////////////////////////////// Case Preview  ////////////////////////////--------------------------->

	else
	{
		$report_select_stmt = $conn->prepare("SELECT * FROM reports where case_id = :id order by report_date desc limit 1");
		$report_select_stmt->execute(array(':id'=>$case_id));
		$report_row=$report_select_stmt->fetch(PDO::FETCH_ASSOC);
		if(!$report_row)
		{
			$report_row['diagnosis']=$row['diagnosis'];
			$report_row['state']=$row['state'];
			$report_row['assessment_progress']=$row['assessment_progress'];
			$report_row['plan']=$row['plan'];
		}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Case View</title>
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

	<h1>Case: <?=$row['case_id']?></h1>
	<h5 style="color: red"><?=$error?></h5><h5 style="color: rgb(0,0,255)"><?=$message?></h5>
<?php
$update_link="update.php?CID=".$row['case_id'];
$discharge_link="discharge.php?CID=".$row['case_id'];
$request_link="testreq.php?CID=".$row['case_id'];
$message="";
$_SESSION['message']="";
$error="";
$_SESSION['error']="";
?>
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
			<td class="right">Diagnosis: </td><td><?=$report_row['diagnosis']?></td>
			<td class="right">State: </td><td><?=$report_row['state']?></td>
		</tr>
		<tr style="height: 100px" >
			<td class="right" style="text-align: center;">Symptoms: </td>
			<td colspan="3" style="word-wrap: all; overflow: scroll;"><?=$report_row['assessment_progress']?></td>
		</tr>
		<tr style="height: 200px" >
			<td class="right" style="text-align: center;">Plan: </td>
			<td colspan="3" style="word-wrap: all; overflow: scroll;"><?=$report_row['plan']?></td>
		</tr>
	</table>
	<br>

<!-----------//////////////////////// Tests Preview  ////////////////////////////--------------------------->
<!-----------//////////////////////// Tests Preview  ////////////////////////////--------------------------->
<!-----------//////////////////////// Tests Preview  ////////////////////////////--------------------------->


<?php

	$result_select_stmt = $conn->prepare("SELECT * FROM cbc_test_result where case_id = :id order by result_date LIMIT 1,1");
	$result_select_stmt->execute(array(':id'=>$case_id));
	$result_row=$result_select_stmt->fetch(PDO::FETCH_ASSOC);

	if ($result_row && $result_row['result_date'] >= $report_row['report_date'])
	{

?>
	<h2>Last Taken Tests</h2>
	<h4>Test Type: CBC</h4>
	<table style="width: 100%;" border="1">
		<tr>
			<th>WBC</th><td><?=$result_row['WBC']?></td><th>LYM%</th><td><?=$result_row['LYM_pc']?></td><th>GRA%</th><td><?=$result_row['GRA_pc']?></td><th>MID%</th><td><?=$result_row['MID_pc']?></td>
		</tr>		
		<tr>
			<th>LYM</th><td><?=$result_row['LYM']?></td><th>GRAN</th><td><?=$result_row['GRAN']?></td><th>MID</th><td><?=$result_row['MID']?></td><th>RBC</th><td><?=$result_row['RBC']?></td>
		</tr>		
		<tr>
			<th>HGB</th><td><?=$result_row['HGB']?></td><th>HCT</th><td><?=$result_row['HCT']?></td><th>MCV</th><td><?=$result_row['MCV']?></td><th>MCH</th><td><?=$result_row['MCH']?></td>
		</tr>		
		<tr>
			<th>MCHC</th><td><?=$result_row['MCHC']?></td><th>RDW%</th><td><?=$result_row['RDW_pc']?></td><th>RDWa</th><td><?=$result_row['RDWa']?></td><th>PLT</th><td><?=$result_row['PLT']?></td>
		</tr>		
		<tr>
			<th>MPV</th><td><?=$result_row['MPV']?></td><th>PDW</th><td><?=$result_row['PDW']?></td><th>PCT</th><td><?=$result_row['PCT']?></td><th>LPCR</th><td><?=$result_row['LPCR']?></td>
		</tr>
	</table>

<?php
		}
	}
}
?>
<br>
	<button style="border-radius: 20%; float: right; margin-right: 20px; font-weight: bold" onclick="window.location.href='<?=$update_link?>';">Update Plan</button>
	<button style="border-radius: 20%; float: right; margin-right: 20px; font-weight: bold" onclick="window.location.href='<?=$request_link?>'">Request Test</button>
	<br><br>
	<button style="border-radius: 20%; float: right; margin-right: 20px; font-weight: bold" onclick="window.location.href='<?=$discharge_link?>'">Discharge</button>

</body>
</html>

<?php
}
catch (Exception $ex)
{
	echo ("internal error, please contact support");
	error_log("page_name, SQL error=" . $ex->getMessage());
	return;
}

?>