<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>List Competitions - Thai Football Tournament</title>

    <script
      type="module"
      crossorigin
      src="./assets/compiled/js/bootstrap.esm.js"
    ></script>
    <link rel="stylesheet" href="./assets/compiled/css/bootstrap.css" />
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container">
        <a class="navbar-brand" href="competition-index.php"
          >Thai Football competition</a
        >
      </div>
    </nav>

    <main class="container py-5">
      <header class="mb-4 d-flex align-items-center justify-content-between">
        <h3 class="mb-0">Competition List</h3>
        <a class="btn btn-outline-primary" href="competition-create.html">Create a competition</a>
      </header>

      <section class="competition-list">
        <?php
        include 'db.php';

        $sql = "SELECT competitions.id, competitions.name, competitions.max_teams, COUNT(competition_allowed_provinces.province_id) as province_count
                FROM competitions
                LEFT JOIN competition_allowed_provinces ON competitions.id = competition_allowed_provinces.competition_id
                GROUP BY competitions.id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<a href="competition-detail.php?id=' . $row["id"] . '">';
                echo '<article class="competition-box card mb-3">';
                echo '<div class="card-body">';
                echo '<h4>' . $row["name"] . '</h4>';
                echo '<p class="text-muted mb-0">' . $row["max_teams"] . ' Teams - ' . $row["province_count"] . ' Provinces</p>';
                echo '</div>';
                echo '</article>';
                echo '</a>';
            }
        } else {
            echo '<p>No competitions found.</p>';
        }

        $conn->close();
        ?>
      </section>
    </main>
  </body>
</html>
