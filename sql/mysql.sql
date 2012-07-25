CREATE TABLE `bookshop_authors` (
  `auth_id` int(10) unsigned NOT NULL auto_increment,
  `auth_type` tinyint(1) unsigned NOT NULL default '1' COMMENT '1 = Auteur, 2 = Traducteur',
  `auth_name` varchar(255) NOT NULL,
  `auth_firstname` varchar(255) NOT NULL,
  `auth_email` varchar(255) NOT NULL,
  `auth_bio` text NOT NULL,
  `auth_url` varchar(255) NOT NULL COMMENT 'URL du site de l''auteur par exemple',
  `auth_photo1` varchar(255) NOT NULL,
  `auth_photo2` varchar(255) NOT NULL,
  `auth_photo3` varchar(255) NOT NULL,
  `auth_photo4` varchar(255) NOT NULL,
  `auth_photo5` varchar(255) NOT NULL,
  PRIMARY KEY  (`auth_id`),
  KEY `auth_name` (`auth_name`),
  KEY `auth_firstname` (`auth_firstname`),
  KEY `auth_type` (`auth_type`),
  FULLTEXT KEY `auth_bio` (`auth_bio`)
) ENGINE=MyISAM;


CREATE TABLE `bookshop_books` (
  `book_id` int(11) unsigned NOT NULL auto_increment,
  `book_cid` int(5) unsigned NOT NULL default '0',
  `book_title` varchar(255) NOT NULL default '',
  `book_lang_id` int(10) unsigned NOT NULL,
  `book_number` varchar(60) NOT NULL COMMENT 'numéro du livre, par exemple pour les périodiques',
  `book_tome` varchar(50) NOT NULL,
  `book_format` varchar(100) NOT NULL COMMENT 'Par exemple 19x23',
  `book_url` varchar(255) NOT NULL COMMENT 'URL vers une page externe ',
  `book_image_url` varchar(255) NOT NULL COMMENT 'URL de la grande image',
  `book_thumb_url` varchar(255) NOT NULL COMMENT 'URL de la vignette',
  `book_submitter` int(11) unsigned NOT NULL default '0',
  `book_online` tinyint(1) NOT NULL default '0',
  `book_date` varchar(255) NOT NULL COMMENT 'date de publication du livre',
  `book_submitted` int(10) unsigned NOT NULL default '0' COMMENT 'Date à laquelle le livre a été soumis sur le site',
  `book_hits` int(11) unsigned NOT NULL default '0' COMMENT 'Nombre de fois où la fiche du livre a été vue',
  `book_rating` double(6,4) NOT NULL default '0.0000',
  `book_votes` int(11) unsigned NOT NULL default '0',
  `book_comments` int(11) unsigned NOT NULL default '0',
  `book_price` decimal(7,2) NOT NULL,
  `book_shipping_price` decimal(7,2) NOT NULL,
  `book_discount_price` decimal(7,2) NOT NULL,
  `book_stock` mediumint(8) unsigned NOT NULL COMMENT 'Quantité du livre disponible en stock',
  `book_alert_stock` mediumint(8) unsigned NOT NULL COMMENT 'quantité à partir de laquelle il faut émettre une alerte',
  `book_summary` text NOT NULL,
  `book_description` text NOT NULL,
  `book_attachment` varchar(255) NOT NULL,
  `book_isbn` varchar(13) NOT NULL,
  `book_ean` varchar(13) NOT NULL,
  `book_vat_id` mediumint(8) unsigned NOT NULL,
  `book_pages` mediumint(8) unsigned NOT NULL,
  `book_pages_collection` mediumint(8) unsigned NOT NULL COMMENT 'Nombre total de pages dans la collection',
  `book_volumes_count` mediumint(8) unsigned NOT NULL COMMENT 'Nombre de volumes',
  `book_recommended` date NOT NULL,
  `book_metakeywords` varchar(255) NOT NULL,
  `book_metadescription` varchar(255) NOT NULL,
  `book_metatitle` varchar(255) NOT NULL,
  PRIMARY KEY  (`book_id`),
  KEY `book_cid` (`book_cid`),
  KEY `book_status` (`book_online`),
  KEY `book_title` (`book_title`),
  KEY `book_isbn` (`book_isbn`),
  KEY `book_ean` (`book_ean`),
  KEY `book_lang_id` (`book_lang_id`),
  KEY `book_tome` (`book_tome`),
  KEY `book_format` (`book_format`),
  KEY `recent_online` (`book_online`,`book_submitted`),
  KEY `book_recommended` (`book_recommended`),
  FULLTEXT KEY `book_summary` (`book_summary`),
  FULLTEXT KEY `book_description` (`book_description`)
) ENGINE=MyISAM;


CREATE TABLE `bookshop_booksauthors` (
  `ba_id` int(10) unsigned NOT NULL auto_increment,
  `ba_book_id` int(10) unsigned NOT NULL,
  `ba_auth_id` int(10) unsigned NOT NULL,
  `ba_type` tinyint(1) NOT NULL COMMENT '1 = Auteur, 2 = Traducteur',
  PRIMARY KEY  (`ba_id`),
  KEY `ba_book_id` (`ba_book_id`),
  KEY `ba_auth_id` (`ba_auth_id`),
  KEY `ba_type` (`ba_type`),
  KEY `ba_book_type` (`ba_book_id`,`ba_type`)
) ENGINE=MyISAM;


CREATE TABLE `bookshop_caddy` (
  `caddy_id` int(10) unsigned NOT NULL auto_increment,
  `caddy_book_id` int(10) unsigned NOT NULL,
  `caddy_qte` mediumint(8) unsigned NOT NULL,
  `caddy_price` decimal(7,2) NOT NULL,
  `caddy_cmd_id` int(10) unsigned NOT NULL,
  `caddy_shipping` double(7,2) NOT NULL,
  PRIMARY KEY  (`caddy_id`),
  KEY `caddy_cmd_id` (`caddy_cmd_id`),
  KEY `caddy_book_id` (`caddy_book_id`)
) ENGINE=MyISAM;


