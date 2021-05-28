<?php
try{

session_start();

$_SESSION['error']=" this page is not ready yet sorry for the inconveniant";
header("Location:index.php");
}
catch (Exception $ex)
{
	echo ("internal error, please contact support");
	error_log("page_name, SQL error=" . $ex->getMessage());
	return;
}

?>