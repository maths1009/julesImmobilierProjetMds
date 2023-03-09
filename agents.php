<?php
require_once './config.php';
require_once  './classes/dbGestion.php';
include_once './components/head.php';

$mysqli = new dbGestion();
$id = $_SESSION['user']['id'];

// get user role
$role = $mysqli->getRoleById($id);

// get agents
$agents = $mysqli->getAgentsById($id);

// get total number of meets
$totalMeets = 0;
foreach ($agents as $key => $agent) {
    $total = $mysqli->getMeetsById($agent['id']);
    $totalMeets += count($total);
}

// get all meets 7 last days
$totalMeetsWeek = [];
foreach ($agents as $key => $agent) {
    $meets7Days = $mysqli->getMeetRecentById($agent['id']);
    foreach ($meets7Days as $meet) {
        $totalMeetsWeek[] = $meet;
    }
}

// get total number client 
$totalClients = 0;
foreach ($agents as $key => $agent) {
    $total = $mysqli->getClientByIdUser($agent['id']);
    $totalClients += count($total);
}
?>

<body>
    <div class="d-flex flex-row w-100 vh-100">
        <?php require './components/sidebar.php' ?>
        <main class="w-100 m-5">
            <?php require './components/header.php' ?>

            <?php
            $result = $mysqli->getAgentsById($id);
            ?>
            <ul class="d-flex list-unstyled nav-agents gap-3">
                <li class="w-25 p-3 text-center rounded-4">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($totalMeetsWeek as $meet) {
                                        $result = $mysqli->getClientById($meet['client_id']);
                                        $meet['client_name'] = $result['name'];
                                        $meet['duration'] = date_diff(date_create($meet['start_date']), date_create($meet['end_date']))->format('%H:%I');
                                    ?>
                                        <tr>
                                            <td><?php echo $meet['client_name']; ?></td>
                                            <td><?php echo strftime('%A %e %B %Y', strtotime($meet['start_date'])); ?></td>
                                            <td><?php echo $meet['adresse']; ?></td>
                                            <td><?php echo $meet['duration']; ?></td>
                                        </tr>
                                    <?php };
                                    $mysqli->disconnect($mysqli->mysqli);
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
    <script type="module" src="./assets/js/agents.js"></script>
</body>

</html>

</body>