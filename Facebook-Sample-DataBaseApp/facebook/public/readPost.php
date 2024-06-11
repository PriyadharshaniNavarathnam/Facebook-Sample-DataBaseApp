<?php

/**
 * Function to query information based on 
 * a parameter: in this case, location.
 *
 */

if (isset($_POST['submit'])) {
    try  {
        
        require "../config.php";
        require "../common.php";

        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT * 
                        FROM post
                        WHERE postType = :postType";

        $location = $_POST['postType'];

        $statement = $connection->prepare($sql);
        $statement->bindParam(':postType', $location, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>
<?php require "templates/header.php"; ?>
        
<?php  
if (isset($_POST['submit'])) {
    if ($result && $statement->rowCount() > 0) { ?>
        <h2>Results</h2>

        <table>
            <thead>
                <tr>
                    <th>User Id</th>
                    <th>Post Title</th>
                    <th>Post Type</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($result as $row) { ?>
            <tr>
                <td><?php echo escape($row["userId"]); ?></td>
                <td><?php echo escape($row["postTitle"]); ?></td>
                <td><?php echo escape($row["postType"]); ?></td>

            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No results found for <?php echo escape($_POST['postType']); ?>.</blockquote>
    <?php } 
} ?> 

<h2>Find posts based on post Type</h2>

<form method="post">
    <label for="postType">Post Type</label>
    <input type="text" id="postType" name="postType">
    <input type="submit" name="submit" value="View Posts">
</form>

<a href="post.php">Back to Post </a>

<?php require "templates/footer.php"; ?>
