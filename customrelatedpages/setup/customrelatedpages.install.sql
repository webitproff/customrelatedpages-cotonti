-- file customrelatedpages.install.sql

CREATE TABLE IF NOT EXISTS `cot_customrelatedpages` (
  `pr_id`          int unsigned         NOT NULL auto_increment,
  `pr_from_id`     int unsigned         NOT NULL default '0',
  `pr_to_id`       int unsigned         NOT NULL default '0',
  `pr_order`       tinyint unsigned     NOT NULL default '0',
  PRIMARY KEY  (`pr_id`),
  UNIQUE KEY `unique_pair` (`pr_from_id`,`pr_to_id`),
  KEY `idx_from` (`pr_from_id`),
  KEY `idx_to`   (`pr_to_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;