CREATE TABLE `aref_video_class` (
  `id` int(11) NOT NULL,
  `class` varchar(255) NOT NULL,
  `description` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


ALTER TABLE `aref_video_class`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `aref_video_class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `aref_video_class` ADD `deleted` BOOLEAN NOT NULL DEFAULT FALSE AFTER `description`;
