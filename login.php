<?php
require_once "./config.php";
require_once "./classes/dbGestion.php";

$connect = new dbGestion();

// Get user connect
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $verif = $connect->login();
    $connect->disconnect($connect->mysqli);
    if (isset($verif)) {
        $_SESSION['user'] = $verif;
        header('Location: ' . SITE_ROOT . 'index.php');
    } else {
        $_SESSION['status'] = "L'email ou le mot de passe est invalide !";
    }
}

include_once './components/head.php';
?>

<body>
    <main class="bg-login">
        <form class="vh-100" action="" method="POST">

            <?php if (isset($_SESSION['status'])) { ?>
                <div class="alert alert-danger position-fixed start-50 translate-middle-x mt-5" role="alert">
                    <?php echo ($_SESSION['status']); ?>
                </div>
            <?php unset($_SESSION['status']);
            } ?>

            <div class="container h-100 primary">
                <div class="row d-flex justify-content-center align-items-center h-100 flex-column gap-5">
                    <img class="mw-30" src="./assets/img/logo.svg" alt="">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card shadow-2-strong" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">

                                <h3 class="mb-5 fs-2">Bienvenue chez Jules Immobilier</h3>

                                <div class="form-outline mb-4 d-flex flex-column align-items-start fs-4">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control form-control-lg" />
                                </div>

                                <div class="form-outline mb-4 d-flex flex-column align-items-start fs-4">
                                    <label class="form-label" for="password">Mot de passe</label>
                                    <input type="password" name="password" id="password" class="form-control form-control-lg" />
                                </div>

                                <button class="btn btn-primary btn-lg btn-block" type="submit" name="login">connexion</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
</body>