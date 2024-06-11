<?php

if (isset($_GET['albumId'])) {
    try  {

        require "../config.php";
        require "../common.php";

        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT *
                FROM album
                WHERE albumId = :albumId";

        $albumId = $_GET['albumId'];

        $statement = $connection->prepare($sql);
        $statement->bindValue(':albumId', $albumId);
        $statement->execute();

        $album = $statement->fetch(PDO::FETCH_ASSOC);

        // Fetch associated posts for the album
        $sql = "SELECT p.*
                FROM post p
                JOIN post_albums pa ON p.postId = pa.postId
                WHERE pa.albumId = :albumId";

        $statement = $connection->prepare($sql);
        $statement->bindValue(':albumId', $albumId);
        $statement->execute();

        $associatedPosts = $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>

<?php require "templates/header.php"; ?>

<?php if ($album) { ?>
    <h2>Album Details</h2>
    <p><b>Album ID</b>: <?php echo escape($album["albumId"]); ?></p>
    <p><b>Album Name</b>: <?php echo escape($album["albumName"]); ?></p>
    <p><b>Description</b>: <?php echo escape($album["description"]); ?></p>
    <p><b>Album Date</b>: <?php echo escape($album["albumDate"]); ?></p>

    <h3>Associated Posts:</h3>
    <ul>
        <?php foreach ($associatedPosts as $post) { ?>
            <li><?php echo escape($post["postType"]); ?></li>
        <?php } ?>
    </ul>

    <a href="editAlbum.php?albumId=<?php echo escape($album["albumId"]); ?>">Edit Album</a><br>
    <a href="index.php">Back to Home</a>
<?php } else { ?>
    <blockquote>No results found for <?php echo escape($_GET['albumId']); ?>.</blockquote>
<?php } ?>

<?php require "templates/footer.php"; ?>
