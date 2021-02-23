<?php require_once "config.php"; ?>
<?php
if (!(isset($_SESSION["identity"]))) {
    header("Location: index.php");
    exit();
}
if (isset($_GET['id'])) {
    $bookId = $_GET['id'];
    $userId = $_SESSION["identity"]["id"];
    $sql = "SELECT * from user_rated_book WHERE users_id = :userId AND books_id = :bookId";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ":userId" => $userId,
        ":bookId" => $bookId
    ]);
    $kniha = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    if ($kniha != null) {
        setFlash("Tuto knihu již máte mezi oblíbenými", "success");
        header("Location: index.php");
        exit();
    }


    $sql = "INSERT INTO user_rated_book (users_id, books_id) VALUES (:userId, :bookId)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ":userId" => $userId,
        ":bookId" => $bookId
    ]);
    setFlash("Kniha byla uložena mezi vaše oblíbené", "success");
    header("Location: index.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
