<?php

/**
 * Use an HTML form to create a new entry in the
 * users table.
 *
 */


if (isset($_POST['submit'])) {
    require "../config.php";
    require "../common.php";

    try  {
        $connection = new PDO($dsn, $username, $password, $options);
        
        $new_user = array(
            "userId" => $_POST['userId'],
            "postTitle"  => $_POST['postTitle'],
            "postType"     => $_POST['postType'],
        );

        $sql = sprintf(
                "INSERT INTO %s (%s) values (%s)",
                "post",
                implode(", ", array_keys($new_user)),
                ":" . implode(", :", array_keys($new_user))
        );
        
        $statement = $connection->prepare($sql);
        $statement->execute($new_user);
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>

<?php require "templates/header.php"; ?>

<?php if (isset($_POST['submit']) && $statement) { ?>
    <blockquote><?php echo $_POST['postTitle']; ?> Your post is successfully added.</blockquote>
<?php } ?>

<h2>Add a post</h2>

<form method="post">
    <label for="userId">User Id</label>
    <input type="text" name="userId" id="userId">
    <label for="postTitle">Post Title</label>
    <input type="text" name="postTitle" id="postTitle">
    <label for="postType">Post Type</label>
    <input type="text" name="postType" id="postType">

    <input type="submit" name="submit" value="Save">
</form>

<a href="post.php">Back to Post</a>

<?php require "templates/footer.php"; ?>
