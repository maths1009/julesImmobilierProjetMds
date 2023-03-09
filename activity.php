<?php
include './components/head.php';
require_once './config.php';

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$mysqli->set_charset("utf8mb4");

if (!$mysqli) {
    die("Connection failed: " . mysqli_connect_error());
}

// get user role
$stmt = $mysqli->prepare('SELECT * FROM roles WHERE id = ?');
$stmt->bind_param('i', $_SESSION['user']['role_id']);
$stmt->execute();
$result = $stmt->get_result();
$role = $result->fetch_assoc();

// get meet
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $masque = "/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4}$/";

    if (isset($_POST['client_lastname'], $_POST['client_firstname'], $_POST['client_addr'], $_POST['time_start'], $_POST['time_finish']) && preg_match($masque, $_POST['client_email'])) {

        $stmt = $mysqli->prepare('SELECT * FROM clients WHERE email = ?');
        $stmt->bind_param('s', $_POST["client_email"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $client = $result->fetch_assoc();

        if (isset($client)) {
            $_SESSION['status'] = "Le rendez-vous à bien été ajouté !";
        } else {
            $stmt = $mysqli->prepare('INSERT INTO clients (name, surname, email) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $_POST["client_firstname"], $_POST["client_lastname"], $_POST["client_email"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $_SESSION['status'] = "Le client a été créé et le rendez-vous à bien été ajouté !";
        }

        $stmt = $mysqli->prepare('SELECT * FROM clients WHERE email = ?');
        $stmt->bind_param('s', $_POST["client_email"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $client = $result->fetch_assoc();

        $stmt = $mysqli->prepare('INSERT INTO meets (user_id, client_id, adresse, start_date, end_date, comment) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('iissss', $_SESSION['user']['id'], $client['id'], $_POST['client_addr'], $_POST['time_start'], $_POST['time_finish'], $_POST['commentaires']);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $_SESSION['error'] = "Merci de remplir tout les champs";
    }
}

$mysqli->close();
?>

<body>
    <div class="d-flex flex-row w-100 vh-100">
        <?php require './components/sidebar.php'; ?>
        <main class="w-100 m-5">
            <?php include './components/header.php'; ?>
            <?php if (isset($_SESSION['error'])) { ?>
                <div class="alert alert-danger position-fixed start-50 translate-middle-x mt-5" role="alert">
                    <?php echo ($_SESSION['error']); ?>
                </div>
            <?php unset($_SESSION['error']);
            } ?>
            <?php if (isset($_SESSION['status'])) { ?>
                <div class="alert alert-success position-fixed start-50 translate-middle-x mt-5" role="alert">
                    <?php echo ($_SESSION['status']); ?>
                </div>
            <?php unset($_SESSION['status']);
            } ?>
            <form class="container__suivi" method="POST">
                <div class="title">
                    <h1>Suivi des agents immobiliers</h1>
                    <h3><?php echo ($role['name']) ?></h3>
                </div>

                <div class="content__card content__client rounded-3">
                    <h2>Rendez-vous client :</h2>
                    <span class="client_fullname">
                        <input type="text" id="client_lastname" class="form-control" name="client_lastname" required placeholder="Nom du client">
                        <input type="text" id="client_firstname" class="form-control" name="client_firstname" required placeholder="Prénom du client">
                    </span>
                    <span class="client_addr">
                        <input type="text" id="client_addr" class="form-control" name="client_addr" require placeholder="Adresse du rendez-vous">
                        <input type="email" id="client_email" class="form-control" name="client_email" require placeholder="Email">
                    </span>
                    <span class="heure_rdv">
                        <input type="text" class="form-control" name="time_start" id="time_start" placeholder="Date de début" onfocus="(this.type='datetime-local')" required />
                        <input type="text" class="form-control" name="time_finish" id="time_finish" placeholder="Date de fin" onfocus="(this.type='datetime-local')" required />
                    </span>
                    <span class="client_com">
                        <textarea id="commentaires" class="form-control" name="commentaires" placeholder="Commentaires sur le rendez-vous"></textarea>
                    </span>
                </div>

                <div class="content__send">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">Envoyer le suivi</button>
                </div>
            </form>
        </main>
    </div>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>