CREATE TABLE `customer` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NULL,
    `email` varchar(255) NULL,
    `phone` varchar(255) NULL,
    `cpf` varchar(255) NULL,
    `zipcode` varchar(255) NULL,
    `street` varchar(255) NULL,
    `number` int(11) NULL,
    `complement` varchar(255) NULL,
    `ditrict` varchar(255) NULL,
    `city` varchar(255) NULL,
    `state` varchar(255) NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_by` int(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE `product` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `price` float(8,2) NOT NULL,
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
