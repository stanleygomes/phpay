CREATE TABLE `store` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NULL,
    `razao_social` varchar(255) NULL,
    `cpf_cnpj` varchar(255) NULL,
    `logo_url` varchar(255) NULL,
    `email` varchar(255) NULL,
    `phone` varchar(255) NULL,
    `whatsapp` varchar(255) NULL,
    `zipcode` varchar(255) NULL,
    `street` varchar(255) NULL,
    `number` int(11) NULL,
    `complement` varchar(255) NULL,
    `district` varchar(255) NULL,
    `city` varchar(255) NULL,
    `state` varchar(255) NULL,
    `facebook_url` varchar(255) NULL,
    `instagram_url` varchar(255) NULL,
    `youtube_url` varchar(255) NULL,
    `twitter_url` varchar(255) NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_by` int(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE `product` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `category_id` int(10) NOT NULL,
    `code` varchar(255) NOT NULL,
    `featured` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL,
    `title` varchar(255) NOT NULL,
    `price` float(8,2) NOT NULL,
    `description` text NULL,
    `description_short` varchar(255) NOT NULL,
    `stock_qty` int(10) NOT NULL,
    `evaluation_rate` float(8,2) NOT NULL DEFAULT 0,
    `photo_main_url` varchar(255) NULL,
    `more_details` text NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_by` int(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE `product_photo` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `product_id` int(10) NOT NULL,
    `photo_url` varchar(255) NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_by` int(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE `product_stock_movement` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `product_id` int(10) NOT NULL,
    `value` int(10) NOT NULL,
    `action` varchar(255) NOT NULL, -- ADD, REMOVE
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_by` int(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE `product_question` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `user_id` int(10) NOT NULL,
    `product_id` int(10) NOT NULL,
    `question` text NOT NULL,
    `answer` text NULL,
    `answered_by` int(10) NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_by` int(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE `wishlist_item` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `user_id` int(10) NOT NULL,
    `product_id` int(10) NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_by` int(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE `featured` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NULL,
    `photo_url` varchar(255) NULL,
    `position` int(10) NULL,
    `placement` varchar(255) NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_by` int(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE `payment_methods_available` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `gateway` varchar(255) NULL,
    `method_id` varchar(255) NULL,
    `method_name` varchar(255) NULL,
    `method_code` varchar(255) NULL,
    `method_type` varchar(255) NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_by` int(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE `address` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `user_id` int(10) NOT NULL,
    `name` varchar(255) NULL,
    `zipcode` varchar(255) NULL,
    `street` varchar(255) NULL,
    `number` int(11) NULL,
    `complement` varchar(255) NULL,
    `district` varchar(255) NULL,
    `city` varchar(255) NULL,
    `state` varchar(255) NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_by` int(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE `category` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_by` int(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE `contact` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NULL,
    `email` varchar(255) NULL,
    `phone` varchar(255) NULL,
    `message` text NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_by` int(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE `contact_reply` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `contact_id` int(10) NOT NULL,
    `message` text NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_by` int(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE `transaction` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `customer_id` int(10) NOT NULL,
    `qty` int(10) NOT NULL,
    `payment_method_id` int(10) NOT NULL,
    `total_price` float(8,2) NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_by` int(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE `transaction_history` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `transaction_id` int(10) NOT NULL,
    `status` varchar(255) NOT NULL,
    `status_detail` varchar(255) NOT NULL,
    `message` varchar(255) NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_by` int(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `profile` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NULL,
  `sex` varchar(255) NULL,
  `cpf` varchar(255) NULL,
  `born_at` timestamp NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE `user_login_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE `user_password_reset` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

-- CREATE TABLE `product_review` (
--     `id` int(10) NOT NULL AUTO_INCREMENT,
--     `product_id` int(10) NOT NULL,
--     `cart_id` int(10) NOT NULL,
--     `evaluation` int(10) NOT NULL,
--     `description` varchar(255) NOT NULL,
--     `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
--     `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
--     `deleted_at` timestamp NULL DEFAULT NULL,
--     `created_by` int(10) NOT NULL,
--     PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1;

-- ALTER TABLE `product_review` ADD FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

ALTER TABLE `wishlist_item` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
ALTER TABLE `wishlist_item` ADD FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);
ALTER TABLE `category` ADD FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);
ALTER TABLE `address` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
ALTER TABLE `address` ADD FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);
ALTER TABLE `product` ADD FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);
ALTER TABLE `product` ADD FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);
ALTER TABLE `product_photo` ADD FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);
ALTER TABLE `product_photo` ADD FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);
ALTER TABLE `product_stock_movement` ADD FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);
ALTER TABLE `product_stock_movement` ADD FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);
ALTER TABLE `product_question` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
ALTER TABLE `product_question` ADD FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);
ALTER TABLE `product_question` ADD FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);
ALTER TABLE `product_question` ADD FOREIGN KEY (`answered_by`) REFERENCES `user` (`id`);
ALTER TABLE `contact_reply` ADD FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`);
ALTER TABLE `featured` ADD FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);
ALTER TABLE `payment_methods_available` ADD FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);
ALTER TABLE `user_password_reset` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
ALTER TABLE `user_password_reset` ADD FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);
ALTER TABLE `user_login_history` ADD FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);
ALTER TABLE `user_login_history` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
ALTER TABLE `user` ADD FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);
ALTER TABLE `transaction_history` ADD FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`);
ALTER TABLE `transaction` ADD FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);
ALTER TABLE `store` ADD FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);
ALTER TABLE `wishlist_item` ADD FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);
ALTER TABLE `contact` ADD FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);
ALTER TABLE `contact_reply` ADD FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);

CREATE UNIQUE INDEX user_email_unique ON `user` (`email`);
