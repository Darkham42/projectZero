<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Register">
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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <?php
    
    include_once ('includes/db.class.php');
    include_once ('includes/fonctions.php');
   
    if(isset($_POST['submit'])) {
        
        $pseudo = testPseudo($_POST['nom'],$_POST['prenom']);

        $password = md5($_POST['password']);

        $mail = $_POST['mail'];

        $emetteur = str_replace("@google.com", "", $mail);

        $find = '@google.com';
        $position = strpos($mail, $find);

        if ($position === false) {
            echo "<font color='red'>Vous n'êtes pas autorisé à vous inscrire !</font>";
        } else {

            $db = new Db();
            $reqTest = $db->query("SELECT count(username) as exist FROM members WHERE username = :user", array("user"=>"".toSQL($pseudo).""));
            $db->CloseConnection();

            $nbLignesBDD = $reqTest[0]['exist'];

            if ($nbLignesBDD == 0) {
                $db = new Db();
                $addMember = $db->query("INSERT INTO members (username, email, password, emetteur) values (:user, :mail, :pass, :emet)", array("user"=>"".toSQL($pseudo)."", "mail"=>"".toSQL($mail)."", "pass"=>"".toSQL($password)."", "emet"=>"".toSQL($emetteur).""));
                $db->CloseConnection();
                header('Location: login.php');
                exit();
            }
            else {
                echo "<font color='red'>Pseudo déjà utilisé !</font>";
            }
        }
    }

    ?>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">S'enregistrer</h3>
                    </div>
                    <div class="panel-body">
                        <form method="POST" role="form">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Nom" size=32 maxlength=20 name="nom" type="text" autofocus required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Prénom" size=32 maxlength=20 name="prenom" type="text" required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Mot de passe" name="password" type="password" minlength="4" maxlength="8" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{4,8}$" title="Votre mot de passe doit contenir entre 4 et 8 caractères dont au moins une minuscule, une majuscule et un chiffre." required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="email@exemple.com" name="mail" type="email" required>
                                </div>
                                <input type="submit" value="Valider" name="submit" class="btn btn-lg btn-success btn-block"/>
                                <a href="login.php" class="btn btn-lg btn-danger btn-block">Annuler</a>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>