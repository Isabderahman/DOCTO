<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <h2>Login</h2>
    <form method="post">
        <input type="text" name="lg" id="lg">
        <input type="password" name="psw" id="psw">
        <input type="submit" value="Login" name="submit">
    </form>
    <?php
    session_start();
    require 'connexion.php';
    if (isset($_POST['submit'])) {
        $query = "Select loginn , pass from utilisateur where loginn = :p1 and pass=:p2";
        $resultat = $conn->prepare($query);
        $resultat->bindValue('p1', $_POST["lg"]);
        $resultat->bindValue('p2', $_POST["psw"]);
        //si la requete s'execute 
        if ($resultat->execute()) {
            $row = $resultat->fetch(PDO::FETCH_ASSOC);
            if (empty($row['loginn']) and empty($row['pass'])) {
                echo "<br><br><span class ='erreur'>Le code medcin ou le mots de passe est incorrect</span>";
            } else {
                $_SESSION['login'] = $row['loginn'];
                $_SESSION['userpass'] = $row['pass'];
                if (!isset($_COOKIE['login']) and !isset($_COOKIE['password'])) {
                    setcookie('login', $_SESSION['login'], time() + (86400 * 1));
                    setcookie('password', $_SESSION['userpass'], time() + (86400 * 1));
                }
                header('location:gestionR.php');
            }
        }
    }
    ?>
</body>

</html>