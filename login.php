<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Connection">
    <meta name="author" content="Darkham">

    <title>Bureau Méthode</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="favicon.png" />
    
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    

</head>

<body>

    <?php 
    session_start();
    include_once ('includes/db.class.php');
    include_once ('includes/fonctions.php');
   
    if (isset($_POST['submit']) && $_POST['submit'] == 'Se connecter') {

        if ((isset($_POST['pseudo']) && !empty($_POST['pseudo'])) && (isset($_POST['password']) && !empty($_POST['password']))) {

            $pseudo = $_POST['pseudo'];
            $password = md5($_POST['password']);

            $db = new Db();
            $reqTestCo = $db->query("SELECT count(username) as exist FROM members WHERE username = :id and password = :pwd", array("id"=>"".toSQL($pseudo)."", "pwd"=>"".toSQL($password).""));
            $db->CloseConnection();

            $nbLignesBDD = $reqTestCo[0]['exist'];
            
            if ($nbLignesBDD == 1) {
                $_SESSION['login'] = $pseudo; 
                header('Location: index.php');
                exit();
            }
            else {
                header('Location: register.php');
                exit();
            }
        }
    }
    ?>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Connexion</h3>
                    </div>
                    <div class="panel-body">
                        <form method="POST" role="form">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Pseudo" name="pseudo" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Mot de passe" name="password" type="password">
                                </div>
                                <input type="submit" value="Se connecter" name="submit" class="btn btn-lg btn-success btn-block"/>
                                <a href="register.php" class="btn btn-lg btn-warning btn-block">S'enregistrer</a>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>