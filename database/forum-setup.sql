DROP DATABASE IF EXISTS forum;
CREATE DATABASE forum;
USE forum;

CREATE TABLE user (
	user_id INT(8) NOT NULL AUTO_INCREMENT,
	user_name VARCHAR(30) NOT NULL,
    user_password VARCHAR(255) NOT NULL,
	user_email VARCHAR(255) NOT NULL,
	user_date	DATE NOT NULL,
	user_score INT(8) NOT NULL DEFAULT 0,
	UNIQUE INDEX user_name_unique_key (user_name),
	PRIMARY KEY (user_id)
);

# categories - There are main categories and subcategories. Each subcategory belongs to a main category
CREATE TABLE category (
	category_id INT(8) NOT NULL AUTO_INCREMENT,
	category_name VARCHAR(255) NOT NULL,
	UNIQUE INDEX category_name_unique_key (category_name),
	PRIMARY KEY (category_id)
);

CREATE TABLE subcategory (
	subcategory_id INT(8) NOT NULL AUTO_INCREMENT,
	subcategory_parent INT(8) NOT NULL,
	subcategory_name VARCHAR(255) NOT NULL,
	UNIQUE INDEX subcategory_name_unique_key (subcategory_name),
	PRIMARY KEY (subcategory_id)
);

CREATE TABLE post (
	post_id INT(8) NOT NULL AUTO_INCREMENT,
    post_subject VARCHAR(255) NOT NULL,
	post_content TEXT NOT NULL,
	post_date DATETIME NOT NULL,
	post_category INT(8) NOT NULL,
	post_creator INT(8) NOT NULL,
	post_score INT(8) NOT NULL DEFAULT 0,
	PRIMARY KEY (post_id)
);

# For counting post views
CREATE TABLE postview (
	view_id INT(11) NOT NULL AUTO_INCREMENT,
    post INT(8) NOT NULL,
    user_ip TEXT NOT NULL,
    PRIMARY KEY(view_id)
);

# comments - Using a tree data structure. Each comment can have infintely many child comments.
# Each child comment can have infinitely many child comments and so on...
# If comment parent is NULL, the comment is the first in its tree
CREATE TABLE comment (
	comment_id INT(8) NOT NULL AUTO_INCREMENT,
	comment_parent INT(8) DEFAULT NULL,
	comment_content TEXT NOT NULL,
	comment_date DATETIME NOT NULL,
	comment_post INT(8) NOT NULL,
	comment_creator INT(8) NOT NULL,
	comment_score INT(8) NOT NULL DEFAULT 0,
	PRIMARY KEY (comment_id)
);

# Bridge table between categories and user
CREATE TABLE moderatedcategory (
	category INT(8) NOT NULL,
	user INT(8) NOT NULL
);

# Table for profile pictures (stored as blobs)
CREATE TABLE userimg (
	img_id INT(8) NOT NULL AUTO_INCREMENT,
	img LONGBLOB NOT NULL,
    img_user INT(8) NOT NULL,
	PRIMARY KEY(img_id)
);

# Table for post thumbnails (stored as urls)
CREATE TABLE postimg (
	img_id INT(8) NOT NULL AUTO_INCREMENT,
	img_url TEXT NOT NULL,
	img_post INT(8) NOT NULL,
	PRIMARY KEY(img_id)
);


# Länka kategorier till subkategorier
# ON DELETE CASCADE: om en kategori tas bort tas alla underkategorier bort, ON UPDATE CASCADE: om ett category_id ändras ändras värdet i varje underkategory
ALTER TABLE subcategory ADD FOREIGN KEY(subcategory_parent) REFERENCES category(category_id) ON DELETE CASCADE ON UPDATE CASCADE;

# Länka posts till subkategorier
# ON DELETE CASCADE: om en underkategori tas bort tas alla posts bort, ON UPDATE CASCADE: om ett subcategory_id ändras ändras även varje post
ALTER TABLE post ADD FOREIGN KEY(post_category) REFERENCES subcategory(subcategory_id) ON DELETE CASCADE ON UPDATE CASCADE;

# Länka posts till användaren som skapar
# ON DELETE RESTRICT: användaren kan inte tas bort så länge det finns posts kvar (man kan vilja spara dessa)
ALTER TABLE post ADD FOREIGN KEY(post_creator) REFERENCES user(user_id) ON DELETE RESTRICT ON UPDATE CASCADE;

# Länka kommentarer till posts
ALTER TABLE comment ADD FOREIGN KEY(comment_post) REFERENCES post(post_id) ON DELETE CASCADE ON UPDATE CASCADE;

# Länka kommentarer till skapare
ALTER TABLE comment ADD FOREIGN KEY(comment_creator) REFERENCES user(user_id) ON DELETE RESTRICT ON UPDATE CASCADE;

