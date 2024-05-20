<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establish connection to the database
    $servername = "localhost"; // Change to your database server name
    $username = "root"; // Change to your database username
    $password = ""; // Change to your database password
    $dbname = "tfc"; // Change to your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve selected teams from the form
    $teams = $_POST['teams'];

    // Insert each team into the database
    foreach ($teams as $team) {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO teams (name) VALUES (?)");
        $stmt->bind_param("s", $team);
        $stmt->execute();
    }

    echo "Teams saved successfully!";

    // Close connection
    $conn->close();
}
?>
