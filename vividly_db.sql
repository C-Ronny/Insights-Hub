DROP DATABASE IF EXISTS vividly_db;
CREATE DATABASE vividly_db;
USE vividly_db;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  email varchar() NOT NULL UNIQUE,
  phone_number VARCHAR(30) NOT NULL,
  password VARCHAR() NOT NULL,  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  
);

