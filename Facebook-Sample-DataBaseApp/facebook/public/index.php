<?php include "templates/header.php"; ?>



<div class="welcome-message">
    <h1 style="color: white; background-color: blue;"><b>Welcome to FaceBook</b></h1>
</div>

<style>
  /* Style for the welcome message */
  .welcome-message {
    text-align: center;
    font-size: 24px;
    color: white;
    padding: 20px;
  }
</style>
<ul>
  <li><a href="post.php"><strong>Post</strong></a> - add a post</li>
  <li><a href="album.php"><strong>Albums</strong></a> - add an album</li>
  <li><a href="homePost.php"><strong>Home</strong></a> - show last updates</li>
</ul>

<?php include "templates/footer.php"; ?>
