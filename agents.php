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

// get agents
$stmt = $mysqli->prepare('SELECT * FROM users WHERE manager_user_id = ?');
$stmt->bind_param('i', $_SESSION['user']['id']);
$stmt->execute();
$agents = $stmt->get_result();
foreach ($agents as $key => $agent) {
    // var_dump($agent);
}

// get total number of meets
$totalMeets = 0;
foreach ($agents as $key => $agent) {
    $stmt = $mysqli->prepare('SELECT COUNT(*) AS total FROM meets WHERE user_id = ?');
    $stmt->bind_param('i', $agent['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $total = $result->fetch_assoc();
    $totalMeets += $total['total'];
}

// get all meets 7 last days
$totalMeetsWeek = [];
foreach ($agents as $key => $agent) {
    $stmt = $mysqli->prepare('SELECT * FROM meets WHERE user_id = ? AND start_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)');
    $stmt->bind_param('i', $agent['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $meets7Days = $result->fetch_all(MYSQLI_ASSOC);
    foreach ($meets7Days as $meet) {
        $totalMeetsWeek[] = $meet;
    }
}

// get total number client 
$totalClients = 0;
foreach ($agents as $key => $agent) {
    $stmt = $mysqli->prepare('SELECT COUNT(DISTINCT c.id) AS total FROM clients c INNER JOIN meets m ON c.id = m.client_id WHERE m.user_id = ?');
    $stmt->bind_param('i', $agent['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $total = $result->fetch_assoc();
    $totalClients += $total['total'];
}
?>

<body>
    <div class="d-flex flex-row w-100 vh-100">
        <?php require './components/sidebar.php' ?>
        <main class="w-100 m-5">
            <?php require './components/header.php' ?>

            <?php
            $stmt = $mysqli->prepare('SELECT * FROM users WHERE manager_user_id = ?');
            $stmt->bind_param('i', $_SESSION['user']['id']);
            $stmt->execute();

            $result = $stmt->get_result();
            $verif = $result->fetch_assoc();
            ?>
            <ul class="d-flex list-unstyled nav-agents gap-3">
                <li class="w-25 p-3 text-center rounded-4 colorCard">
                    Vue générale
                </li>
                <?php
                if (isset($result)) {
                    foreach ($result as $key => $agent) { ?>
                        <?php $firstletter = substr($agent['surname'], 0, 1); ?>
                        <li class="w-25 p-3 text-center rounded-4">
                            <?php echo $agent['name'] . "." . $firstletter; ?>
                        </li>
                <?php
                    }
                }
                ?>
            </ul>

            <div class="d-flex flex-row gap-5 containerMain">
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
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($totalMeetsWeek as $meet) {
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
                                            <td><a href="./rdv_client.php?id=<?php echo $meet['id'] ?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                                    </svg></a>
                                            </td>
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
                        <span class="fw-bold fs-1"><?php echo $totalMeets; ?></span>
                        <p class="text-senary">Total de rendez-vous</p>
                    </div>
                    <div class="rounded-3 bg-tertiary p-4 d-flex flex-column gap-2">
                        <h2 class="fs-5 fw-bold">Rendez-vous</h2>
                        <span class="fw-bold fs-1"><?php echo count($totalMeetsWeek); ?></span>
                        <p class="text-senary">7 derniers jours</p>
                    </div>
                    <div class="rounded-3 bg-tertiary p-4 d-flex flex-column gap-2">
                        <h2 class="fs-5 fw-bold">Client</h2>
                        <span class="fw-bold fs-1"><?php echo $totalClients ?></span>
                        <p class="text-senary">Clients vus</p>
                    </div>
                </div>
            </div>
        </main>
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

</body>