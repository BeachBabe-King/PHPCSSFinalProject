CREATE TABLE FPAdmin (
                       userId INT AUTO_INCREMENT PRIMARY KEY,
                       firstName VARCHAR(50) NOT NULL,
                       lastName VARCHAR(50) NOT NULL,
                       email VARCHAR(100) UNIQUE NOT NULL,
                       password VARCHAR(255) NOT NULL,
                       phone VARCHAR(20) DEFAULT NULL,
                       address TEXT DEFAULT NULL,
                       isAdmin BOOLEAN DEFAULT FALSE,
                       createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);