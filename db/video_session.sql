-- VIDEO_SESSION

CREATE TABLE `wp_video_session` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `teacher_id` int(11) NOT NULL DEFAULT '0',
  `start_sell_date` datetime DEFAULT NULL,
  `end_sell_date` datetime DEFAULT NULL,
  `register_id` int(11) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `wp_video_session`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `wp_video_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `wp_video_session` ADD `item_id` INT NOT NULL DEFAULT '0' AFTER `register_id`;

ALTER TABLE `wp_video_session` ADD `price` INT NOT NULL DEFAULT '0' AFTER `item_id`, ADD `file_path` VARCHAR(255) NULL DEFAULT NULL AFTER `price`;

ALTER TABLE `wp_video_session` ADD `session_type` ENUM('online','offline') NOT NULL AFTER `file_path`;

ALTER TABLE `wp_video_session` ADD `video_link` VARCHAR(255) NULL DEFAULT NULL AFTER `session_type`;

ALTER TABLE `wp_video_session` CHANGE `session_type` `session_type` ENUM('online','offline') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'offline';