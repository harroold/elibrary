<?php require_once "config.php"; ?>
<?php

$sql = "SELECT * FROM books WHERE active = 1 ORDER BY title LIMIT 5;";
$stmt = $db->prepare($sql);
$stmt->execute();
$books = $stmt->fetchAll();


if(isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if(empty($email) || empty($password)) {
        setFlash("Není vyplněný e-mail nebo heslo!", "danger");
    } else {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $db->prepare($sql);
        $stmt->execute([":email" => $email]);
        $user = $stmt->fetch();
        if(!$user) {
            setFlash("Uživatel neexistuje!", "danger");
        } else if($user["active"] == 0) {
          setFlash("Uživatel je zablokovaný!", "danger");
        } else {
            if(password_verify($password, $user["password"])) {
                $_SESSION["identity"] = $user;
                setFlash("Podařilo se vám přihlásit.", "success");

                $sql = "UPDATE users set last_login = :last_login WHERE email = :email;";
                $stmt = $db->prepare($sql);
                $stmt->execute([":last_login" => date('Y-m-d H:i:s'), ":email" => $email]);
            } else {
                setFlash("Zadejte správné přihlašovací údaje!", "danger");
            }
        }
    }
}
?>
<?php require_once "header.php"; ?>

<section class="bookcase py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-3">Knihovna</h2>
            <a href="pridat-knihu.php" class="btn btn-primary">Přidat novou knihu</a>
        </div>
        <?php if(!empty($books)) : ?>
        <div class="row">
            <?php foreach($books as $book): ?>
            <div class="col-md-3">
                <div class="card">
                    <img src="<?= $book["cover_url"]; ?>" class="card-img-top" alt="<?= $book["title"]; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $book["title"]; ?></h5>
                        <p class="text-muted text-uppercase"><?= $book["author"]; ?></p>
                        <p class="card-text"><?= $book["content"]; ?></p>
                        <p>
                            <a class="btn btn-primary" href="upravit-knihu.php?id=<?= $book["id"]; ?>">Upravit knihu</a>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p>
            Zatím tu žádné knihy nejsou.
        </p>
        <?php endif; ?>
    </div>
</section>
    <?php if(!isset($_SESSION["identity"])) : ?>
    <section class="login py-5">
      <div class="container">
        <div class="row justify-content-center" id="login">
          <div class="col-lg-6">
            <h4>Přihlašte se do aplikace</h4>
            <form action="index.php" method="POST">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">E-mail</label>
                <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Heslo</label>
                <input name="password" type="password" class="form-control" id="exampleInputPassword1">
              </div>
              <div class="mb-3">
                <button name="login" type="submit" class="btn btn-primary">Přihlásit se do aplikace</button>
              </div>
              <div class="mb-3">
                <p>Ještě nemáte účet?</p>
                <div class="mb-3"><a href="registrace.php" class="btn btn-primary">Registrovat se</a></div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
    <?php else : ?>
    <section class="random-books py-5">
      <div class="container">
        <h2 class="mb-3">Vaše osobní knihovna</h2>
        <p class="alert alert-warning mb-3">
            Zatím jste si nepřidali žádné knížky do své knihovny.
        </p>
      </div>
    </section>
    <?php endif; ?>


<?php require_once "footer.php"; ?>