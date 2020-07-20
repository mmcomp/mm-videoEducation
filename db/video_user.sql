CREATE TABLE `wp_video_users` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `principal_id` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `wp_video_users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `wp_video_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;