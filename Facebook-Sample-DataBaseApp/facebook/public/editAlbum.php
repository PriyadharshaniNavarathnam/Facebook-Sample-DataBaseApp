<?php
if (isset($_GET['albumId'])) {
    require "../config.php";
    require "../common.php";

    try {
        $connection = new PDO($dsn, $username, $password, $options);

        $albumId = $_GET['albumId'];

        if (isset($_POST['submit'])) {
            $newAlbumName = $_POST['newAlbumName'];
            $newAlbumDescription = $_POST['newAlbumDescription'];

            $sql = "UPDATE album
                    SET albumName = :newAlbumName,
                        description = :newAlbumDescription
                    WHERE albumId = :albumId";

            $statement = $connection->prepare($sql);
            $statement->bindValue(':newAlbumName', $newAlbumName);
            $statement->bindValue(':newAlbumDescription', $newAlbumDescription);
            $statement->bindValue(':albumId', $albumId);
            $statement->execute();

            // Update associated posts
            if (isset($_POST['associatedPost'])) {
                $associatedPostIds = $_POST['associatedPost'];

                // Remove existing associations
                $sql = "DELETE FROM post_albums WHERE albumId = :albumId";
                $statement = $connection->prepare($sql);
                $statement->bindValue(':albumId', $albumId);
                $statement->execute();

                // Create new associations for selected posts
                foreach ($associatedPostIds as $postId) {
                    $sql = "INSERT INTO post_albums (albumId, postId) VALUES (:albumId, :postId)";
                    $statement = $connection->prepare($sql);
                    $statement->bindValue(':albumId', $albumId);
                    $statement->bindValue(':postId', $postId);
                    $statement->execute();
                }
            }

            // Redirect to viewAlbum.php with the updated albumId
            header("Location: viewAlbum.php?albumId=$albumId");
            exit;
        }

        // Fetch existing album information
        $sql = "SELECT * FROM album WHERE albumId = :albumId";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':albumId', $albumId);
        $statement->execute();
        $album = $statement->fetch(PDO::FETCH_ASSOC);

        // Fetch associated posts
        $sql = "SELECT p.* FROM post p
                JOIN post_albums pa ON p.postId = pa.postId
                WHERE pa.albumId = :albumId";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':albumId', $albumId);
        $statement->execute();
        $associatedPost = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Initialize associatedPostIds as an empty array
        $associatedPostIds = [];

        // Retrieve all posts for the list
        $sql = "SELECT * FROM post";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $allPosts = $statement->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
} else {
    echo "Album ID not specified.";
    exit;
}
?>

<?php require "templates/header.php"; ?>

<?php if (isset($_POST['submit']) && $statement) { ?>
    <blockquote><?php echo htmlspecialchars($_POST['newAlbumName']); ?> successfully edited.</blockquote>
<?php } ?>

<h2>Edit an Album</h2>

<form method="post">
    <label for="newAlbumName">Album Name</label>
    <input type="text" name="newAlbumName" id="newAlbumName" value="<?php echo htmlspecialchars($album['albumName']); ?>" required>
    <label for="newAlbumDescription">Album Description</label>
    <input type="text" name="newAlbumDescription" id="newAlbumDescription" value="<?php echo htmlspecialchars($album['description']); ?>" required>

    <!-- Checkbox inputs to select associated posts -->
    <label>Associated Posts:</label><br>
    <?php
    foreach ($allPosts as $post) {
        $postId = $post['postId'];
        $postType = $post['postType'];
        $isChecked = in_array($postId, array_column($associatedPost, 'postId')) ? 'checked' : '';
        ?>
        <input type="checkbox" name="associatedPost[]" value="<?php echo $postId; ?>" <?php echo $isChecked; ?>>
        <?php echo htmlspecialchars($postType); ?><br>
    <?php }
    ?>

    <!-- Select list to add new associated posts -->
    <label>Add New Associated Posts:</label><br>
    <select name="newAssociatedPosts[]" multiple>
        <?php
        foreach ($allPosts as $post) {
            $postId = $post['postId'];
            $postType = $post['postType'];
            $isSelected = in_array($postId, $associatedPostIds) ? 'selected' : '';
            ?>
            <option value="<?php echo $postId; ?>" <?php echo $isSelected; ?>><?php echo htmlspecialchars($postType); ?></option>
        <?php }
        ?>
    </select>

    <input type="submit" name="submit" value="Save">
</form>

<a href="album.php">Back to albums</a>

<?php require "templates/footer.php"; ?>
