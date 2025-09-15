CREATE DATABASE IF NOT EXISTS despesas_pessoais
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE despesas_pessoais;

CREATE USER IF NOT EXISTS 'admin'@'%' IDENTIFIED BY 'admin123';
GRANT ALL PRIVILEGES ON despesas_pessoais.* TO 'admin'@'%';
FLUSH PRIVILEGES;

USE despesas_pessoais;

-- tabela users
CREATE TABLE IF NOT EXISTS `user` (
                                      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                      `username` VARCHAR(255) NOT NULL UNIQUE,
                                      `password_hash` VARCHAR(255) NOT NULL,
                                      `auth_key` VARCHAR(64) NOT NULL,
                                      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                      `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- tabela categorias
CREATE TABLE IF NOT EXISTS `categorias` (
                                            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                            `nome` VARCHAR(255) NOT NULL,
                                            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- tabela despesas
CREATE TABLE IF NOT EXISTS `despesas` (
                                          `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                          `descricao` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                                          `valor` DECIMAL(10,2) NOT NULL,
                                          `data` DATE NOT NULL,
                                          `categoria` ENUM('alimentação','transporte','lazer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                                          `user_id` INT NOT NULL,
                                          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                          `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                          INDEX (`user_id`),
                                          CONSTRAINT fk_despesas_user FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `categorias` (`nome`) VALUES ('alimentação'), ('transporte'), ('lazer');
