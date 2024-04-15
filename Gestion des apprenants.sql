CREATE TABLE `gda_users` (
  `user_id` int PRIMARY KEY,
  `first_name` varchar(255),
  `last_name` varchar(255),
  `password` varchar(255),
  `email` varchar(255),
  `activation` bool,
  `role_id` int
);

CREATE TABLE `gda_classes` (
  `class_id` int PRIMARY KEY,
  `class_name` varchar(255),
  `class_startDate` date,
  `class_endDate` date,
  `places_available` int
);

CREATE TABLE `gda_roles` (
  `role_id` int PRIMARY KEY,
  `role_name` varchar(255)
);

CREATE TABLE `gda_courses` (
  `course_id` int PRIMARY KEY,
  `class_id` int,
  `course_date` date,
  `course_startTime` time,
  `course_endTime` time,
  `course_randomCode` int(5)
);

CREATE TABLE `gda_attendance` (
  `attend_id` int PRIMARY KEY,
  `user_id` int,
  `course_id` int,
  `attend_date` date,
  `attend_status` varchar(255)
);

ALTER TABLE `gda_users` ADD FOREIGN KEY (`role_id`) REFERENCES `gda_roles` (`role_id`);

ALTER TABLE `gda_courses` ADD FOREIGN KEY (`class_id`) REFERENCES `gda_classes` (`class_id`);

ALTER TABLE `gda_attendance` ADD FOREIGN KEY (`user_id`) REFERENCES `gda_users` (`user_id`);

ALTER TABLE `gda_attendance` ADD FOREIGN KEY (`course_id`) REFERENCES `gda_courses` (`course_id`);
