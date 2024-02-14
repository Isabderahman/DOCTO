<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODIFIER RendezVous :</title>
</head>
<body>
    <?php
        require  'connexion.php';
        session_start();
        echo"<a class='btn' href='gestionR.php'>Précedent</a>";
        echo"<a class='btn' href='deconnexion.php'>Deconnexion</a>"; 
        if(empty($_SESSION['login']) and empty($_SESSION['userpass'])){
            header("Location: deconnexion.php");
        }
        $resultat = $conn->prepare("SELECT * from rdv where numRdv=:para");
        $resultat->bindValue(':para',$_GET['num_rdv']);
        $resultat->execute();
        $col=$resultat->fetch(PDO::FETCH_ASSOC);
    ?>
    <table>
        <form method="post">
            <tr>
                <td>codeRdv : </td>
                <td><input type="text" name="numRDV" value=<?php echo($col['numRDV'])?>></td>
            </tr>
            <tr>
                <td>dateRdv : </td>
                <td><input type="text" name="dateRDV" value=<?php echo($col['dateRDV'])?>></td>
            </tr>
            <tr>
                <td>heureRdv : </td>
                <td><input type="text" name="heureRDV" value=<?php echo($col['heurRDV'])?>></td>
            </tr>
            <tr>
            <td>code Patient : </td>
                <td>
                    <select name="codePatient" id="CP" name="codePatient">
                        <?php 
                            $rens=$conn->query("SELECT codePatient from patient");
                            $rens->execute();
                            while($cl=$rens->fetch(PDO::FETCH_ASSOC)){
                                echo "<option ";
                                if  ($cl['codePatient']==$col["codePatient"]) {
                                    echo 'selected';
                                }
                                echo ">".$cl['codePatient']."</option>";
                            }
                        ?>
                    </select>
                </td>
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
                                if  ($coll['codeMedecin']==$col["codeMedecin"]) {
                                    echo 'selected';
                                }
                                echo ">".$coll['codeMedecin']."</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Modifier</td>
                <td><input type="submit" value="Modifier" name="modifier"></td>
            </tr>
        </form>
    </table>
    <?php
        if(isset($_POST['modifier'])){
            $query="update rdv set dateRDV=:p2 , heurRDV=:p3 ,codePatient=:p4, codeMedecin=:p5 where numRDV=:p1";
            $resUpdate=$conn->prepare($query);
            $resUpdate->bindValue("p1",$_POST['numRDV']);
            $resUpdate->bindValue("p2",$_POST['dateRDV']);
            $resUpdate->bindValue("p3",$_POST['heureRDV']);
            $resUpdate->bindValue("p4",$_POST['codePatient']);
            $resUpdate->bindValue("p5",$_POST['codeMedecin']);
            try{
                $resUpdate->execute();
                $message = "Modification Reussite";
                header("Location: gestionR.php?message=$message");
            }catch(PDOException $e){
                $message=$e->getMessage();
                header("Location: gestionR.php?message=$message");
            }
        }
    ?>
</body>
</html>