CREATE TABLE `bookshop_cat` (
  `cat_cid` int(5) unsigned NOT NULL auto_increment,
  `cat_pid` int(5) unsigned NOT NULL default '0',
  `cat_title` varchar(255) NOT NULL default '',
  `cat_imgurl` varchar(255) NOT NULL default '',
  `cat_description` text NOT NULL,
  `cat_advertisement` text NOT NULL COMMENT 'Publicité de la catégorie',
  `cat_metatitle` varchar(255) NOT NULL,
  `cat_metadescription` varchar(255) NOT NULL,
  `cat_metakeywords` varchar(255) NOT NULL,
  PRIMARY KEY  (`cat_cid`),
  KEY `cat_pid` (`cat_pid`),
  FULLTEXT KEY `cat_title` (`cat_title`),
  FULLTEXT KEY `cat_description` (`cat_description`)
) ENGINE=MyISAM ;


CREATE TABLE `bookshop_commands` (
  `cmd_id` int(10) unsigned NOT NULL auto_increment,
  `cmd_uid` int(10) unsigned NOT NULL COMMENT 'ID utilisateur Xoops',
  `cmd_date` date NOT NULL,
  `cmd_state` tinyint(1) unsigned NOT NULL,
  `cmd_ip` varchar(32) NOT NULL,
  `cmd_lastname` varchar(255) NOT NULL,
  `cmd_firstname` varchar(255) NOT NULL,
  `cmd_adress` text NOT NULL,
  `cmd_zip` varchar(30) NOT NULL,
  `cmd_town` varchar(255) NOT NULL,
  `cmd_country` varchar(3) NOT NULL,
  `cmd_telephone` varchar(30) NOT NULL,
  `cmd_email` varchar(255) NOT NULL,
  `cmd_articles_count` mediumint(8) unsigned NOT NULL,
  `cmd_total` double(7,2) NOT NULL,
  `cmd_shipping` decimal(7,2) NOT NULL,
  `cmd_bill` tinyint(1) unsigned NOT NULL default '0' COMMENT 'Le client à demandé une facture papier ?',
  `cmd_password` varchar(32) NOT NULL COMMENT 'Utilisé pour imprimer les factures en ligne',
  `cmd_text` text NOT NULL,
  `cmd_cancel` varchar(32) NOT NULL,
  PRIMARY KEY  (`cmd_id`),
  KEY `cmd_date` (`cmd_date`),
  KEY `cmd_state` (`cmd_state`),
  KEY `cmd_uid` (`cmd_uid`)
) ENGINE=MyISAM;

CREATE TABLE `bookshop_related` (
  `related_id` int(10) unsigned NOT NULL auto_increment,
  `related_book_id` int(10) unsigned NOT NULL COMMENT 'Id du livre maître',
  `related_book_related` int(10) unsigned NOT NULL COMMENT 'Id du livre à afficher avec le maître',
  PRIMARY KEY  (`related_id`),
  KEY `seealso` (`related_book_id`,`related_book_related`),
  KEY `related_book_id` (`related_book_id`),
  KEY `related_book_related` (`related_book_related`)
) ENGINE=MyISAM;

CREATE TABLE `bookshop_vat` (
  `vat_id` mediumint(8) unsigned NOT NULL auto_increment,
  `vat_rate` double(5,2) NOT NULL,
  PRIMARY KEY  (`vat_id`),
  KEY `vat_rate` (`vat_rate`)
) ENGINE=MyISAM;

CREATE TABLE `bookshop_votedata` (
  `vote_ratingid` int(11) unsigned NOT NULL auto_increment,
  `vote_book_id` int(11) unsigned NOT NULL default '0',
  `vote_uid` int(11) unsigned NOT NULL default '0',
  `vote_rating` tinyint(3) unsigned NOT NULL default '0',
  `vote_ratinghostname` varchar(60) NOT NULL default '',
  `vote_ratingtimestamp` int(10) NOT NULL default '0',
  PRIMARY KEY  (`vote_ratingid`),
  KEY `vote_ratinguser` (`vote_uid`),
  KEY `vote_ratinghostname` (`vote_ratinghostname`),
  KEY `vote_book_id` (`vote_book_id`)
) ENGINE=MyISAM;

CREATE TABLE `bookshop_discounts` (
  `disc_id` int(10) unsigned NOT NULL auto_increment,
  `disc_group` int(10) unsigned NOT NULL,
  `disc_amount` double(7,2) NOT NULL,
  `disc_percent_monney` tinyint(1) unsigned NOT NULL,
  `disc_on_what` tinyint(1) unsigned NOT NULL,
  `disc_when` tinyint(1) unsigned NOT NULL,
  `disc_shipping` tinyint(1) NOT NULL,
  `disc_shipping_amount` double(7,2) NOT NULL,
  `disc_shipping_amount_next` double(7,2) NOT NULL,
  `disc_if_amount` double(7,2) NOT NULL,
  `disc_description` text NOT NULL,
  `disc_qty_criteria` tinyint(1) unsigned NOT NULL,
  `disc_qty_value` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY  (`disc_id`),
  KEY `disc_group` (`disc_group`)
) ENGINE=MyISAM;


CREATE TABLE `bookshop_lang` (
  `lang_id` int(10) unsigned NOT NULL auto_increment,
  `lang_lang` varchar(150) NOT NULL,
  PRIMARY KEY  (`lang_id`),
  KEY `lang_lang` (`lang_lang`)
) ENGINE=MyISAM;
