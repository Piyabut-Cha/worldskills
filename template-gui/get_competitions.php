<?php
include 'db.php';

$sql = "SELECT * FROM competitions";
$result = $conn->query($sql);

$competitions = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $competitions[] = $row;
    }
}
$conn->close();

echo json_encode($competitions);
?>
