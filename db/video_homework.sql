CREATE TABLE `wp_video_homework` (
  `id` int(11) NOT NULL,
  `video_session_id` int(11) NOT NULL DEFAULT '0',
  `student_id` int(11) NOT NULL DEFAULT '0',
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `wp_video_homework` ADD PRIMARY KEY (`id`);

ALTER TABLE `wp_video_homework` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `wp_video_homework` ADD `deleted` BOOLEAN NOT NULL DEFAULT FALSE AFTER `file_path`;

ALTER TABLE `wp_video_homework` ADD `updated_at` DATETIME NULL DEFAULT NULL AFTER `created_at`;
