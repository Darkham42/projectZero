<?php
    
    require_once("lib/password.php");
   
    if(isset($_POST['submit'])) {
        
        $pseudo = $_POST['pseudo'];
        $password = $_POST['password'];
        $mail = $_POST['mail'];

        $hash = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $db = new Db();
        $reqTest = $db->query("SELECT * FROM USERS WHERE pseudo = :user", array("user"=>$pseudo));
        $reqCount = $db->query("SELECT * FROM USERS");

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
            $addMember = $db->query("INSERT INTO USERS (id, pseudo, email, password) values (:id, :user, :mail, :pass)", 
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

<div class="card">
    <p class="card-title">Register</p>
    <img src="http://s3.foxfilm.com/foxmovies/production/films/103/images/gallery/deadpool1-gallery-image.jpg" class="full" />
    <form method="POST" action='#'>
        <div class="form-group">
            <input id="pseudo" spellcheck=false class="form-control" name="pseudo" type="text" size="20" alt="login" required="">
            <span class="form-highlight"></span>
            <span class="form-bar"></span>
            <label for="username" class="float-label">Pseudo</label>
        </div>
        <div class="form-group">
            <input id="pseudo" spellcheck=false class="form-control" name="mail" type="email" size="20" alt="login" required="">
            <span class="form-highlight"></span>
            <span class="form-bar"></span>
            <label for="username" class="float-label">Email</label>
        </div>
        <div class="form-group">
            <input id="password" class="form-control" spellcheck=false name="password" type="password" size="20" alt="login" minlength="4" maxlength="8" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{4,8}$" title="Votre mot de passe doit contenir entre 4 et 8 caractères dont au moins une minuscule, une majuscule et un chiffre." required="">
            <span class="form-highlight"></span>
            <span class="form-bar"></span>
            <label for="password" class="float-label">Password</label>
        </div>
        <div class="form-group">
            <button type="submit" value="Valider" name="submit" ripple>Sign up</button>
        </div>
    </form>
    <p class="url"><a href=".?action=register">Abort</a></p>
</div>