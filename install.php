<?php

require_once('util4p/util.php');
require_once('util4p/CRObject.class.php');
require_once('util4p/MysqlPDO.class.php');

require_once('config.inc.php');
require_once('init.inc.php');


/* show error for debug purpose */
$config = new CRObject();
$config->set('show_error', true);
MysqlPDO::configure($config);


create_table_user();
create_table_workspace();
create_table_cluster();
create_table_job();
create_table_agent();
create_table_resource();
create_table_model();
create_table_log();

function execute_sqls($sqls)
{
	foreach ($sqls as $description => $sql) {
		echo "Executing $description: ";
		$res = (new MysqlPDO)->execute($sql, array());
		echo $res ? '<em>Success</em>' : '<em>Failed</em>';
		echo "<hr/>";
	}
}

function create_table_user()
{
	$sqls = array(
//		'DROP `yao_user`' =>
//			'DROP TABLE IF EXISTS `yao_user`',
		'CREATE `yao_user`' =>
			'CREATE TABLE `yao_user`(
				`uid` int AUTO_INCREMENT,
				 PRIMARY KEY (`uid`),
				`open_id` varchar(64) NOT NULL,
				 UNIQUE (`open_id`),
				`email` varchar(64),
				`role` varchar(12) NOT NULL,
				`level` int DEFAULT 0,
				`time` bigint
			)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci'
	);
	execute_sqls($sqls);
}

function create_table_workspace()
{
	$sqls = array(
//        'DROP `yao_workspace`' => 'DROP TABLE IF EXISTS `yao_workspace`',
		'CREATE `yao_workspace`' =>
			'CREATE TABLE `yao_workspace`(
				`id` int AUTO_INCREMENT,
				 PRIMARY KEY(`id`),
                `name` varchar(64) NOT NULL,
                `content` json NOT NULL,
                `created_at` BIGINT NOT NULL,
                `updated_at` BIGINT NOT NULL,
				`virtual_cluster` varchar(64) NOT NULL,
				 INDEX(`virtual_cluster`),
				`created_by` int NOT NULL,
				`permission` int, /* [0, 1, 2] * 10 + [0, 1, 2] => [-, r, w] * [-, r, w] */
				`version` int NOT NULL DEFAULT 0 /* for upgrade purpose */
			)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci',
	);
	execute_sqls($sqls);
}

function create_table_cluster()
{
	$sqls = array(
//        'DROP `yao_cluster`' => 'DROP TABLE IF EXISTS `yao_cluster`',
		'CREATE `yao_cluster`' =>
			'CREATE TABLE `yao_cluster`(
				`name` VARCHAR(64) NOT NULL,
				 PRIMARY KEY(`name`),
                `created_at` BIGINT NOT NULL,
				`created_by` int NOT NULL,
				`reserved_nodes` json NOT NULL,
				`quota_per_day` int NOT NULL,
				`quota_used` int NOT NULL,
				
				`version` int NOT NULL DEFAULT 0 /* for upgrade purpose */
			)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci',
	);
	execute_sqls($sqls);
}

function create_table_resource()
{
	$sqls = array(
//        'DROP `yao_resource`' => 'DROP TABLE IF EXISTS `yao_resource`',
		'CREATE `yao_resource`' =>
			'CREATE TABLE `yao_resource`(
				`id` int AUTO_INCREMENT,
				 PRIMARY KEY(`id`),
                `ip` bigint NOT NULL,
				`type` int NOT NULL, /* 0-CPU, 1-GPU */
				`model` VARCHAR(64) NOT NULL, /* eg. i7, P100 */
				`memory` int NOT NULL /* MB */
			)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci',
	);
	execute_sqls($sqls);
}

function create_table_agent()
{
	$sqls = array(
//        'DROP `yao_agent`' => 'DROP TABLE IF EXISTS `yao_agent`',
		'CREATE `yao_agent`' =>
			'CREATE TABLE `yao_agent`(
				`id` int AUTO_INCREMENT,
				 PRIMARY KEY(`id`),
                `ip` bigint NOT NULL,
                 UNIQUE(`ip`),
                `alias` VARCHAR(64),
                `cluster` int NOT NULL DEFAULT 0,
				`token` VARCHAR(64) NOT NULL
			)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci',
	);
	execute_sqls($sqls);
}

function create_table_model()
{
	$sqls = array(
//        'DROP `yao_model`' => 'DROP TABLE IF EXISTS `yao_model`',
		'CREATE `yao_model`' =>
			'CREATE TABLE `yao_model`(
				`id` int AUTO_INCREMENT,
				 PRIMARY KEY(`id`),
				`virtual_cluster` varchar(64) NOT NULL,
				`name` varchar(64) NOT NULL,
                `content` json NOT NULL,
				`created_at` BIGINT NOT NULL,
				`created_by` int NOT NULL,
				`permission` int /* [0, 1, 2] * 10 + [0, 1, 2] => [-, r, w] * [-, r, w] */
			)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci',
	);
	execute_sqls($sqls);
}

function create_table_job()
{
	$sqls = array(
//        'DROP `yao_job`' => 'DROP TABLE IF EXISTS `yao_job`',
		'CREATE `yao_job`' =>
			'CREATE TABLE `yao_job`(
				`id` int AUTO_INCREMENT,
				 PRIMARY KEY(`id`),
                `name` varchar(64) NOT NULL,
                `image` varchar(256) NOT NULL,
                `tasks` json NOT NULL,
                `workspace` int NOT NULL,
                `virtual_cluster` int NOT NULL DEFAULT 0,
                `priority` int NOT NULL DEFAULT 0,
                `run_before` bigint,
                `status` int NOT NULL DEFAULT 0,/* 0-submitted, 1-running, 2-finished, 3-failed, 4-stopped */
                `created_at` BIGINT NOT NULL,
                `updated_at` BIGINT,
				`created_by` int,
				`version` int NOT NULL DEFAULT 0 /* for upgrade purpose */
			)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci',
	);
	execute_sqls($sqls);
}

function create_table_log()
{
	$sqls = array(
//		'DROP `yao_log`' => 'DROP TABLE IF EXISTS `yao_log`',
		'CREATE `yao_log`' =>
			'CREATE TABLE `yao_log`(
		  		`id` BIGINT AUTO_INCREMENT,
	  			 PRIMARY KEY(`id`),
				`scope` VARCHAR(128) NOT NULL,
				 INDEX(`scope`),
				`tag` VARCHAR(128) NOT NULL,
				 INDEX(`tag`),
				`level` INT NOT NULL, /* too small value sets, no need to index */
				`time` BIGINT NOT NULL,
				 INDEX(`time`), 
			  	`ip` BIGINT NOT NULL,
				 INDEX(`ip`),
				`content` json 
			)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci'
	);
	execute_sqls($sqls);
}
