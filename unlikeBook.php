<?php require_once "config.php"; ?>
<?php
if (!(isset($_SESSION["identity"]))) {
    header("Location: index.php");
    exit();
}
if (isset($_GET['id'])) {
    $bookId = $_GET['id'];
    $userId = $_SESSION["identity"]["id"];
    $sql = "SELECT * fROM user_rated_book WHERE users_id = :userId AND books_id = :bookId";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ":userId" => $userId,
        ":bookId" => $bookId
    ]);
    $kniha = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    if ($kniha == null) {
        header("Location: index.php");
        exit();
    }
    $sql = "DELETE FROM user_rated_book WHERE users_id = :userId AND books_id = :bookId";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ":userId" => $userId,
        ":bookId" => $bookId
    ]);

    setFlash("Kniha byla odebrána z vašich oblíbených", "success");
    header("Location: profil.php?id=" . $userId);
    exit();
} else {
    header("Location: index.php");
    exit();
}
