<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $max_teams = $_POST['max_teams'];
    $allowed_provinces = $_POST['allowed_Provinces'];  // This is an array

    // Validate form data
    if (empty($name) || empty($slug) || empty($max_teams) || empty($allowed_provinces)) {
        die("Please fill all the required fields.");
    }

    // Insert competition data
    $stmt = $conn->prepare("INSERT INTO competitions (name, slug, max_teams, group_count) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $name, $slug, $max_teams, $max_teams);
    $stmt->execute();
    $competition_id = $stmt->insert_id;
    $stmt->close();

    // Insert allowed provinces data
    foreach ($allowed_provinces as $province) {
        // Fetch the province ID
        $stmt = $conn->prepare("SELECT id FROM provinces WHERE name = ?");
        $stmt->bind_param("s", $province);
        $stmt->execute();
        $stmt->bind_result($province_id);
        $stmt->fetch();
        $stmt->close();

        // Insert into competition_allowed_provinces
        $stmt = $conn->prepare("INSERT INTO competition_allowed_provinces (competition_id, province_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $competition_id, $province_id);
        $stmt->execute();
        $stmt->close();
    }

    // Handle file upload
    if (isset($_FILES['banner']) && $_FILES['banner']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $uploaded_file = $upload_dir . basename($_FILES['banner']['name']);
        move_uploaded_file($_FILES['banner']['tmp_name'], $uploaded_file);
    }

    // Redirect to competition detail page or index page
    header("Location: competition-index.html");
    exit();
}
?>
