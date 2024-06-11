<?php
require "../config.php";
require "../common.php";

// Check if the post table is empty
$postSelectOptions = "";
try {
    $connection = new PDO($dsn, $username, $password, $options);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling

    // Start a transaction
    $connection->beginTransaction();

    $sql = "SELECT * FROM post";
    $result = $connection->query($sql);

    if ($result->rowCount() > 0) {
        foreach ($result as $row) {
            $postId = $row["postId"];
            $postType = escape($row["postType"]);
            $postSelectOptions .= "<option value='$postId'>$postType</option>";
        }
    } else {
        // Handle the case when the post table is empty
        $postSelectOptions = "<option value='0'>No posts Available</option>";
    }

    if (isset($_POST['submit'])) {
        try {
            // Create a new album
            $newAlbum = [
                "albumName" => $_POST['albumName'],
                "description" => $_POST['description']
            ];

            $sql = sprintf(
                "INSERT INTO album (%s) VALUES (%s)",
                implode(",", array_keys($newAlbum)),
                ":" . implode(",:", array_keys($newAlbum))
            );

            $statement = $connection->prepare($sql);

            $statement->execute($newAlbum);

            // Get the AlbumId of the newly created album
            $albumId = $connection->lastInsertId();

            // Associate selected posts with the album
            if (!empty($_POST['selected_posts'])) {
                foreach ($_POST['selected_posts'] as $postId) {
                    $sql = "INSERT INTO post_albums (AlbumId, PostId) VALUES (:albumId, :postId)";
                    $statement = $connection->prepare($sql);
                    $statement->execute(["albumId" => $albumId, "postId" => $postId]);
                }
            }

            // Commit the transaction if all steps are successful
            $connection->commit();

            echo "New album created and relationships updated successfully.";
        } catch (PDOException $error) {
            // Rollback the transaction in case of an error
            $connection->rollBack();
            echo "Error: " . $error->getMessage();
        }
    }
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>

<?php require "templates/header.php"; ?>

<?php if (isset($_POST['submit'])) : ?>
    <blockquote><?php echo $_POST['albumName']; ?> successfully added.</blockquote>
<?php endif; ?>

<h2>Create an Album</h2>

<form method="post">
    <label for="albumName">Album Name</label>
    <input type="text" name="albumName" id="albumName" required>
    <label for="description">Album Description</label>
    <input type="text" name="description" id="description">
    <label for="selected_posts">Select post types for the Album (Hold Ctrl/Cmd to select multiple posts)</label>
    <select multiple name="selected_posts[]" id="selected_posts">
        <?php echo $postSelectOptions; ?>
    </select>
    <input type="submit" name="submit" value="Create Album">
</form>

<a href="album.php">Back to albums</a>

<?php require "templates/footer.php"; ?>
