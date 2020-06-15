<?php
include('connect.php');
$nodeid = $_GET['repID'];
$search = $nodeid;
?>
<?php
function child_data_count($parentID) {
global $con;
$child_data_count = 0;
$query1 = mysqli_query($con,"select * from mlm_rep where parentID ='".$parentID."'") or die("Error: " . mysqli_error($con));
$child_data_result = mysqli_fetch_array($query1);
$child_data_count = mysqli_num_rows($query1);
return $child_data_count;
}
function child_data($parentID) {
global $con;
$child_data_count = 0;
$query1 = mysqli_query($con,"SELECT a.*, b.name as sponsorName, c.name as parentName FROM mlm_rep a LEFT OUTER JOIN mlm_rep b ON (a.sponsorID = b.recordID) LEFT OUTER JOIN mlm_rep c ON (a.parentID = c.recordID) where a.parentID = '".$parentID."'") or die("Error: " . mysqli_error($con));
$json = mysqli_fetch_all($query1, MYSQLI_ASSOC);
$cdata = json_encode($json);
return $json;
}
function tree_data($nodeid){
global $con;
$data = array();
$query = mysqli_query($con,"select * from mlm_rep where recordID='".$_GET['nodeID']."' and 	repID='".$_GET['repID']."'") or die("Error: " . mysqli_error($con));
$result = mysqli_fetch_array($query);
if($result['leg']) {
$data['left'] = $result['name'];
$data['right'] = '';
$data['leftID'] = $result['repID'];
$data['rightID'] = '';
} else {
$data['right'] = $result['name'];
$data['left'] = '';
$data['rightID'] = $result['repID'];
$data['leftID'] = '';
}
$data['recordID'] = $result['recordID'];
$data['child_data_count'] = child_data_count($result['recordID']);
return $data;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<title>Mlml Website - Tree</title>
<!-- Bootstrap Core CSS -->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- MetisMenu CSS -->
<link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="dist/css/sb-admin-2.css" rel="stylesheet">
<!-- Custom Fonts -->
<link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="wrapper">
<!-- Navigation -->
<!-- Page Content -->
<div id="page-wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-lg-12">
</div>
<!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
<div class="col-lg-12">
<div class="table-responsive">
<table class="table" align="center" border="0" style="text-align:center">
<tr height="150">
<?php
$data = tree_data($search);
?>
<td></td>
<td colspan="2"><i class="fa fa-user fa-4x" style="color:#1430B1"></i><p><?php echo $search; ?></p></td>
<td></td>
</tr>
<?php
if($data['child_data_count']) {
?>
<tr height="150">
<?php
$data1 = [];
$data1 = child_data($data['recordID']);
foreach ($data1 as $key => $value) {
	if($value['leg']) {
		$first_right_user = $value['name'];
		$first_right_user_id = $value['recordID'];
	}
	else {
		$first_left_user = $value['name'];
		$first_left_user_id = $value['recordID'];
	}
}
?>
<?php 
if($first_left_user!=""){
?>
<td colspan="2"><a href="javascript:void(0);"><i class="fa fa-user fa-4x" style="color:#D520BE"></i><p><?php echo $first_left_user ?></p></a></td>
<?php 
}
else{
?>
<td colspan="2"><i class="fa fa-user fa-4x" style="color:#D520BE"></i><p><?php echo $first_left_user ?></p></td>
<?php
}
?>
<?php 
if($first_right_user!=""){
?>
<td colspan="2"><a href="javascript:void(0);"><i class="fa fa-user fa-4x" style="color:#D520BE"></i><p><?php echo $first_right_user ?></p></a></td>
<?php 
}
else{
?>
<td colspan="2"><i class="fa fa-user fa-4x" style="color:#D520BE"></i><p><?php echo $first_right_user ?></p></td>
<?php
}
?>
</tr>
<?php
}
?>

<tr height="150">
<?php 
$data_first_left_user = [];
$data_first_left_user = child_data($first_left_user_id);
foreach ($data_first_left_user as $key => $value) {
	if($value['leg']) {
		$second_left_user = $value['name'];
		$second_left_user_id = $value['recordID'];
	} 
	else {
		$second_right_user = $value['name'];
		$second_right_user_id = $value['recordID'];
	}
}

$data_first_right_user = [];
$data_first_right_user = child_data($first_right_user_id);
foreach ($data_first_right_user as $key => $value) {
	if($value['leg']) {
		$third_left_user = $value['name'];
		$third_left_user_id = $value['recordID'];
	}
	else {
		$thidr_right_user = $value['name'];
		$thidr_right_user_id = $value['recordID'];
	}
}
?>
<?php 
if($second_left_user!=""){
?>
<td><a href="tree.php?search-id=<?php echo $second_left_user ?>"><i class="fa fa-user fa-4x" style="color:#361515"></i><p><?php echo $second_left_user ?></p></a></td>
<?php 
}
else{
?>
<td><i class="fa fa-user fa-4x" style="color:#361515"></i></td>
<?php
}
?>
<?php 
if($second_right_user!=""){
?>
<td><a href="tree.php?search-id=<?php echo $second_right_user ?>"><i class="fa fa-user fa-4x" style="color:#361515"></i><p><?php echo $second_right_user ?></p></a></td>
<?php 
}
else{
?>
<td><i class="fa fa-user fa-4x" style="color:#361515"></i></td>
<?php
}
?>
<?php 
if($third_left_user!=""){
?>
<td><a href="tree.php?search-id=<?php echo $third_left_user ?>"><i class="fa fa-user fa-4x" style="color:#361515"></i><p><?php echo $third_left_user ?></p></a></td>
<?php 
}
else{
?>
<td><i class="fa fa-user fa-4x" style="color:#361515"></i></td>
<?php
}
?>
<?php 
if($thidr_right_user!=""){
?>
<td><a href="tree.php?search-id=<?php echo $thidr_right_user ?>"><i class="fa fa-user fa-4x" style="color:#361515"></i><p><?php echo $thidr_right_user ?></p></a></td>
<?php 
}
else{
?>
<td><i class="fa fa-user fa-4x" style="color:#361515"></i></td>
<?php
}
?>
</tr>

</table>
</div>
</div>
</div>
</div>
<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
<!-- jQuery -->
<script src="vendor/jquery/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="vendor/metisMenu/metisMenu.min.js"></script>
<!-- Custom Theme JavaScript -->
<script src="dist/js/sb-admin-2.js"></script>
</body>
</html>