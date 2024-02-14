<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rdv</title>
</head>
<body>
<div>
<?php
session_start();
require('connexion.php');
if (empty($_SESSION['login']) and empty($_SESSION['userpass'])) {
    header("Location: deconnexion.php");
}
if(isset($_GET['message'])){
    echo($_GET['message']);
}
$query= "SELECT * from rdv";
$resultat = $conn->prepare($query);
try{
   if($resultat->execute()){
    echo "<table><tr><th>NUMRDV</th><th>dateRDV</th><th>heureRDV</th><th>CodePatient</th><th>CodeMedcin</th><th>Action</th></tr>";
    while($row = $resultat->fetch(PDO::FETCH_ASSOC)){
        echo '<tr>
        <td>' . $row['numRDV'] . '</td>
        <td>' . $row['dateRDV'] . '</td>
        <td>' . $row['heurRDV'] . '</td>
        <td>' . $row['codePatient'] . '</td>
        <td>' . $row['codeMedecin'] . '</td>
        <td>
            <a id="supp" href="supprimerR.php?num_rdv=' . $row['numRDV'] . '" onclick="return confirm(\'Vous êtes sûr que vous voulez supprimer?\')">Supprimer</a>
            <a href="modifierR.php?num_rdv=' . $row['numRDV'] . '">Modifier</a>
        </td>
    </tr>';

    }
    echo "</table>";
 }
    
}catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<button onclick="redirect('medecin.php')">Médecin</button>
<button onclick="redirect('patient.php')">Patient</button>
</div>
<div>
    <h3>Ajouter un RDV</h3>
    <form action="" method="post">
        <table>
            <tr>
                <td>codeRDV :</td>
                <td><input type="text" name="numRDV" id="numRdv"></td>
            </tr>
            <tr>
                <td>heure RDV :</td>
                <td><input type="time" name="heurrdv"></td>
            </tr>
            <tr>
                <td>DateRdv :</td>
                <td><input type="date" name="daterdv" ></td>
            </tr>
            <tr>
                <td>codePatient :</td>
                <td><input type="text" name="codePatient" ></td>
            </tr>
            <tr>
            <td>code Medecin : </td>
                <td>
                    <select name="codeMedecin" id="CM" name="codeMedcin" >
                        <?php 
                            $res=$conn->query("SELECT codeMedecin from medecin");
                            $res->execute();
                            while($coll=$res->fetch(PDO::FETCH_ASSOC)){
                                echo "<option ";
                                echo ">".$coll['codeMedecin']."</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><input type="submit" value="AJOUTER RDV" name="submit"></td>
            </tr>
        </table>
    </form>
    <?php
        if (isset($_POST["submit"])) {
            $rtest=$conn->prepare("select codePatient from patient where codePatient=?");
            $rtest->execute([$_POST["codePatient"]]);
            $test = $rtest->fetch(PDO::FETCH_ASSOC);
            if(!empty($test['codePatient'])){
                $query = "INSERT INTO rdv (numRDV, heurRDV, dateRDV, codePatient, codeMedecin) VALUES (?, ?, ?, ?, ?)";
                $resAjouter = $conn->prepare($query);
                
                try {
                    $resAjouter->execute([
                        $_POST['numRDV'],
                        $_POST['heurrdv'],
                        $_POST['daterdv'],
                        $_POST['codePatient'],
                        $_POST['codeMedecin']
                    ]);
                    header("Location:gestionR.php");
                } catch (PDOException $e) {
                    echo "L'insertion a rencontré un problème : " . $e->getMessage();
                }
            }else{
                echo "<h3> ce patient n'excist pas ! </h3>";
                echo '<form action="" method="post">
                <table>
                    <tr>
                        <td>codePatient :</td>
                        <td><input type="text" name="codePatient2" ></td>
                    </tr>
                    <tr>
                        <td>nom patient :</td>
                        <td><input type="text" name="nompatient"></td>
                    </tr>
                    <tr>
                        <td>adresse Patient :</td>
                        <td><input type="text" name="adressePatient" ></td>
                    </tr>
                    <tr>
                        <td>date Naissance :</td>
                        <td><input type="date" name="datenais" ></td>
                    </tr>
                    <tr>
                        <td>sexe :</td>
                        <td>homme : <input type="radio" name="sexe" value="homme">
                         femme :<input type="radio" name="sexe" value="femme"></td> 
                    </tr>
                    <tr>
                        <td><input type="submit" value="ajouter Patient" name="AJP"></td>
                    </tr>
                </table>
            </form>';
            }
        }
        if(isset($_POST['AJP'])){
            $resultAjo=$conn->prepare('INSERT into patient values(?,?,?,?,?)');
            try{
                $resultAjo->execute([$_POST["codePatient2"],$_POST["nompatient"],$_POST['adressePatient'],$_POST["datenais"],$_POST["sexe"]]);

            }catch (PDOException $e) {
                $message = "L'ajout a rencontré le probléme suivant: ". $e->getMessage();
        }}
    ?>
</div>

<script>
    function redirect(url) {
        window.location.href = url;
    }
</script>

</body>
</html>
