CREATE TABLE `venue_rating` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `venue_id` INT(30) NOT NULL,  -- Foreign key to `venue` table
  `booking_id` INT(30) NOT NULL,  -- Foreign key to `venue_booking` table
  `rater_name` VARCHAR(255) NOT NULL,  -- Name of the rater
  `rater_email` VARCHAR(100) NOT NULL,  -- Email of the rater
  `otp` VARCHAR(6) DEFAULT NULL,  -- OTP sent to the rater's email
  `otp_verified` TINYINT(1) DEFAULT 0,  -- 0 = Not Verified, 1 = Verified via OTP
  `comment` TEXT DEFAULT NULL,  -- Optional feedback from the user
  `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`venue_id`) REFERENCES `venue`(`id`) ON DELETE CASCADE
  Foreign key (`booking_id`) REFERENCES `venue_booking`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `venue_rating_parameters` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `venue_rating_id` INT(30) NOT NULL,  -- Foreign key to `venue_rating` table
  `cleanliness` FLOAT NOT NULL CHECK (cleanliness BETWEEN 1 AND 5),
  `service` FLOAT NOT NULL CHECK (service BETWEEN 1 AND 5),
  `facilities` FLOAT NOT NULL CHECK (facilities BETWEEN 1 AND 5),
  `ambience` FLOAT NOT NULL CHECK (ambience BETWEEN 1 AND 5),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`venue_rating_id`) REFERENCES `venue_rating`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE rating_weights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    days_range_start INT NOT NULL,
    days_range_end INT NOT NULL,
    weight DECIMAL(5, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);