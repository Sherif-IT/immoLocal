<?php
    $id = $_POST['id'];
    $bdd = new PDO('mysql:host=127.0.0.1; dbname=gesapp', 'root', '');
    $reponse = $bdd->query('SELECT * FROM appartement WHERE id = '.$id.'');
    $donnees = $reponse->fetch();
    echo json_encode($donnees);
?>