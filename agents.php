<?php
include_once("./config.php");
include './components/head.php';
// include_once("../classes/dbGestion.php");
// $connect = new dbGestion("julesimmo", "users");
$mysqli = new mysqli("localhost", "root", "root", "julesimmo");
?>

<body class="d-flex flex-row">
    <?php
    include './components/sidebar.php';
    if ($_SESSION['user']['role_id'] == 1) {

        $stmt = $mysqli->prepare('SELECT * FROM users WHERE manager_user_id = ?');
        $stmt->bind_param('i', $_SESSION['user']['id']);
        $stmt->execute();

        $result = $stmt->get_result();
        $verif = $result->fetch_assoc();
    ?>
        <div>
            <?php
            if (isset($result)) {
                foreach ($result as $key => $agent) { ?>
                    <?php $firstletter = substr($agent['surname'], 0, 1); ?>
                    <div class="card_name">
                        <?php echo $agent['name'] . "." . $firstletter; ?>
                    </div>
            <?php
                }
            }
            ?>
        </div>

    <?php
    }
    ?>

</body>

</html>