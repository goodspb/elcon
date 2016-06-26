<?php
/**
 *
 * Migration 使用样例
 * 使用 $db 变量来操作 SQL  , $db 变量为 PDO 类
 *
 */

$db->execute("
    DROP TABLE IF EXISTS `cron`;
    CREATE TABLE IF NOT EXISTS `cron` (
      `cron_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
      `expression` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
      `class` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
      `method` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
      `type` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
      `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
      `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
      `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
      `run_at` timestamp NULL DEFAULT NULL,
      `ms` int(10) unsigned NOT NULL DEFAULT '0',
      `error` text COLLATE utf8_unicode_ci NOT NULL,
      PRIMARY KEY (`cron_id`),
      KEY `name` (`name`,`created_at`),
      KEY `cron_status_index` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
");
