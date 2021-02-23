<?php require_once "config.php"; ?>
<?php
  $sql = "SELECT * FROM books WHERE active = 1 ORDER BY title LIMIT 10;";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $books = $stmt->fetchAll();
  
  if(isset($_POST["rateBook"])) {
    $bookId = $_POST["book_id"];
    $userId = $_SESSION["identity"]["id"];
    $stars = $_POST["stars"];
    $review = $_POST["review"];

    $sql = "INSERT INTO user_rated_book (stars, review, books_id, users_id) VALUES (:stars, :review, :books_id, :users_id);";
    $stmt = $db->prepare($sql);
    $stmt->execute([":stars" => $stars, ":review" => $review, ":books_id" => $bookId, ":users_id" => $userId]);

    setFlash("Kniha přidána do oblíbených", "success");

    header("Location: knihovna.php");
    exit();    

  }
//   if(isset($_GET["like_id"])) {

//     $bookId = $_GET["like_id"];
//     $userId = $_SESSION["identity"]["id"];

//     $sql = "INSERT INTO user_rated_book (books_id, users_id) VALUES (:books_id, :users_id);";
//     $stmt = $db->prepare($sql);
//     $stmt->execute([":books_id" => $bookId, ":users_id" => $userId]);

//     setFlash("Kniha přidána do oblíbených", "success");

//     header("Location: knihovna.php");
//     exit();    
//   }


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
            <?php foreach($books as $book): 
                // vytažení informací o žánrech každé knihy
                $sql = "SELECT c.title FROM books_has_categories AS bc
                        JOIN categories AS c ON c.id = bc.categories_id
                        WHERE bc.books_id = :id;";
                $stmt = $db->prepare($sql);
                $stmt->execute([":id" => $book["id"]]);
                $categories = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
            ?>
            <div class="col-md-3">
                <div class="card mb-3">
                    <img src="<?= $book["cover_url"]; ?>" class="card-img-top" alt="<?= $book["title"]; ?>">
                    <div class="card-body">
                        <div class="pb-3">
                        <?php if(!empty($categories)) : ?>
                            <?php foreach($categories as $category) : ?>
                                <span class="badge bg-secondary"><?= $category; ?></span>
                            <?php endforeach; ?>    
                        <?php endif; ?>
                        </div>
                        <h5 class="card-title"><?= $book["title"]; ?></h5>
                        <p class="text-muted text-uppercase"><?= $book["author"]; ?></p>
                        <p class="card-text"><?= $book["content"]; ?></p>
                        <p>
                            <a class="btn btn-primary" href="upravit-knihu.php?id=<?= $book["id"]; ?>">Upravit knihu</a>
                        </p>
                        <p>
                                        <!-- Button trigger modal -->
                            <button type="button" class="likeBtn btn btn-danger" data-book-id="<?= $book["id"]; ?>" data-bs-toggle="modal" data-bs-target="#rateBook">
                               Ohodnotit knihu
                            </button>
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

<!-- Modal -->
<div class="modal fade" id="rateBook" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="knihovna.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Přidat knihu do knihovny</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Vaše hodnocení</label>
                        <select name="stars" class="form-select">
                            <option value="NULL">Vyberte vaše hodnocení</option>
                            <option value="1">*</option>
                            <option value="2">**</option>
                            <option value="3">***</option>
                            <option value="4">****</option>
                            <option value="5">*****</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slovní hodnocení</label>
                        <textarea name="review" class="form-control" rows="3"></textarea>
                    </div>
                    <input type="hidden" name="book_id" value="" class="hiddenId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zrušit</button>
                    <button name="rateBook" type="submit" class="btn btn-primary">Ohodnotit knihu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
"use strict"
const btns = document.querySelectorAll(".likeBtn");
[...btns].forEach(btn => {
    btn.addEventListener("click", ev => {
        document.querySelector(".hiddenId").value = btn.getAttribute("data-book-id");
    })
});
</script>

<?php require_once "footer.php"; ?>