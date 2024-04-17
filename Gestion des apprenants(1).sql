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
  `class_start_date` date,
  `class_end_date` date,
  `available_places` int
);

CREATE TABLE `gda_roles` (
  `role_id` int PRIMARY KEY,
  `role_name` varchar(255)
);

CREATE TABLE `gda_courses` (
  `course_id` int PRIMARY KEY,
  `class_id` int,
  `course_date` date,
  `course_start_time` time,
  `course_end_time` time,
  `course_randomCode` int(5)
);

CREATE TABLE `gda_user_class` (
  `user_id` int,
  `class_id` int
);

CREATE TABLE `gda_attendance` (
  `user_id` int,
  `course_id` int,
  `presence` bool,
  `delay` bool
);

ALTER TABLE `gda_users` ADD FOREIGN KEY (`role_id`) REFERENCES `gda_roles` (`role_id`);

ALTER TABLE `gda_courses` ADD FOREIGN KEY (`class_id`) REFERENCES `gda_classes` (`class_id`);

ALTER TABLE `gda_user_class` ADD FOREIGN KEY (`user_id`) REFERENCES `gda_users` (`user_id`);

ALTER TABLE `gda_user_class` ADD FOREIGN KEY (`class_id`) REFERENCES `gda_classes` (`class_id`);

ALTER TABLE `gda_attendance` ADD FOREIGN KEY (`user_id`) REFERENCES `gda_users` (`user_id`);

ALTER TABLE `gda_attendance` ADD FOREIGN KEY (`course_id`) REFERENCES `gda_courses` (`course_id`);
