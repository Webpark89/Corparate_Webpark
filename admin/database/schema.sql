-- WEBPARK Database Schema
-- Database: WEBPARK

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admins
-- ----------------------------
DROP TABLE IF EXISTS admins;
CREATE TABLE admins (
  id INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(100) NOT NULL,
  email VARCHAR(191) DEFAULT NULL,
  password_hash VARCHAR(255) NOT NULL,
  display_name VARCHAR(191) DEFAULT NULL,
  role ENUM('admin','editor','author') NOT NULL DEFAULT 'admin',
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  last_login DATETIME DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY username (username),
  UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(191) NOT NULL,
  slug VARCHAR(191) NOT NULL,
  description TEXT,
  parent_id INT(11) DEFAULT NULL,
  sort_order INT(11) NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY slug (slug),
  KEY parent_id (parent_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for authors
-- ----------------------------
DROP TABLE IF EXISTS authors;
CREATE TABLE authors (
  id INT(11) NOT NULL AUTO_INCREMENT,
  display_name VARCHAR(191) NOT NULL,
  slug VARCHAR(191) NOT NULL,
  email VARCHAR(191) DEFAULT NULL,
  bio TEXT,
  avatar VARCHAR(255) DEFAULT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY slug (slug),
  UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for company
-- ----------------------------
DROP TABLE IF EXISTS company;
CREATE TABLE company (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name_th VARCHAR(255) NOT NULL,
  name_en VARCHAR(255) DEFAULT NULL,
  tagline VARCHAR(255) DEFAULT NULL,
  description TEXT,
  about LONGTEXT,
  vision TEXT,
  mission TEXT,
  logo VARCHAR(255) DEFAULT NULL,
  favicon VARCHAR(255) DEFAULT NULL,
  email VARCHAR(191) DEFAULT NULL,
  phone VARCHAR(50) DEFAULT NULL,
  fax VARCHAR(50) DEFAULT NULL,
  address_th TEXT,
  address_en TEXT,
  google_maps TEXT,
  facebook VARCHAR(255) DEFAULT NULL,
  line VARCHAR(100) DEFAULT NULL,
  youtube VARCHAR(255) DEFAULT NULL,
  instagram VARCHAR(255) DEFAULT NULL,
  linkedin VARCHAR(255) DEFAULT NULL,
  tax_id VARCHAR(50) DEFAULT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS article;
CREATE TABLE article (
  id INT(11) NOT NULL AUTO_INCREMENT,
  slug VARCHAR(191) NOT NULL,
  title VARCHAR(255) NOT NULL,
  summary TEXT,
  content LONGTEXT,
  cover_image VARCHAR(255) DEFAULT NULL,
  cover_image_alt VARCHAR(255) DEFAULT NULL,
  category_id INT(11) DEFAULT NULL,
  author_id INT(11) DEFAULT NULL,
  meta_title VARCHAR(255) DEFAULT NULL,
  meta_description TEXT,
  meta_keywords TEXT,
  view_count INT(11) NOT NULL DEFAULT 0,
  status ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
  featured TINYINT(1) NOT NULL DEFAULT 0,
  published_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY slug (slug),
  KEY category_id (category_id),
  KEY author_id (author_id),
  KEY status (status),
  KEY published_at (published_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for portfolio
-- ----------------------------
DROP TABLE IF EXISTS portfolio;
CREATE TABLE portfolio (
  id INT(11) NOT NULL AUTO_INCREMENT,
  slug VARCHAR(191) NOT NULL,
  title VARCHAR(255) NOT NULL,
  subtitle VARCHAR(255) DEFAULT NULL,
  description TEXT,
  content LONGTEXT,
  cover_image VARCHAR(255) DEFAULT NULL,
  cover_image_alt VARCHAR(255) DEFAULT NULL,
  client_name VARCHAR(255) DEFAULT NULL,
  project_date DATE DEFAULT NULL,
  project_url VARCHAR(255) DEFAULT NULL,
  technologies TEXT,
  meta_title VARCHAR(255) DEFAULT NULL,
  meta_description TEXT,
  meta_keywords TEXT,
  view_count INT(11) NOT NULL DEFAULT 0,
  status ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
  featured TINYINT(1) NOT NULL DEFAULT 0,
  sort_order INT(11) NOT NULL DEFAULT 0,
  published_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY slug (slug),
  KEY status (status),
  KEY featured (featured),
  KEY sort_order (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for partners
-- ----------------------------
DROP TABLE IF EXISTS partners;
CREATE TABLE partners (
  id INT(11) NOT NULL AUTO_INCREMENT,
  slug VARCHAR(191) NOT NULL,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  logo VARCHAR(255) DEFAULT NULL,
  website VARCHAR(255) DEFAULT NULL,
  category_id INT(11) DEFAULT NULL,
  meta_title VARCHAR(255) DEFAULT NULL,
  meta_description TEXT,
  meta_keywords TEXT,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT(11) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY slug (slug),
  KEY category_id (category_id),
  KEY is_active (is_active),
  KEY sort_order (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for partner_categories
-- ----------------------------
DROP TABLE IF EXISTS partner_categories;
CREATE TABLE partner_categories (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(191) NOT NULL,
  slug VARCHAR(191) NOT NULL,
  description TEXT,
  icon VARCHAR(100) DEFAULT NULL,
  sort_order INT(11) NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY slug (slug),
  KEY sort_order (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for service
-- ----------------------------
DROP TABLE IF EXISTS service;
CREATE TABLE service (
  id INT(11) NOT NULL AUTO_INCREMENT,
  slug VARCHAR(191) NOT NULL,
  title VARCHAR(255) NOT NULL,
  subtitle VARCHAR(255) DEFAULT NULL,
  summary TEXT,
  description LONGTEXT,
  cover_image VARCHAR(255) DEFAULT NULL,
  cover_image_alt VARCHAR(255) DEFAULT NULL,
  icon VARCHAR(100) DEFAULT NULL,
  details_json LONGTEXT,
  features_json LONGTEXT,
  meta_title VARCHAR(255) DEFAULT NULL,
  meta_description TEXT,
  meta_keywords TEXT,
  view_count INT(11) NOT NULL DEFAULT 0,
  status ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
  featured TINYINT(1) NOT NULL DEFAULT 0,
  sort_order INT(11) NOT NULL DEFAULT 0,
  published_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY slug (slug),
  KEY status (status),
  KEY featured (featured),
  KEY sort_order (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for service_features
-- ----------------------------
DROP TABLE IF EXISTS service_features;
CREATE TABLE service_features (
  id INT(11) NOT NULL AUTO_INCREMENT,
  service_id INT(11) NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  icon VARCHAR(100) DEFAULT NULL,
  sort_order INT(11) NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY service_id (service_id),
  KEY sort_order (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for erp_modules
-- ----------------------------
DROP TABLE IF EXISTS erp_modules;
CREATE TABLE erp_modules (
  id INT(11) NOT NULL AUTO_INCREMENT,
  slug VARCHAR(191) NOT NULL,
  name VARCHAR(255) NOT NULL,
  name_en VARCHAR(255) DEFAULT NULL,
  description TEXT,
  icon VARCHAR(100) DEFAULT NULL,
  cover_image VARCHAR(255) DEFAULT NULL,
  cover_image_alt VARCHAR(255) DEFAULT NULL,
  summary TEXT,
  features_json LONGTEXT,
  benefits_json LONGTEXT,
  meta_title VARCHAR(255) DEFAULT NULL,
  meta_description TEXT,
  meta_keywords TEXT,
  view_count INT(11) NOT NULL DEFAULT 0,
  status ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
  featured TINYINT(1) NOT NULL DEFAULT 0,
  sort_order INT(11) NOT NULL DEFAULT 0,
  published_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY slug (slug),
  KEY status (status),
  KEY featured (featured),
  KEY sort_order (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ----------------------------
-- Table structure for review
-- ----------------------------
DROP TABLE IF EXISTS review;
CREATE TABLE review (
  id INT(11) NOT NULL AUTO_INCREMENT,
  customer_name VARCHAR(255) NOT NULL,
  customer_company VARCHAR(255) DEFAULT NULL,
  customer_position VARCHAR(255) DEFAULT NULL,
  customer_avatar VARCHAR(255) DEFAULT NULL,
  rating TINYINT(1) NOT NULL,
  title VARCHAR(255) DEFAULT NULL,
  content TEXT,
  is_featured TINYINT(1) NOT NULL DEFAULT 0,
  status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  published_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY rating (rating),
  KEY status (status),
  KEY is_featured (is_featured)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS settings;
CREATE TABLE settings (
  id INT(11) NOT NULL AUTO_INCREMENT,
  config_key VARCHAR(191) NOT NULL,
  config_value TEXT,
  config_group VARCHAR(100) NOT NULL DEFAULT 'general',
  description TEXT,
  is_protected TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY config_key (config_key),
  KEY config_group (config_group)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;