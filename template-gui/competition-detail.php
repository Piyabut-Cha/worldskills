<?php
include('db.php');

// Check if the 'id' parameter is set in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Competition ID is not specified.";
    exit;
}

$competition_id = intval($_GET['id']); // Ensure it's an integer to prevent SQL injection

// Fetch competition details using a prepared statement
$sql = $conn->prepare("SELECT * FROM competitions WHERE id = ?");
if (!$sql) {
    echo "Failed to prepare the SQL statement: (" . $conn->errno . ") " . $conn->error;
    exit;
}
$sql->bind_param("i", $competition_id);
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows > 0) {
    $competition = $result->fetch_assoc();
    $name = $competition['name'];
    $max_teams = $competition['max_teams'];
    $allowed_provinces = isset($competition['allowed_provinces']) ? explode(',', $competition['allowed_provinces']) : [];
    $banner = isset($competition['banner']) ? $competition['banner'] : '';
} else {
    echo "Competition not found.";
    exit;
}

// Fetch registered teams using a prepared statement
$sql_teams = $conn->prepare("SELECT * FROM teams WHERE province_id = ?");
if (!$sql_teams) {
    echo "Failed to prepare the SQL statement for teams: (" . $conn->errno . ") " . $conn->error;
    exit;
}
$sql_teams->bind_param("i", $competition_id);
$sql_teams->execute();
$result_teams = $sql_teams->get_result();
$teams = [];
if ($result_teams->num_rows > 0) {
    while ($row = $result_teams->fetch_assoc()) {
        $teams[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Overview Competition - Thai Football Tournament</title>

  <script type="module" crossorigin src="./assets/compiled/js/bootstrap.esm.js"></script>
  <link rel="stylesheet" href="./assets/compiled/css/bootstrap.css" />
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand" href="competition-index.php">Thai Football Competition</a>
    </div>
  </nav>

  <main class="container py-5">
    <section class="mb-4 d-flex align-items-center justify-content-between">
      <header>
        <h3><?php echo htmlspecialchars($name); ?></h3>
      </header>
      <a class="btn btn-outline-primary" href="team-register.html">Register teams</a>
    </section>

    <div class="bg-light text-center">
      <?php if ($banner): ?>
        <img src="<?php echo htmlspecialchars($banner); ?>" alt="Competition Banner" class="img-fluid" width="100%" height="300">
      <?php else: ?>
        <svg class="bd-placeholder-img bd-placeholder-img-lg img-fluid" width="100%" height="300"
          xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Banner"
          preserveAspectRatio="xMidYMid slice" focusable="false">
          <title>Placeholder</title>
          <rect width="100%" height="100%" fill="#868e96"></rect>
          <text x="50%" y="50%" fill="#dee2e6" dy=".3em">Banner</text>
        </svg>
      <?php endif; ?>
    </div>

    <section class="my-5">
      <header class="text-center mb-3">
        <h5>Competition Info</h5>
      </header>
      <div class="row justify-content-center">
        <div class="col-md-3 col-sm-6">
          <div class="card">
            <div class="card-body text-center py-3">
              <h1><?php echo htmlspecialchars($max_teams); ?></h1>
              <p class="text-muted mb-0">Max Teams</p>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="card">
            <div class="card-body text-center py-3">
              <h1><?php echo count($allowed_provinces); ?></h1>
              <p class="text-muted mb-0">Provinces</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="my-5">
      <header class="text-center">
        <h5>Participated Provinces</h5>
      </header>
      <div class="row justify-content-center">
        <?php foreach ($allowed_provinces as $province): ?>
          <div class="col-md-2 col-sm-6 mt-3">
            <div class="card">
              <div class="card-body text-center py-3">
                <span class="text-muted"><?php echo htmlspecialchars($province); ?></span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="my-5">
      <header class="text-center">
        <h5>Registered Teams</h5>
      </header>
      <div>
        <div class="row">
          <?php foreach ($teams as $team): ?>
            <div class="col-md-3 mt-3">
              <div class="card">
                <div class="card-body text-center">
                  <img src="<?php echo htmlspecialchars($team['logo']); ?>" alt="logo" class="mb-2" height="50" />
                  <div><?php echo htmlspecialchars($team['name']); ?></div>
                  <div class="text-muted"><?php echo htmlspecialchars($team['province']); ?></div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  </main>
</body>

</html>
