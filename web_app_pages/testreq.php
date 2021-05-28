<?php 
try{
session_start();

if (empty($_SESSION['USER_ID']))
{
	header("Location:logout.php");
}

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

if ($_SESSION['USER_TYPE']=="doctor")
{
	$doctor_id=$_SESSION['USER_ID'];
	if (isset($_POST['request']))
	{

		if (isset($_POST['CID']))
		{
			$_SESSION['CID']=$_POST['CID'];
			$case_id=$_POST['CID'];
		}

		$case_link="case.php?CID=".$_POST['CID'];

		$conn = new PDO('mysql:host=localhost;port=3306;dbname=wis_hospital' , 'root');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$select_stmt = $conn->prepare("SELECT * FROM cases where case_id = :id");
		$select_stmt->execute(array(':id'=>$case_id));
		$row=$select_stmt->fetch(PDO::FETCH_ASSOC);
		$doctor_id2=$row['doctor_id'];

		if($doctor_id2 != $doctor_id)
		{
		$_SESSION['error']="sorry, you don't have permession to request a test for this case! if you feel that you are seeing this by mistake please contact the IT deppartment";
		header("Location:index.php");
		}
		
			////////////before Taher : 
		// $insert_stmt = $conn->prepare("INSERT into tests (patient_id,doctor_id,case_id,test_type) values (:p_id,:d_id,c_id,test_type)");
		// $insert_stmt->execute(array(
		// 	':p_id'=>$row['patient_id'],
		// 	':d_id'=>$doctor_id,
		// 	':c_id'=>$case_id,
		// 	':test_type'=>$_POST['type'],
		// ));

			//////////after Taher : 
		$insert_stmt = $conn->prepare("INSERT into tests (patient_id,doctor_id,case_id,test_type) values (?,?,?,?)");
		$insert_stmt->execute(array($row['patient_id'],$doctor_id,$case_id,$_POST['type']));
		$_SESSION['message']="Request was sent";
		header("Location:$case_link");
	}
	else
	{
	if (empty($_GET['CID']))
	{
		$_SESSION['error']="please go back and select the case you want to request a test for";
		header("Location:index.php");
	}
	else
	{
		$case_id=$_GET['CID'];

?>

<!DOCTYPE html>
<html>
<head>
	<title>Test Request</title>
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

<section style="padding:5%; margin: 0 auto;">

<h1>Test Request</h1>
<p>please chose the type of the test you want to request</p>
<br>
	<form method="post">
		<select name="type" style="display: inline-block;">
			<option value="cbc" selected>CBC</option>
			<option value="amniocentesis">amniocentesis</option>
			<option value="gastric fluid analysis">gastric fluid analysis</option>
			<option value="protein-bound iodine test">protein-bound iodine test</option>
			<option value="toxicology test">toxicology test</option>
		</select>
		<input type="text" name="CID" value="<?=$case_id?>" style="display: none;">
		<input type="submit" name="request" value="Request" style="border-radius: 20%; margin-left: 30px; font-weight: bold">
	</form>
</section>

</body>
</html>

<?php
	}
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