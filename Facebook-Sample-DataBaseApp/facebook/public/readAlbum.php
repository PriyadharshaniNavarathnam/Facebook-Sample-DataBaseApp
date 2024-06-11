<?php

if (isset($_POST['submit'])) {
    try {
        require "../config.php";
        require "../common.php";

        $connection = new PDO($dsn, $username, $password, $options);

        $albumName = $_POST['albumName'];

        // Query albums based on albumName
        $sql = "SELECT *
                FROM album
                WHERE albumName = :albumName";

        $statement = $connection->prepare($sql);
        $statement->bindParam(':albumName', $albumName, PDO::PARAM_STR);
        $statement->execute();

        $albums = $statement->fetchAll();

        // If albums are found, fetch associated posts
        if ($albums && $statement->rowCount() > 0) {
            $albumId = $albums[0]['albumId'];

            // Query associated posts
            $sql = "SELECT p.*
                    FROM post p
                    JOIN post_albums pa ON p.postId = pa.postId
                    WHERE pa.albumId = :albumId";

            $statement = $connection->prepare($sql);
            $statement->bindParam(':albumId', $albumId, PDO::PARAM_INT);
            $statement->execute();

            $associatedPosts = $statement->fetchAll();
        }
    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>




<?php require "templates/header.php"; ?>

<h2>Find album based on Album Name</h2>

<form method="post">
    <label for="albumName">Album Name</label>
    <input type="text" id="albumName" name="albumName">
    <input type="submit" name="submit" value="View album">
</form>

<a href="album.php">Back to album</a>


<?php if (isset($_POST['submit'])) { ?>
    <h2>Albums</h2>
    <?php if (!empty($albums)) { ?>
        <table>
            <thead>
                <tr>
                    <th>Album Name</th>
                    <th>Album Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($albums as $album) { ?>
                    <tr>
                        <td><?php echo escape($album["albumName"]); ?></td>
                        <td><?php echo escape($album["description"]); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php if (!empty($associatedPosts)) { ?>
            <h2>Associated Posts</h2>
            <table>
                <thead>
                    <tr>
                        <th>User Id</th>
                        <th>Post Title</th>
                        <th>Post Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($associatedPosts as $row) { ?>
                        <tr>
                            <td><?php echo escape($row["userId"]); ?></td>
                            <td><?php echo escape($row["postTitle"]); ?></td>
                            <td><?php echo escape($row["postType"]); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    <?php } else { ?>
        <blockquote>No results found for <?php echo escape($_POST['albumName']); ?>.</blockquote>
    <?php }
} ?>


<?php require "templates/footer.php"; ?>
