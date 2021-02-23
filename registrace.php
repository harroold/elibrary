<?php require_once "config.php"; ?>
<?php
if(isset($_POST["register"])) {
    $valid = true;

    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $password = $_POST["password"];
    $passwordVerify = $_POST["passwordVerify"];

    if(!$email) {
        setFlash("E-mail musí být vyplněný a mít platný tvar (@).", "danger");
        $valid = false;
    }

    if(empty($passwordVerify) || empty($password)) {
        setFlash("Obě hesla musí být vyplněná!", "danger");
        $valid = false;
    }

    if(!empty($password) && ($password !== $passwordVerify)) {
        setFlash("Hesla nesouhlasí!", "danger");
        $valid = false;
    }

    if($valid) {
        $username = (empty($username)) ? NULL : $username;
        $sql = "INSERT INTO users (email, password, username, active, role)
                VALUES (:email, :password, :username, :active, :role);";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ":email" => $email,
            ":password" => password_hash($password, PASSWORD_DEFAULT),
            ":username" => $username,
            ":active" => 1,
            ":role" => "user"
        ]);
        setFlash("Uživatel byl úspěšně zaregistrován!", "success");
        header("Location: index.php#login");
        exit();    
    }
}

?>
<?php require_once "header.php"; ?>

<section class="profil py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>Registrace</h2>
            <form action="registrace.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input name="email" type="email" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Uživatelské jméno <span class="text-muted">(nepovinné)</span></label>
                    <input name="username" type="text" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Heslo</label>
                    <input name="password" type="password" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Heslo znovu</label>
                    <input name="passwordVerify" type="password" class="form-control">
                </div>
                <button name="register" type="submit" class="btn btn-primary">Registrovat uživatele</button>
            </form>
        </div>
      </div>
       
    </div>
</section>


<?php require_once "footer.php"; ?>