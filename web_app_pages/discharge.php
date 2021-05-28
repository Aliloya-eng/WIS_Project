<?php 
try{
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
		$_SESSION['error']="sorry, you don't have permession to update this case! if you feel that you are seeing this by mistake please contact the IT deppartment";
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
		if(!$row)
		{
			$_SESSION['error']="sorry, you don't have permession to update this case! if you feel that you are seeing this by mistake please contact the IT deppartment";
			header("Location:index.php");

		}
		if ($row['doctor_id']!==$doctor_id)
		{
			$_SESSION['error']="sorry, you don't have permession to update this case! if you feel that you are seeing this by mistake please contact the IT deppartment";
			header("Location:index.php");
		}
		else
		{
			$select_stmt = $conn->prepare("UPDATE cases SET discharged='yes' where case_id = :id");
			$select_stmt->execute(array(':id'=>$case_id));		
			$_SESSION['message']="descharged successfuly";
			header("Location:index.php");
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
