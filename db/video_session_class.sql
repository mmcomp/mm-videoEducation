
CREATE TABLE `aref_video_session_class` (
  `id` int(11) NOT NULL,
  `video_class` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE `aref_video_session_class`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `aref_video_session_class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `aref_video_session_class` ADD `deleted` BOOLEAN NOT NULL DEFAULT FALSE AFTER `admin_id`;