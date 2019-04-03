CREATE TABLE bbs.users (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name VARCHAR(25) NOT NULL,
    login_id VARCHAR(255) NOT NULL BINARY UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    
    
);
ALTER TABLE post modify user_id text after picture;     
ALTER TABLE post CHANGE COLUMN user_id user_id int(11);
ALTER TABLE users CHANGE COLUMN password password  VARCHAR(255) NOT NULL binary;
INSERT INTO bbs.post (id,name,comment,color,password,picture,user_id,created_at) VALUES (0,'test','test','red',null,null,1,now());
ALTER TABLE users ADD picture VARCHAR(100) AFTER password;
ALTER TABLE users ADD comment VARCHAR(50) AFTER picture;
ALTER TABLE users CHANGE profile_comment comment VARCHAR(60);


CREATE TABLE bbs.replies (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name VARCHAR(25) NOT NULL,
    comment VARCHAR(100) NOT NULL,
    color VARCHAR(6) NOT NULL,
    password VARCHAR(100),
    picture  VARCHAR(100),
    user_id INT(11),
    post_id INT(11) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);