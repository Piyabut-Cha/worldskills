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
        $stmt->close();
    }

    echo "Teams saved successfully!";

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Create Competition - Thai Football Tournament</title>

    <script type="module" crossorigin src="./assets/compiled/js/bootstrap.esm.js"></script>
    <link rel="stylesheet" href="./assets/compiled/css/bootstrap.css" />
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="competition-index.html">Thai Football competition</a>
        </div>
    </nav>

    <main class="container py-5">
        <header class="mb-4 d-flex align-items-center justify-content-between">
            <h3 class="mb-0">Register teams</h3>
        </header>

        <section class="form">
            <div class="row">
                <div class="col-md-4">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="teams">Teams</label>
                                    <select multiple name="teams[]" id="teams" class="form-control">
                                        <option>Nong Bua Pitchaya FC</option>
                                        <option>Buriram United FC</option>
                                        <option>Nakhon Ratchasima FC</option>
                                        <option>Port FC</option>
                                        <option>Bangkok Glass FC</option>
                                        <option>Bangkok United FC</option>
                                        <option>Khon Kaen United FC</option>
                                        <option>Lampang FC</option>
                                        <option>BEC Tero Sasana FC</option>
                                        <option>Sukhothai FC</option>
                                        <option>Prachuap FC</option>
                                        <option>Chonburi FC</option>
                                        <option>Lamphun Warrior FC</option>
                                        <option>Ratchaburi Mitr Phol FC</option>
                                        <option>Chiangrai United FC</option>
                                        <option>Muang Thong United</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-8">
                                <button class="btn btn-primary w-100" type="submit">Save</button>
                            </div>
                            <div class="col-4">
                                <a href="competition-detail.html" class="btn bg-light text-primary w-100" type="submit">Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
