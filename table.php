<!DOCTYPE html>
<html>
<head>
<title>Table with database</title>
<style>
table {
border-collapse: collapse;
width: 100%;
color: #588c7e;
font-family: monospace;
font-size: 25px;
text-align: left;
}
th {
background-color: #588c7e;
color: white;
}
tr:nth-child(even) {background-color: #f2f2f2}
</style>
</head>
<body>
<table>
<tr>
<th>Record ID</th>
<th>Parent Name</th>
<th>Sponsor By</th>
<th>Node Side</th>
<th>Representative ID</th>
<th>Node Name</th>
<th>Tree</th>
</tr>
<?php
$conn = mysqli_connect("localhost", "root", "", "mlmbinary");
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT a.*, b.name as sponsorName, c.name as parentName FROM mlm_rep a LEFT OUTER JOIN mlm_rep b ON (a.sponsorID = b.recordID) LEFT OUTER JOIN mlm_rep c ON (a.parentID = c.recordID)";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
// output data of each row
while($row = $result->fetch_assoc()) {
echo "<tr>
	<td>" . $row["recordID"]. "</td>
	<td>" . $row["parentName"] . "</td>
	<td>" . $row["sponsorName"] . "</td>
	<td>". ($row["leg"] ? "Left Node" : "Right Node"). "</td>
	<td>". $row["repID"]. "</td>
	<td>". $row["name"]. "</td>
	<td><a href='tree.php?nodeID=".$row["recordID"]."&repID=".$row["repID"]."'>Tree</a></td>
	</tr>";
}
echo "</table>";
} else { echo "0 results"; }
$conn->close();
?>
</table>
</body>
</html>