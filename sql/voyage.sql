-- API Voyage

CREATE DATABASE IF NOT EXISTS voyage_api
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE voyage_api;

-- regions
CREATE TABLE regions (
  id INT(3) NOT NULL AUTO_INCREMENT,
  slug VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

-- utilisateurs
CREATE TABLE users (
  id INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(100) NOT NULL,
  email VARCHAR(190) NOT NULL,
  password VARCHAR(255) NOT NULL,
  first_name VARCHAR(100) DEFAULT NULL,
  last_name VARCHAR(100) DEFAULT NULL,
  gender VARCHAR(30) DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  avatar VARCHAR(255) DEFAULT NULL,
  role ENUM('membre', 'admin') NOT NULL DEFAULT 'membre',
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY email (email),
  UNIQUE KEY username (username)
) ENGINE=InnoDB;

-- destinations
CREATE TABLE destinations (
  id INT(3) NOT NULL AUTO_INCREMENT,
  slug VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  region_id INT(3) NOT NULL,
  capital VARCHAR(255) DEFAULT NULL,
  description TEXT,
  image VARCHAR(255) DEFAULT NULL,
  lat VARCHAR(20) DEFAULT NULL,
  `long` VARCHAR(20) DEFAULT NULL,
  created_by INT(11) NOT NULL,
  PRIMARY KEY (id),
  KEY region_id (region_id),
  KEY created_by (created_by),
  CONSTRAINT fk_dest_region FOREIGN KEY (region_id) REFERENCES regions (id),
  CONSTRAINT fk_dest_user FOREIGN KEY (created_by) REFERENCES users (id)
) ENGINE=InnoDB;

-- commentaires
CREATE TABLE comments (
  id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  destination_id INT(3) NOT NULL,
  content TEXT NOT NULL,
  rating TINYINT(3) UNSIGNED DEFAULT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY user_id (user_id),
  KEY destination_id (destination_id),
  CONSTRAINT fk_comment_user FOREIGN KEY (user_id) REFERENCES users (id),
  CONSTRAINT fk_comment_dest FOREIGN KEY (destination_id) REFERENCES destinations (id)
) ENGINE=InnoDB;

-- demandes de contact
CREATE TABLE contact_requests (
  id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(190) NOT NULL,
  subject VARCHAR(190) NOT NULL,
  message TEXT NOT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY user_id (user_id),
  CONSTRAINT fk_contact_user FOREIGN KEY (user_id) REFERENCES users (id)
) ENGINE=InnoDB;

-- favoris
CREATE TABLE favorites (
  id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  destination_id VARCHAR(100) NOT NULL,
  destination_title VARCHAR(190) DEFAULT NULL,
  destination_image VARCHAR(255) DEFAULT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY user_id (user_id),
  CONSTRAINT fk_fav_user FOREIGN KEY (user_id) REFERENCES users (id)
) ENGINE=InnoDB;

INSERT INTO regions (slug, name) VALUES
('europe', 'Europe'),
('asie', 'Asie'),
('afrique', 'Afrique');

INSERT INTO users (username, email, password, first_name, last_name, gender, role, created_at) VALUES
('admin', 'admin@voyage.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Paul', 'Martin', 'homme', 'admin', NOW()),
('sophie', 'sophie@mail.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sophie', 'Durand', 'femme', 'membre', NOW());

INSERT INTO destinations (slug, name, region_id, capital, description, image, lat, `long`, created_by) VALUES
('paris-france', 'Paris', 1, 'Paris', 'Ville lumiere, Tour Eiffel, musees...', 'paris.jpg', '48.8566', '2.3522', 1),
('tokyo-japon', 'Tokyo', 2, 'Tokyo', 'Metropole moderne entre tradition et high-tech.', 'tokyo.jpg', '35.6762', '139.6503', 1),
('marrakech', 'Marrakech', 3, 'Marrakech', 'Medina, souks et desert pas tres loin.', 'marrakech.jpg', '31.6295', '-7.9811', 2);

INSERT INTO comments (user_id, destination_id, content, rating, created_at) VALUES
(2, 1, 'Super week-end, je recommande !', 5, NOW()),
(2, 2, 'Un peu cher mais ca vaut le coup.', 4, NOW());

INSERT INTO favorites (user_id, destination_id, destination_title, destination_image, created_at) VALUES
(2, '1', 'Paris', 'paris.jpg', NOW()),
(2, '3', 'Marrakech', 'marrakech.jpg', NOW());