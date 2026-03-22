-- Create database
CREATE DATABASE IF NOT EXISTS portfolio_db;
USE portfolio_db;

-- Projects table
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image_url VARCHAR(255),
    technologies VARCHAR(255),
    project_url VARCHAR(255),
    github_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'archived') DEFAULT 'active'
);

-- Blog posts table
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT,
    image_url VARCHAR(255),
    author VARCHAR(100) DEFAULT 'Tesfaw Amare',
    category VARCHAR(100),
    tags VARCHAR(255),
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('published', 'draft') DEFAULT 'published'
);

-- Contact messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Newsletter subscribers table
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    status ENUM('active', 'unsubscribed') DEFAULT 'active',
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unsubscribed_at TIMESTAMP NULL
);

-- Visitor statistics table
CREATE TABLE IF NOT EXISTS visitor_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45),
    user_agent TEXT,
    page_visited VARCHAR(255),
    visit_date DATE,
    visit_count INT DEFAULT 1,
    last_visit TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_visitor (ip_address, visit_date)
);

-- Insert sample projects
INSERT INTO projects (title, description, technologies, image_url) VALUES
('Portfolio Website', 'Modern, responsive portfolio showcasing creative work and projects.', 'HTML5, CSS3, JavaScript, PHP', 'project3.jpg');

-- Insert sample blog posts
INSERT INTO blog_posts (title, content, excerpt, category, tags) VALUES
('Getting Started with PHP and MySQL', 'Learn how to build dynamic websites using PHP and MySQL. This comprehensive guide covers database connections, CRUD operations, and security best practices...', 'A beginner-friendly guide to PHP and MySQL development', 'Web Development', 'PHP, MySQL, Backend'),
('Modern CSS Techniques', 'Explore the latest CSS features including Grid, Flexbox, and custom properties. Learn how to create responsive layouts without frameworks...', 'Master modern CSS for beautiful responsive designs', 'Frontend', 'CSS, Design, Responsive'),
('JavaScript Best Practices', 'Write cleaner, more maintainable JavaScript code. This article covers ES6+ features, async/await, and common design patterns...', 'Essential JavaScript tips for better code quality', 'JavaScript', 'JavaScript, Best Practices, ES6');

-- Create indexes for better performance
CREATE INDEX idx_projects_status ON projects(status);
CREATE INDEX idx_blog_status ON blog_posts(status);
CREATE INDEX idx_contact_status ON contact_messages(status);
CREATE INDEX idx_newsletter_status ON newsletter_subscribers(status);
CREATE INDEX idx_visitor_date ON visitor_stats(visit_date);
