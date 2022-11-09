/* 1. Create database */
CREATE DATABASE blog_php;

/* 2. Create posts table */
CREATE TABLE `post` (
    `post_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `url_key` varchar(255) NOT NULL,
    `image_path` varchar(255) DEFAULT NULL,
    `content` text,
    `description` varchar(255) DEFAULT NULL,
    `published_date` datetime NOT NULL,
    PRIMARY KEY (`post_id`),
    UNIQUE KEY `url_key` (`url_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* 3. Add 3 posts into the posts table */
INSERT INTO post (title, url_key, content, description, published_date) VALUES ('Hello World', 'hello-world', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.', 'My first blog post', '2022-23-05 12:00:00');
INSERT INTO post (title, url_key, content, description, published_date) VALUES ('Second post', 'second-post', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using Content here, content here, making it look like readable English.', 'second psot','2022-23-09 12:00:00');
INSERT INTO post (title, url_key, content, description, published_date) VALUES ('My third post', 'my-third-post', 'There are many variations of passages of Lorem Ipsum available', 'Third post','2022-12-10 12:00:00');
