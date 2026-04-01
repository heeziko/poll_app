-- POLL Application Database Schema

CREATE DATABASE IF NOT EXISTS POLL;
USE POLL;

CREATE TABLE IF NOT EXISTS responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visit_source VARCHAR(100),
    other_visit_source VARCHAR(255),
    purpose TEXT, -- JSON format
    first_impression TEXT,
    search_ease VARCHAR(100),
    useful_content TEXT,
    desired_features TEXT, -- JSON format
    other_feature VARCHAR(255),
    recommend_score INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
