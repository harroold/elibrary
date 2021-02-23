<?php require_once "config.php"; ?>
<?php

    $id = $_GET["id"];
    $sql = "SELECT * FROM books WHERE id = :id;";
    $stmt = $db->prepare($sql);
    $stmt->execute([":id" => $id]);
    $book = $stmt->fetch();

    if(!$book) {
        setFlash("Zadali jste špatnou adresu, zkuste to jinak!", "danger");
        header("Location: knihovna.php");
        exit();
    }

    $sql = "SELECT * FROM categories;";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll();

    $sql = "SELECT c.title FROM books_has_categories AS bc
            JOIN categories AS c ON c.id = bc.categories_id
            WHERE bc.books_id = :id;";
    $stmt = $db->prepare($sql);
    $stmt->execute([":id" => $book["id"]]);
    $bookCategories = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);


    if(isset($_POST["editBook"])) {
        $valid = true;

        $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);
        $author = filter_input(INPUT_POST, "author", FILTER_SANITIZE_STRING);
        $content = filter_input(INPUT_POST, "content", FILTER_SANITIZE_STRING);

        if(empty($title)) {
            setFlash("Název knihy musí být vyplněný!", "danger");
            $valid = false;
        }

        if(empty($author)) {
            setFlash("Autor knihy musí být vyplněný!", "danger");
            $valid = false;
        }

        $content = (empty($content)) ?  NULL : $content;

        if($valid) {
            if(!empty($_FILES["cover_url"]["name"])) {
                $file = $_FILES["cover_url"];
                $fileName = "./uploads/" . uniqid() . "_" . $file["name"];
                if(!move_uploaded_file($file["tmp_name"], $fileName)) {
                    setFlash("Nepovedlo se uložit obrázek na server!", "danger");
                    $fileName = NULL;
                } 
            } else if(empty($_FILES["cover_url"]["name"]) && isset($_POST["deleteImage"])) {
                unlink($book["cover_url"]);
                $fileName = NULL;
            } else {
                $fileName =  $book["cover_url"];
            }
            $sql = "UPDATE books SET title = :title, author = :author, content = :content,
                    cover_url = :cover_url WHERE id = :id;";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ":title" => $title,
                ":author" => $author,
                ":content" => $content,
                ":cover_url" => $fileName,
                ":id" => $id
            ]);

            if(!empty($_POST["categories"])) {
                $book_id = $id;

                $sql = "DELETE FROM books_has_categories WHERE books_id = :books_id;";                
                $stmt = $db->prepare($sql);
                $stmt->execute([":books_id" => $book_id]);

                $sql = "INSERT INTO books_has_categories VALUES(:books_id, :categories_id);";
                $stmt = $db->prepare($sql);
                foreach($_POST["categories"] as $category) {
                    $stmt->execute([":books_id" => $book_id, ":categories_id" => $category]);
                }
            }


            setFlash("Upravili jste knihu!", "success");
            header("Location: knihovna.php");
            exit();
        }


    }
?>
<?php require_once "header.php"; ?>


<section class="bookcase py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="mb-3">Upravit knihu</h2>
                <form action="<?= $_SERVER["REQUEST_URI"]; ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Název knihy</label>
                        <input name="title" type="text" class="form-control" value="<?= $book["title"]; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Autor knihy</label>
                        <input name="author" type="text" class="form-control" value="<?= $book["author"]; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Obsah knihy <span class="text-muted">(nepovinné)</span></label>
                        <textarea name="content" class="form-control" rows="10"><?= $book["content"]; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Žánry</label>
                        <?php foreach($categories as $category) : 
                            $checked = (in_array($category["title"], $bookCategories)) ? " checked" : "";
                        ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]" value="<?= $category["id"]; ?>"<?= $checked; ?>>
                                <label class="form-check-label">
                                    <?= $category["title"]; ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Obálka knihy</label>
                        <div>
                            <img src="<?= $book["cover_url"]; ?>" alt="" widht="80" height="200">
                        </div>
                        <div>
                            <label class="form-label"><input type="checkbox" name="deleteImage">Smazat obrázek!</label>
                        </div>
                        <input name="cover_url" type="file" class="form-control">
                    </div>
                    <button name="editBook" type="submit" class="btn btn-primary">Upravit knihu</button>
                </form>

            </div>
        </div>
    </div>

    </div>
</section>



<?php require_once "footer.php"; ?>