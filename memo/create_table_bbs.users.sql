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