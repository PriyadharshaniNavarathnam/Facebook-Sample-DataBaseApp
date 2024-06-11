<?php

if (isset($_GET['postId'])) {
    try  {

        require "../config.php";
        require "../common.php";

        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT *
                        FROM post
                        WHERE postId = :postId";

        $postId = $_GET['postId'];

        $statement = $connection->prepare($sql);
        $statement->bindValue(':postId', $postId);
        $statement->execute();

        $post = $statement->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>


<?php require "templates/header.php"; ?>

<?php
    if ($post) { ?>
        <h2><B>Post Details</h2>
        <p><b>User ID</b>: <?php echo escape($post["userId"]); ?></p>
        <p><b>Post Title</b>: <?php echo escape($post["postTitle"]); ?></p>
        <p><b>Post Type </b>: <?php echo escape($post["postType"]); ?></p>
        <p><b>Post Date </b>: <?php echo escape($post["postDate"]); ?></p>

    <?php } else { ?>
        <blockquote>No results found for <?php echo escape($_GET['postId']); ?>.</blockquote>
    <?php }
?>

<a href="editPost.php?postId=<?php echo escape($post["postId"]); ?>">Edit Post</a><br>
<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>
