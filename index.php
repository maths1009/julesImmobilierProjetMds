<?php
include './components/head.php';
require_once './config.php';

$mysqli = new mysqli("localhost", "root", "root", "julesimmo");

if (!$mysqli) {
  die("Connection failed: " . mysqli_connect_error());
}

// get user role
$stmt = $mysqli->prepare('SELECT * FROM roles WHERE id = ?');
$stmt->bind_param('i', $_SESSION['user']['role_id']);
$stmt->execute();
$result = $stmt->get_result();
$role = $result->fetch_assoc();

// get total number of meets
$stmt = $mysqli->prepare('SELECT COUNT(*) AS total FROM meets WHERE user_id = ?');
$stmt->bind_param('i', $_SESSION['user']['id']);
$stmt->execute();
$result = $stmt->get_result();
$totalMeets = $result->fetch_assoc();

// get all meets 7 last days
$stmt = $mysqli->prepare('SELECT * FROM meets WHERE user_id = ? AND start_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)');
$stmt->bind_param('i', $_SESSION['user']['id']);
$stmt->execute();
$result = $stmt->get_result();
$meets7Days = $result->fetch_all(MYSQLI_ASSOC);


// get total number client 
$stmt = $mysqli->prepare('SELECT COUNT(DISTINCT c.id) AS total FROM clients c INNER JOIN meets m ON c.id = m.client_id WHERE m.user_id = ?');
$stmt->bind_param('i', $_SESSION['user']['id']);
$stmt->execute();
$result = $stmt->get_result();
$totalClients = $result->fetch_assoc();
?>

<body>
  <div class="d-flex flex-row w-100 vh-100">
    <?php require './components/sidebar.php' ?>
    <div class="w-100 m-5">
      <?php require './components/header.php' ?>
      <div class="d-flex flex-column">
        <h2 class="d-flex flex-row gap-3 fs-1 text-primary">
          <p class="fw-bold">Bienvenue,</p>
          <p><?php echo utf8_encode($_SESSION['user']["name"]); ?></p>
        </h2>
        <p class="text-senary"><?php echo utf8_encode($role['name']); ?></p>
      </div>
      <div class="d-flex flex-row gap-5 containerMain pt-4">
        <div class=" w-100">
          <div class="d-flex flex-column gap-5  w-100 h-100">
            <div class="p-4 bg-tertiary rounded-3 h-50">
              <div class="d-flex flex-row"></div>
              <canvas id="myEvolution" style="height: 100%; width: 100%;"></canvas>
            </div>
            <div class="p-4 bg-tertiary rounded-3 d-flex flex-column g-3 h-50 gap-4 overflow-auto">
              <h2 class="fs-5 fw-bold">Rendez-vous récents</h2>
              <table>
                <thead>
                  <tr>
                    <th>Nom client</th>
                    <th>Date</th>
                    <th>Adresse</th>
                    <th>Durée</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($meets7Days as $meet) {
                    $stmt = $mysqli->prepare('SELECT * FROM clients WHERE id = ?');
                    $stmt->bind_param('i', $meet['client_id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $meet['client_name'] = $result->fetch_assoc()['name'];
                    $meet['duration'] = date_diff(date_create($meet['start_date']), date_create($meet['end_date']))->format('%H:%I');
                  ?>
                    <tr>
                      <td><?php echo utf8_encode($meet['client_name']); ?></td>
                      <td><?php echo strftime('%A %e %B %Y', strtotime($meet['start_date'])); ?></td>
                      <td><?php echo utf8_encode($meet['adresse']); ?></td>
                      <td><?php echo $meet['duration']; ?></td>
                    </tr>
                  <?php };
                  $mysqli->close();
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-2 d-flex flex-column justify-content-between">
          <div class="rounded-3 bg-tertiary p-4 d-flex flex-column gap-2">
            <h2 class="fs-5 fw-bold">Total</h2>
            <span class="fw-bold fs-1"><?php echo $totalMeets['total']; ?></span>
            <p class="text-senary">Total de rendez-vous</p>
          </div>
          <div class="rounded-3 bg-tertiary p-4 d-flex flex-column gap-2">
            <h2 class="fs-5 fw-bold">Rendez-vous</h2>
            <span class="fw-bold fs-1"><?php echo count($meets7Days); ?></span>
            <p class="text-senary">7 derniers jours</p>
          </div>
          <div class="rounded-3 bg-tertiary p-4 d-flex flex-column gap-2">
            <h2 class="fs-5 fw-bold">Client</h2>
            <span class="fw-bold fs-1"><?php echo $totalClients['total'] ?></span>
            <p class="text-senary">Clients vus</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    fetch('./requests/getMeets.php')
      .then(function(response) {
        return response.json();
      })
      .then(function(data) {
        console.log(data); // Utilisation des données dans votre application web
      })
      .catch(function(error) {
        console.log(error); // Gestion des erreurs éventuelles
      });

    // Données de l'exemple
    const data = {
      thisYears: {
        "lundi": 5,
        "mardi": 3,
        "mercredi": 7,
        "jeudi": 2,
        "vendredi": 4,
        "samedi": 1,
        "dimanche": 0
      },
      lastYears: {
        "lundi": 3,
        "mardi": 2,
        "mercredi": 4,
        "jeudi": 1,
        "vendredi": 2,
        "samedi": 0,
        "dimanche": 0
      }
    };

    const ctx = document.getElementById('myEvolution').getContext('2d');

    const myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: Object.keys(data.thisYears),
        datasets: [{
            pointRadius: 0,
            lineTension: 0.3,
            borderColor: "#ffc72c",
            label: 'Cette année',
            data: Object.values(data.thisYears),
          },
          {
            pointRadius: 0,
            lineTension: 0.3,
            borderWidth: 1,
            borderColor: "#004f71",
            label: 'L\'année dernière',
            data: Object.values(data.lastYears),
          }
        ]
      },
      options: {
        scales: {
          x: {
            grid: {
              display: false
            }
          },
        },
        plugins: {
          legend: {
            display: true,
          },
        }
      }
    });
  </script>
</body>

</html>