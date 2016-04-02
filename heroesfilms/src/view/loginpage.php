<?php
    
    require_once("lib/password.php");
   
    if(isset($_POST['submit'])) {

    	$mail = $_POST['mail'];
    	echo $mail ;
        $password = $_POST['password'];

        $db = new Db();
        $reqTest = $db->query("SELECT * FROM USERS WHERE password = :pass", array("pass"=>$password));
        $req = $db->query("SELECT * FROM USERS");

        $findmail = false;
        $hash;
        $id = -1;
        $pseudo;
        foreach($req as $ligne) {
            if($ligne["email"] === $mail){
            	$findmail = true;
            	$hash = $ligne["password"];
            	$id = $ligne["id"];
            	$pseudo = $ligne["pseudo"];
            } 
        }

        if ($findmail) {
            $db = new Db();
            if (password_verify($password, $hash)) { 
					$_SESSION['user'] = $pseudo;
					$_SESSION["id"] = $id;
					header('Location: .?action=galerie');
			}
			else{
            	header('Location: .?action=login');
            }
            exit();
        }
        else {
            echo "<font color='red'>Email unknown ! : " . $mail . "</font>";
        }
    }

?>

<div class="card">
	<p class="card-title">Log In</p>
	<img src="assets/logIn.jpg" alt="LogIn" class="full" />
	<form method="POST" action='#'>
		<div class="form-group">
		<input id="mail" spellcheck=false class="form-control" name="mail" type="email" size="20" required="">
		<span class="form-highlight"></span>
		<span class="form-bar"></span>
		<label class="float-label">Email</label>
		</div>
		<div class="form-group">
			<input id="password" class="form-control" spellcheck=false name="password" type="password" size="20" required="">
			<span class="form-highlight"></span>
			<span class="form-bar"></span>
			<label for="password" class="float-label">Password</label>
		</div>
		<div class="form-group">
			<button type="submit" value="Valider" name="submit">Sign in</button>
		</div>
	</form>
	<p class="url"><a href=".?action=register">Need new account ?</a></p>
	<p class="lock"><a href="#" onclick="alert('It\'s a feature')">Forgot Password ?</a></p>
</div>