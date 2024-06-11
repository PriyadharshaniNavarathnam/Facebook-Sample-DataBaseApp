<?php
require_once "../config.php";
require_once "../common.php";

if (isset($_GET['postId'])) {
    try {
        $connection = new PDO($dsn, $username, $password, $options);

        $postId = $_GET['postId'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            $newPostTitle = $_POST['newPostTitle'];
            $newPostType = $_POST['newPostType'];
            $newPostDate = $_POST['newPostDate'];

            $sql = "UPDATE post
                    SET postTitle = :newPostTitle,
                        postType = :newPostType,
                        postDate = :newPostDate
                    WHERE postId = :postId";

            $statement = $connection->prepare($sql);
            $statement->bindValue(':newPostTitle', $newPostTitle);
            $statement->bindValue(':newPostType', $newPostType);
            $statement->bindValue(':newPostDate', $newPostDate);
            $statement->bindValue(':postId', $postId);
            $statement->execute();

            header("Location: viewPost.php?postId=$postId");
            exit; // Make sure to exit after a redirect
        }

        $sql = "SELECT * FROM post WHERE postId = :postId";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':postId', $postId);
        $statement->execute();

        $post = $statement->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $error) {
        echo "Error: " . $error->getMessage();
    }
} else {
    echo "Post ID is not specified.";
    exit;
}
?>

<?php require "templates/header.php"; ?>

<h2>Edit a Post</h2>

<form method="post">
    <label for="newPostTitle">Post Title</label>
    <input type="text" name="newPostTitle" id="newPostTitle" value="<?php echo $post['postTitle']; ?>">
    <label for="newPostType">Post Type</label>
    <input type="text" name="newPostType" id="newPostType" value="<?php echo $post['postType']; ?>">
    <label for="newPostDate">Post Date</label>
    <input type="date" name="newPostDate" id="newPostDate" value="<?php echo $post['postDate']; ?>">
    <input type="submit" name="submit" value="Save">
</form>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>
