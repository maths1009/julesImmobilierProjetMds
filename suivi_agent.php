<?php
include './components/head.php';
?>

<body>
  <div class="d-flex flex-row w-100 vh-100">
    <?php require './components/sidebar.php'; ?>
    <main class="w-100">
        <?php include './components/header.php'; ?>  
        <section class="container container__suivi">
            <div class="title">
                <h1>Suivi des agents immobiliers</h1>
                <h3>Agent immobilier</h3>
            </div>

            <div class="content__card content__agent">
                <h2>Agent immobilier :</h2>
                <span class="agent_fullname">
                    <input type="text" id="agent_lastname" name="agent_lastname" required placeholder="Nom">
                    <input type="text" id="agent_firstname" name="agent_firstname" required placeholder="Prénom">
                </span>
                <span class="date_rdv">
                    <input type="date" id="date" name="date" required>
                    <input type="number" id="nbclients" name="nbclients" required min="0" placeholder="Nombre de clients vus">
                </span>
            </div>

            <div class="content__card content__client">
                <h2>Agent immobilier :</h2>
                <span class="client_fullname">
                    <input type="text" id="client_lastname" name="client_lastname" required placeholder="Nom du client">
                    <input type="text" id="client_firstname" name="client_firstname" required placeholder="Prénom du client">
                </span>
                <span class="client_addr">
                    <input type="text" id="client_addr" name="client_addr" require placeholder="Adresse du rendez-vous">
                </span>
                <span class="heure_rdv">
                    <input type="datetime-local" id="time_start" name="time_start" required>
                    <input type="datetime-local" id="time_finish" name="time_finish" required>
                </span>
                <span class="client_com">
                    <textarea id="commentaires" name="commentaires">Commentaires sur le rendez-vous</textarea>
                </span>
            </div>

            <div class="content__send">
                <button class="btn btn-primary btn-lg btn-block" type="submit">Envoyer le suivi</button>
            </div>
        </section>
    </main>
  </div>
</body>

</html>