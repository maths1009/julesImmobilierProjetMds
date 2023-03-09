<?php
include_once './components/head.php';
require_once './classes/dbGestion.php';
require_once './config.php';

$mysqli = new dbGestion("meets");

// get meet
$meet = $mysqli->getMeetById($_GET['id']);


// get client
$client = $mysqli->getClientById($meet['client_id']);

// get agent
$agent = $mysqli->getUserById($meet['user_id']);

// get role
$role = $mysqli->getRoleById($agent['role_id']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_meet'])) {

    $mysqli->delete($_GET['id']);
    $_SESSION['status'] = "Le rendez-vous a bien été supprimé";
    header('Location: ' . SITE_ROOT . 'index.php');
}

?>

<body>
    <div class="d-flex flex-row w-100 vh-100">
        <?php require './components/sidebar.php'; ?>
        <main class="w-100 m-5">
            <?php include './components/header.php'; ?>
            <section class="container__suivi container__rdv">
                <div class="title">
                    <h1>Rendez-vous avec <?php echo $client['name'] . " " .  $client['surname'] ?></h1>
                    <h3><?php echo $role['name'] ?></h3>
                </div>

                <div class="content__card content__agent rounded-3">
                    <h2>Agent immobilier :</h2>
                    <span class="agent">
                        <div class="name">
                            <p class="fw-bold">Nom de l'agent : </p>
                            <p>&nbsp;<?php echo $agent['name'] . " " . $agent['surname'] ?></p>
                        </div>
                        <div class="rdv_date">
                            <p class="fw-bold">Date du rendez-vous : </p>
                            <p>&nbsp;<?php echo date("d-m-Y", strtotime($meet['start_date'])) ?></p>
                        </div>
                    </span>
                </div>

                <div class="content__card content__client rounded-3">
                    <h2>Rendez-vous client :</h2>
                    <span class="client">
                        <div class="name">
                            <p class="fw-bold">Nom du client : </p>
                            <p>&nbsp;<?php echo $client['name'] . " " .  $client['surname'] ?></p>
                        </div>
                        <div class="rdv_lieu">
                            <p class="fw-bold">Adresse : </p>
                            <p>&nbsp;<?php echo $meet['adresse'] ?></p>
                        </div>
                    </span>
                    <span class="rdv">
                        <div class="heures">
                            <p class="fw-bold">Heures du rendez-vous : </p>
                            <p>&nbsp;<?php echo date("H:i:s", strtotime($meet['start_date'])) . " - " . date("H:i:s", strtotime($meet['end_date'])) ?></p>
                        </div>
                        <div class="durée">
                            <p class="fw-bold">Durée du rendez-vous : </p>
                            <p>&nbsp;
                                <?php
                                $start_date = strtotime($meet['start_date']);
                                $end_date = strtotime($meet['end_date']);
                                $diff = $end_date - $start_date;

                                $hours = floor($diff / 3600);
                                $minutes = floor(($diff % 3600) / 60);
                                $seconds = $diff % 60;

                                echo  $hours . "h " . $minutes . "min ";
                                ?>
                            </p>
                        </div>
                    </span>
                    <h2>Commentaires :</h2>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $meet['comment'] ?></p>
                </div>
                <button id="delete_btn" class="btn btn-delete btn-lg btn-block">Supprimer</button>
                <form class="modal" method="POST">
                    <h3>Êtes-vous sûr de vouloir supprimer ce rendez-vous ?</h3>
                    <input type="hidden" name="id_meet" id="id_meet" value="<?php echo $meet['id'] ?>">
                    <div>
                        <button id="delete_meet" class="btn btn-primary btn-lg btn-block" type="submit" name="delete_meet">Oui</button>
                        <button id="annule_delete" class="btn btn-primary btn-lg btn-block">Non</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
    <script src="./assets/js/modal.js"></script>
</body>

</html>