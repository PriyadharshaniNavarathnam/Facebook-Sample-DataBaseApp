<?php


    try  {

        require "../config.php";
        require "../common.php";

        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT *
                        FROM post
                        ORDER BY postDate DESC
                        LIMIT 3";

        $result = $connection->query($sql);

    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }

?>
<?php require "templates/header.php"; ?>


<?php
    if ($result && $result->rowCount() > 0) { ?>
        <h2>Latest posted Posts </h2>

        <table>
            <thead>
                <tr>
                    <th>Post Id</th>
                    <th>User Id</th>
                    <th>Post Title</th>
                    <th>Post Type</th>
                    <th>Post Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($result as $row) { ?>
            <tr>
                <td><?php echo escape($row["postId"]); ?></td>
                <td><?php echo escape($row["userId"]); ?></td>
                <td><?php echo escape($row["postTitle"]); ?></td>
                <td><?php echo escape($row["postType"]); ?></td>
                <td><?php echo escape($row["postDate"]); ?></td>
                <td><a href="viewPost.php?postId=<?php echo escape($row["postId"]); ?>">View</a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No results found.</blockquote>
    <?php }
?>


<?php require "templates/footer.php"; ?>