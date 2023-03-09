<?php
include_once './components/head.php';
?>

<body>
    <div class="d-flex flex-row w-100 vh-100">
        <?php require './components/sidebar.php'; ?>
        <main class="w-100 p">
            <?php include './components/header.php'; ?>
            <section class="container container__suivi container__rdv">
                <div class="title">
                    <h1>Rendez-vous avec madame AHOUIOUIOUI</h1>
                    <h3>Agent immobilier</h3>
                </div>

                <div class="content__card content__agent rounded-3">
                    <h2>Agent immobilier :</h2>
                    <span class="agent">
                        <div class="name">
                            <p class="fw-bold">Nom de l'agent : </p>
                            <p>&nbsp;Stéphane PLAZA</p>
                        </div>
                        <div class="rdv_date">
                            <p class="fw-bold">Date du rendez-vous : </p>
                            <p>&nbsp;02/03/2023</p>
                        </div>
                    </span>
                </div>

                <div class="content__card content__client rounded-3">
                    <h2>Rendez-vous client :</h2>
                    <span class="client">
                        <div class="name">
                            <p class="fw-bold">Nom du client : </p>
                            <p>&nbsp;Alicia AHOUIOUIOUI</p>
                        </div>
                        <div class="rdv_lieu">
                            <p class="fw-bold">Adresse : </p>
                            <p>&nbsp;51 rue de la soif, LA POUEZE 41664</p>
                        </div>
                    </span>
                    <span class="rdv">
                        <div class="heures">
                            <p class="fw-bold">Heures du rendez-vous : </p>
                            <p>&nbsp;15h30 - 16h30</p>
                        </div>
                        <div class="durée">
                            <p class="fw-bold">Durée du rendez-vous : </p>
                            <p>&nbsp;1 heure</p>
                        </div>
                    </span>
                    <h2>Commentaires :</h2>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lors du rendez-vous, vous avez accueilli Madame Ahouiouioui et vous-même dans votre agence immobilière pour discuter de l'appartement situé au 25 rue de la Roue. Vous avez présenté la propriété et expliqué les caractéristiques, les avantages et les inconvénients de la propriété, y compris le nombre de chambres, la taille, les commodités, l'emplacement et le prix. Madame Ahouiouioui a posé des questions sur la propriété, telles que les coûts de l'entretien, l'état de l'immeuble, la durée du contrat de bail et les frais de copropriété.</p>

                </div>
            </section>
        </main>
    </div>
</body>

</html>