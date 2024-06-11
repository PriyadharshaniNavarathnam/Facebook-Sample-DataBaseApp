<?php


try  {
    require "../config.php";
    require "../common.php";

    $connection = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT *
                    FROM album
                    ORDER BY albumDate DESC";

    $result = $connection->query($sql);

} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>

<?php include "templates/header.php"; ?>

<ul>
	<li><a href="createAlbum.php"><strong>Create Album</strong></a> - add an album</li>
	<li><a href="readAlbum.php"><strong>Read Album</strong></a> - find an Album</li>

</ul>

<?php
    if ($result && $result->rowCount() > 0) { ?>
        <h2>Latest created Albums </h2>

        <table>
            <thead>
                <tr>
                    <th>Album ID</th>
                    <th>Album Name</th>
                    <th>Description</th>
                    <th>Album Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($result as $row) { ?>
                <tr>
                    <td><?php echo escape($row["albumId"]); ?></td>
                    <td><?php echo escape($row["albumName"]); ?></td>
                    <td><?php echo escape($row["description"]); ?></td>
                    <td><?php echo escape($row["albumDate"]); ?></td>
                    <td><a href="viewAlbum.php?albumId=<?php echo escape($row["albumId"]); ?>">View</a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
<?php } else { ?>
    <blockquote>No results found.</blockquote>
<?php }
?>

<a href="index.php">Back to home</a>
<?php include "templates/footer.php"; ?>