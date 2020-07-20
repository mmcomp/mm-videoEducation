CREATE TABLE `wp_video_chats` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL DEFAULT 0,
  `message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `session_id` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `wp_video_chats`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `wp_video_chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `divi_video_chats` ADD `updated_at` DATETIME NULL DEFAULT NULL AFTER `created_at`;