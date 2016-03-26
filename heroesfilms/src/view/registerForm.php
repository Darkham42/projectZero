<?php
    
    require_once("lib/password.php");
   
    if(isset($_POST['submit'])) {
        
        $pseudo = $_POST['nom'] . $_POST['prenom'];
        $password = $_POST['password'];
        $mail = $_POST['mail'];

        $hash = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $db = new Db();
        $reqTest = $db->query("SELECT * FROM users WHERE pseudo = :user", array("user"=>$pseudo));
        $reqCount = $db->query("SELECT * FROM users");

        $errmail = false;
        $count = 0;
        foreach($reqCount as $ligne) {
            $count++;
            if($ligne["email"] === $mail) $errmail = true;
        }
        $find = 0;
       
        foreach($reqTest as $ligne) {
            if(isset($ligne['pseudo'])) $find = 1;
            
        }
        echo "find " . $find;
        //Pas d'utilisateur connu
        if ($find == 0 && !$errmail) {
            $db = new Db();
            $addMember = $db->query("INSERT INTO users (id, pseudo, email, password) values (:id, :user, :mail, :pass)", 
                array("id" => $count, "user"=>$pseudo, "mail"=>$mail, "pass"=>$hash));
            $db->CloseConnection();
            $_SESSION['user'] = $pseudo;
            $_SESSION['id'] = $id;
            header('Location: .');
            
            exit();
        }
        else {
            echo "<font color='red'>Pseudo ou adresse mail déjà utilisé ! : " . $pseudo . "</font>";
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
                                <input class="form-control" placeholder="email@exemple.com" name="mail" type="email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                            </div>
                            <input type="submit" value="Valider" name="submit" class="btn btn-lg btn-success btn-block"/>
                            <a href=".?action=login" class="btn btn-lg btn-danger btn-block">Annuler</a>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>