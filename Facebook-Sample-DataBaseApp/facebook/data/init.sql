CREATE DATABASE facebook120;

use facebook120;

CREATE TABLE post(
	userId INT(6) NOT NULL ,
	postTitle VARCHAR(100) NOT NULL,
	postType VARCHAR(30) NOT NULL

);

ALTER TABLE post
ADD postId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY;

ALTER TABLE post
ADD postDate TIMESTAMP;


CREATE TABLE album (
    albumId INT(6)  AUTO_INCREMENT PRIMARY KEY,
    albumName VARCHAR(100) NOT NULL,
    description VARCHAR(1000) NOT NULL,
    albumDate TIMESTAMP
);

CREATE TABLE post_albums (
    postAlbumId INT PRIMARY KEY AUTO_INCREMENT ,
    AlbumId INT(6) ,
    PostId INT(6) UNSIGNED,
    FOREIGN KEY (AlbumId) REFERENCES album(albumId),
    FOREIGN KEY (PostId) REFERENCES post(postId)
);




-- Create an index on the 'userId' column in the 'post' table
CREATE INDEX idx_userId ON post(userId);

-- Create an index on the 'albumName' column in the 'album' table
CREATE INDEX idx_albumName ON album(albumName);






