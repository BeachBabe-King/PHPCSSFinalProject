CREATE TABLE FPproduct
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(200)   NOT NULL,
    author      VARCHAR(255) NOT NULL,
    description TEXT,
    price       DECIMAL(10, 2) NOT NULL,
    image       VARCHAR(255),
    category    VARCHAR(100),
    pageCount   INT,
    stock       INT NOT NULL DEFAULT 0 CHECK (stock >= 0),
    isFeatured TINYINT(1) DEFAULT 0,
    createdAt   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);