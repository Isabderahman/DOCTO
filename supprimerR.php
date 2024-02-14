<?php
    session_start();
    require 'connexion.php';
    if(empty($_SESSION['login']) and empty($_SESSION['userpass'])){
        header("Location: deconnexion.php");}
    if(isset($_GET["num_rdv"])){
        $resu=$conn->prepare("DELETE from rdv where numRDV=:p");
        $resu->bindValue("p",$_GET["num_rdv"]);
        try{
            $resu->execute();
            $message = "supression Reussite";
            header("Location: gestionR.php?message=$message");
        }catch(PDOException $e){
            $message=$e->getMessage();
            header("Location: gestionR.php?message=$message");
        }

    }
