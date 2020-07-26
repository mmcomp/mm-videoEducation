CREATE TABLE `divi_video_pay` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `start_pay_amount` int(11) NOT NULL,
  `first_pay_date` datetime NOT NULL,
  `first_pay_amount` int(11) NOT NULL,
  `second_pay_date` datetime NOT NULL,
  `second_pay_amount` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

ALTER TABLE `divi_video_pay`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `divi_video_pay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `divi_video_pay` ADD `deleted` BOOLEAN NOT NULL DEFAULT FALSE AFTER `second_pay_amount`;