# Länka moderatedcategories till user och category (lookup tables)
ALTER TABLE moderatedcategory ADD FOREIGN KEY(category) REFERENCES category(category_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE moderatedcategory ADD FOREIGN KEY(user) REFERENCES user(user_id) ON DELETE CASCADE ON UPDATE CASCADE;

# Länka profilbilder till användare och thumbnails till posts
ALTER TABLE userimg ADD FOREIGN KEY(img_user) REFERENCES user(user_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE postimg ADD FOREIGN KEY(img_post) REFERENCES post(post_id) ON DELETE CASCADE ON UPDATE CASCADE;

# Länka postview till post
ALTER TABLE postview ADD FOREIGN KEY(post) REFERENCES post(post_id) ON DELETE CASCADE ON UPDATE CASCADE;

#
# FILL DB WITH SAMPLE DATA
#

# USERS
INSERT INTO user VALUES (NULL, "thechorizo", "123", "c.oskar.lundin@gmail.com", CURDATE(), 0);
INSERT INTO user VALUES (NULL, "fluff", "321", "roger.pontare@hotmail.com", CURDATE(), 0);
INSERT INTO user VALUES (NULL, "nilsmfn", "123", "hej.hej@hej.hej", CURDATE(), 0);

# MAIN CATEGORIES
INSERT INTO category VALUES (NULL, "nature"); #1
INSERT INTO category VALUES (NULL, "technology"); #2
INSERT INTO category VALUES (NULL, "language"); #3
INSERT INTO category VALUES(NULL, "music"); #4
INSERT INTO category VALUES(NULL, "food"); #5
INSERT INTO category VALUES(NULL, "games"); #6

# SUB CATEGORIES
INSERT INTO subcategory VALUES (NULL, 1, "flowers"); #1
INSERT INTO subcategory VALUES (NULL, 1, "animals"); #2
INSERT INTO subcategory VALUES (NULL, 1, "trees"); #3

INSERT INTO subcategory VALUES (NULL, 2, "programming"); #4
INSERT INTO subcategory VALUES (NULL, 2, "electronics"); #5
INSERT INTO subcategory VALUES (NULL, 2, "vehicles"); #6

INSERT INTO subcategory VALUES (NULL, 3, "english"); #7
INSERT INTO subcategory VALUES (NULL, 3, "swedish"); #8
INSERT INTO subcategory VALUES (NULL, 3, "spanish"); #9

INSERT INTO subcategory VALUES (NULL, 4, "rock"); #10
INSERT INTO subcategory VALUES (NULL, 4, "metal"); #11
INSERT INTO subcategory VALUES (NULL, 4, "elevator music"); #12

INSERT INTO subcategory VALUES (NULL, 5, "normal"); #13
INSERT INTO subcategory VALUES (NULL, 5, "vegetarian"); #14
INSERT INTO subcategory VALUES (NULL, 5, "vegan"); #15

INSERT INTO subcategory VALUES (NULL, 6, "pc"); #16
INSERT INTO subcategory VALUES (NULL, 6, "nintendo switch"); #17
INSERT INTO subcategory VALUES (NULL, 6, "playstation 4"); #18

# POSTS
INSERT INTO post VALUES (
	NULL, 
	"First post to flowers", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam justo tellus, suscipit ut semper sed, interdum in est. Vivamus a felis nisl. Donec egestas laoreet faucibus. Morbi id orci porttitor, rutrum nibh ut, lobortis mi. Quisque lorem libero, placerat condimentum volutpat a, pretium at leo. In non justo vel libero luctus tempus. Nulla efficitur mauris a sodales viverra. In tincidunt dui nec libero malesuada, eget tempor nunc mollis.", 
	CURDATE(), 
	1, 
	1, 
	0
);
INSERT INTO post VALUES (
	NULL, 
	"First post to animals", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam justo tellus, suscipit ut semper sed, interdum in est. Vivamus a felis nisl. Donec egestas laoreet faucibus. Morbi id orci porttitor, rutrum nibh ut, lobortis mi. Quisque lorem libero, placerat condimentum volutpat a, pretium at leo. In non justo vel libero luctus tempus. Nulla efficitur mauris a sodales viverra. In tincidunt dui nec libero malesuada, eget tempor nunc mollis.", 
	CURDATE(), 
	2, 
	2, 
	0
);
INSERT INTO post VALUES (
	NULL, 
	"First post to trees", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam justo tellus, suscipit ut semper sed, interdum in est. Vivamus a felis nisl. Donec egestas laoreet faucibus. Morbi id orci porttitor, rutrum nibh ut, lobortis mi. Quisque lorem libero, placerat condimentum volutpat a, pretium at leo. In non justo vel libero luctus tempus. Nulla efficitur mauris a sodales viverra. In tincidunt dui nec libero malesuada, eget tempor nunc mollis.", 
	CURDATE(), 
	3, 
	3, 
	0
);
INSERT INTO post VALUES (
	NULL, 
	"First post to programming", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam justo tellus, suscipit ut semper sed, interdum in est. Vivamus a felis nisl. Donec egestas laoreet faucibus. Morbi id orci porttitor, rutrum nibh ut, lobortis mi. Quisque lorem libero, placerat condimentum volutpat a, pretium at leo. In non justo vel libero luctus tempus. Nulla efficitur mauris a sodales viverra. In tincidunt dui nec libero malesuada, eget tempor nunc mollis.", 
	CURDATE(), 
	4, 
	1, 
	0
);
INSERT INTO post VALUES (
	NULL, 
	"First post to electronics", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam justo tellus, suscipit ut semper sed, interdum in est. Vivamus a felis nisl. Donec egestas laoreet faucibus. Morbi id orci porttitor, rutrum nibh ut, lobortis mi. Quisque lorem libero, placerat condimentum volutpat a, pretium at leo. In non justo vel libero luctus tempus. Nulla efficitur mauris a sodales viverra. In tincidunt dui nec libero malesuada, eget tempor nunc mollis.", 
	CURDATE(), 
	5, 
	2, 
	0
);
INSERT INTO post VALUES (
	NULL, 
	"First post to vehicles", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam justo tellus, suscipit ut semper sed, interdum in est. Vivamus a felis nisl. Donec egestas laoreet faucibus. Morbi id orci porttitor, rutrum nibh ut, lobortis mi. Quisque lorem libero, placerat condimentum volutpat a, pretium at leo. In non justo vel libero luctus tempus. Nulla efficitur mauris a sodales viverra. In tincidunt dui nec libero malesuada, eget tempor nunc mollis.", 
	CURDATE(), 
	6, 
	3, 
	0
);
INSERT INTO post VALUES (
	NULL, 
	"First post to english", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam justo tellus, suscipit ut semper sed, interdum in est. Vivamus a felis nisl. Donec egestas laoreet faucibus. Morbi id orci porttitor, rutrum nibh ut, lobortis mi. Quisque lorem libero, placerat condimentum volutpat a, pretium at leo. In non justo vel libero luctus tempus. Nulla efficitur mauris a sodales viverra. In tincidunt dui nec libero malesuada, eget tempor nunc mollis.", 
	CURDATE(), 
	7, 
	1, 
	0
);
INSERT INTO post VALUES (
	NULL, 
	"First post to swedish", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam justo tellus, suscipit ut semper sed, interdum in est. Vivamus a felis nisl. Donec egestas laoreet faucibus. Morbi id orci porttitor, rutrum nibh ut, lobortis mi. Quisque lorem libero, placerat condimentum volutpat a, pretium at leo. In non justo vel libero luctus tempus. Nulla efficitur mauris a sodales viverra. In tincidunt dui nec libero malesuada, eget tempor nunc mollis.", 
	CURDATE(), 
	8, 
	2, 
	0
);
INSERT INTO post VALUES (
	NULL, 
	"First post to spanish", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam justo tellus, suscipit ut semper sed, interdum in est. Vivamus a felis nisl. Donec egestas laoreet faucibus. Morbi id orci porttitor, rutrum nibh ut, lobortis mi. Quisque lorem libero, placerat condimentum volutpat a, pretium at leo. In non justo vel libero luctus tempus. Nulla efficitur mauris a sodales viverra. In tincidunt dui nec libero malesuada, eget tempor nunc mollis.", 
	CURDATE(), 
	9, 
	3, 
	0
);
INSERT INTO post VALUES (
	NULL, 
	"First post to rock", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam justo tellus, suscipit ut semper sed, interdum in est. Vivamus a felis nisl. Donec egestas laoreet faucibus. Morbi id orci porttitor, rutrum nibh ut, lobortis mi. Quisque lorem libero, placerat condimentum volutpat a, pretium at leo. In non justo vel libero luctus tempus. Nulla efficitur mauris a sodales viverra. In tincidunt dui nec libero malesuada, eget tempor nunc mollis.", 
	CURDATE(), 
	10, 
	1, 
	0
);
INSERT INTO post VALUES (
	NULL, 
	"First post to metal", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam justo tellus, suscipit ut semper sed, interdum in est. Vivamus a felis nisl. Donec egestas laoreet faucibus. Morbi id orci porttitor, rutrum nibh ut, lobortis mi. Quisque lorem libero, placerat condimentum volutpat a, pretium at leo. In non justo vel libero luctus tempus. Nulla efficitur mauris a sodales viverra. In tincidunt dui nec libero malesuada, eget tempor nunc mollis.", 
	CURDATE(), 
	11,
	2, 
	0
);
INSERT INTO post VALUES (
	NULL, 
	"First post to elevator music", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam justo tellus, suscipit ut semper sed, interdum in est. Vivamus a felis nisl. Donec egestas laoreet faucibus. Morbi id orci porttitor, rutrum nibh ut, lobortis mi. Quisque lorem libero, placerat condimentum volutpat a, pretium at leo. In non justo vel libero luctus tempus. Nulla efficitur mauris a sodales viverra. In tincidunt dui nec libero malesuada, eget tempor nunc mollis.", 
	CURDATE(), 
	12, 
	3, 
	0
);
INSERT INTO post VALUES (
	NULL, 
	"First post to normal", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam justo tellus, suscipit ut semper sed, interdum in est. Vivamus a felis nisl. Donec egestas laoreet faucibus. Morbi id orci porttitor, rutrum nibh ut, lobortis mi. Quisque lorem libero, placerat condimentum volutpat a, pretium at leo. In non justo vel libero luctus tempus. Nulla efficitur mauris a sodales viverra. In tincidunt dui nec libero malesuada, eget tempor nunc mollis.", 
	CURDATE(), 
	13, 
	1, 
	0
);
INSERT INTO post VALUES (
	NULL, 
	"First post to vegetarian", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam justo tellus, suscipit ut semper sed, interdum in est. Vivamus a felis nisl. Donec egestas laoreet faucibus. Morbi id orci porttitor, rutrum nibh ut, lobortis mi. Quisque lorem libero, placerat condimentum volutpat a, pretium at leo. In non justo vel libero luctus tempus. Nulla efficitur mauris a sodales viverra. In tincidunt dui nec libero malesuada, eget tempor nunc mollis.", 
	CURDATE(), 
	14, 
	2, 
	0
);
INSERT INTO post VALUES (
	NULL, 
	"First post to vegan", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam justo tellus, suscipit ut semper sed, interdum in est. Vivamus a felis nisl. Donec egestas laoreet faucibus. Morbi id orci porttitor, rutrum nibh ut, lobortis mi. Quisque lorem libero, placerat condimentum volutpat a, pretium at leo. In non justo vel libero luctus tempus. Nulla efficitur mauris a sodales viverra. In tincidunt dui nec libero malesuada, eget tempor nunc mollis.", 
	CURDATE(), 
	15, 
	3, 
	0
);
INSERT INTO post VALUES (
	NULL, 
	"First post to pc", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam justo tellus, suscipit ut semper sed, interdum in est. Vivamus a felis nisl. Donec egestas laoreet faucibus. Morbi id orci porttitor, rutrum nibh ut, lobortis mi. Quisque lorem libero, placerat condimentum volutpat a, pretium at leo. In non justo vel libero luctus tempus. Nulla efficitur mauris a sodales viverra. In tincidunt dui nec libero malesuada, eget tempor nunc mollis.", 
	CURDATE(), 
	16, 
	1, 
	0
);
INSERT INTO post VALUES (
	NULL, 
	"First post to nintendo switch", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam justo tellus, suscipit ut semper sed, interdum in est. Vivamus a felis nisl. Donec egestas laoreet faucibus. Morbi id orci porttitor, rutrum nibh ut, lobortis mi. Quisque lorem libero, placerat condimentum volutpat a, pretium at leo. In non justo vel libero luctus tempus. Nulla efficitur mauris a sodales viverra. In tincidunt dui nec libero malesuada, eget tempor nunc mollis.", 
	CURDATE(), 
	17, 
	2, 
	0
);
INSERT INTO post VALUES (
	NULL, 
	"First post to playstation 4", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam justo tellus, suscipit ut semper sed, interdum in est. Vivamus a felis nisl. Donec egestas laoreet faucibus. Morbi id orci porttitor, rutrum nibh ut, lobortis mi. Quisque lorem libero, placerat condimentum volutpat a, pretium at leo. In non justo vel libero luctus tempus. Nulla efficitur mauris a sodales viverra. In tincidunt dui nec libero malesuada, eget tempor nunc mollis.", 
	CURDATE(), 
	18, 
	3, 
	0
);

# COMMENTS - LVL 1
#INSERT INTO comment VALUES (NULL, NULL, "Nice meme!", CURDATE(), 1, 1, 0); #1
#INSERT INTO comment VALUES (NULL, NULL, "Cool!", CURDATE(), 2, 2, 0); #2
#INSERT INTO comment VALUES (NULL, NULL, "Alright!", CURDATE(), 3, 3, 0); #3
# COMMENTS - LVL 2
#INSERT INTO comment VALUES (NULL, 1, "I agree!", CURDATE(), 1, 2, 0); #4
# COMMENTS - LVL 3
#INSERT INTO comment VALUES (NULL, 1, "Me too!", CURDATE(), 1, 3, 0); #5

# Gör TheChorizo till mod över memes
#INSERT INTO moderatedcategory VALUES (3, 1);
