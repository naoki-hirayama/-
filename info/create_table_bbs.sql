CREATE TABLE bbs.post (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name VARCHAR(10),
    comment VARCHAR(100),
    color VARCHAR(6),
    password VARCHAR(100),
    picture 
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
