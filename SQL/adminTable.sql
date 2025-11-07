CREATE TABLE FPadmin(
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        name VARCHAR(100) NOT NULL,
                        email VARCHAR(100) UNIQUE NOT NULL,
                        password VARCHAR(255) NOT NULL,
                        createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);