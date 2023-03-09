<?php
require_once './config.php';
require_once './classes/dbGestion.php';
include_once './components/head.php';

$sql = new dbGestion();
$id = $_SESSION['user']['id'];

// get user role
$role =  $sql->getRoleById($id);

// get total number of meets
$totalMeets =
  $_SESSION['user']['role_id'] == 1 ||  $_SESSION['user']['role_id'] == 2 ? $sql->selectAll('meets') : $sql->getMeetsUserById($id);

// get all meets 7 last days
$meets7Days =
  $_SESSION['user']['role_id'] == 1 ||  $_SESSION['user']['role_id'] == 2 ? $sql->selectAll('meets') : $sql->getMeetRecentById($id);

// get total number client 
$totalClients =
  $_SESSION['user']['role_id'] == 1 ||  $_SESSION['user']['role_id'] == 2 ? $sql->selectAll('clients') : $sql->getClientByIdUser($id);
?>

<body>
  <div class="d-flex flex-row w-100 vh-100">
    <?php require './components/sidebar.php' ?>
    <div class="w-100 m-5">
      <?php require './components/header.php' ?>
      <?php if (isset($_SESSION['status'])) { ?>
        <div class="alert alert-success position-fixed start-50 translate-middle-x mt-5 " role="alert">
          <?php echo ($_SESSION['status']); ?>
        </div>
      <?php
        unset($_SESSION['status']);
      } ?>
      <div class="d-flex flex-column">
        <h2 class="d-flex flex-row gap-3 fs-1 text-primary">
          <p class="fw-bold">Bienvenue,</p>
          <p><?php echo $_SESSION['user']["name"]; ?></p>
        </h2>
        <p class="text-senary"><?php echo $role['name']; ?></p>
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
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($meets7Days as $meet) {
                    $result = $sql->getClientById($meet['client_id']);
                    $meet['client_name'] = $result['name'];
                    $meet['duration'] = date_diff(date_create($meet['start_date']), date_create($meet['end_date']))->format('%H:%I');
                  ?>
                    <tr>
                      <td><?php echo $meet['client_name']; ?></td>
                      <td><?php echo strftime('%A %e %B %Y', strtotime($meet['start_date'])); ?></td>
                      <td><?php echo $meet['adresse']; ?></td>
                      <td><?php echo $meet['duration']; ?></td>
                      <td><a href="./rdv_client.php?id=<?php echo $meet['id'] ?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                          </svg></a>
                      </td>
                    </tr>
                  <?php };
                  $sql->disconnect($sql->mysqli);
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-2 d-flex flex-column justify-content-between">
          <div class="rounded-3 bg-tertiary p-4 d-flex flex-column gap-2">
            <h2 class="fs-5 fw-bold">Total</h2>
            <span class="fw-bold fs-1"><?php echo count($totalMeets); ?></span>
            <p class="text-senary">Total de rendez-vous</p>
          </div>
          <div class="rounded-3 bg-tertiary p-4 d-flex flex-column gap-2">
            <h2 class="fs-5 fw-bold">Rendez-vous</h2>
            <span class="fw-bold fs-1"><?php echo count($meets7Days); ?></span>
            <p class="text-senary">7 derniers jours</p>
          </div>
          <div class="rounded-3 bg-tertiary p-4 d-flex flex-column gap-2">
            <h2 class="fs-5 fw-bold">Client</h2>
            <span class="fw-bold fs-1"><?php echo count($totalClients); ?></span>
            <p class="text-senary">Clients vus</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="module" src="./assets/js/index.js"></script>
</body>

</html>