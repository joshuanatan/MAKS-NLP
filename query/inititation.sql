create database maks_nlp;
use maks_nlp;
CREATE TABLE `tbl_entity` (
  `id_submit_entity` int(11) NOT NULL primary key auto_increment,
  `id_wit_ai_acc` int(11) NOT NULL,
  `entity_wit_id` varchar(400) DEFAULT NULL,
  `entity_name` varchar(300) DEFAULT NULL,
  `entity_desc` varchar(400) DEFAULT NULL,
  `status_aktif_entity` varchar(1) DEFAULT '0' COMMENT '0 = belum naik ke wit, 1 suda naik',
  `tgl_entity_last_modified` datetime DEFAULT '0000-00-00 00:00:00',
  `id_user_entity_last_modified` int(11) 
);


CREATE TABLE `tbl_entity_value` (
  `id_submit_entity_value` int(11) NOT NULL primary key auto_increment,
  `id_entity` int(11) DEFAULT NULL,
  `entity_value` varchar(400) DEFAULT NULL,
  `status_aktif_entity_value` varchar(1) DEFAULT NULL,
  `tgl_entity_value_last_modified` datetime DEFAULT NULL,
  `id_user_entity_value_last_modified` int(11) DEFAULT NULL
);

CREATE TABLE `tbl_entity_value_expression` (
  `id_submit_entity_value_expression` int(11) NOT NULL primary key auto_increment,
  `id_entity_value` int(11) DEFAULT NULL,
  `entity_value_expression` varchar(400) DEFAULT NULL,
  `status_aktif_entity_value_expression` varchar(1) DEFAULT NULL,
  `tgl_entity_value_expression_last_modified` datetime DEFAULT NULL,
  `id_user_entity_value_expression_last_modified` int(11) DEFAULT NULL
);

CREATE TABLE `tbl_samples` (
  `id_submit_samples` int(11) NOT NULL primary key auto_increment,
  `id_wit_ai_acc` int(11) NOT NULL,
  `samples` varchar(400) DEFAULT NULL,
  `status_aktif_samples` varchar(1) DEFAULT NULL,
  `id_intent` int(11) DEFAULT NULL,
  `tgl_samples_last_modified` datetime DEFAULT NULL,
  `id_user_samples_last_modified` int(11) DEFAULT NULL
);

CREATE TABLE `tbl_samples_entity` (
  `id_submit_samples_detail` int(11) NOT NULL primary key auto_increment,
  `id_samples` int(11) DEFAULT NULL,
  `start_index` int(11) DEFAULT NULL,
  `end_index` int(11) DEFAULT NULL,
  `id_entity_value` int(11) DEFAULT NULL
);


CREATE TABLE `tbl_token` (
  `id_submit_token` int(11) NOT NULL primary key auto_increment,
  `token` varchar(255) DEFAULT NULL,
  `nama_client` varchar(300) DEFAULT NULL,
  `status_aktif_token` varchar(1) DEFAULT NULL,
  `tgl_token_last_modified` datetime DEFAULT NULL,
  `id_user_token_last_modified` int(11) DEFAULT NULL
);

CREATE TABLE `tbl_user` (
  `id_submit_user` int(11) NOT NULL primary key auto_increment,
  `nama_user` varchar(200) DEFAULT NULL,
  `password_user` varchar(300) DEFAULT NULL,
  `email_user` varchar(200) DEFAULT NULL,
  `status_aktif_user` varchar(1) DEFAULT NULL,
  `tgl_user_last_modified` datetime DEFAULT NULL,
  `id_user_user_last_modified` int(11) DEFAULT NULL
);

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id_submit_user`, `nama_user`, `password_user`, `email_user`, `status_aktif_user`, `tgl_user_last_modified`,`id_user_user_last_modified` ) VALUES
(1, 'Joshua Natan', '81dc9bdb52d04dc20036dbd8313ed055', 'joshuanatan.jn@gmail.com', '1', '2019-10-29 04:46:48', 0);


drop table tbl_wit_ai_acc;
CREATE TABLE `tbl_wit_ai_acc` (
  `id_submit_wit_ai_acc` int(11) NOT NULL primary key auto_increment,
  `registered_email` varchar(400) DEFAULT NULL,
  `registered_name` varchar(400) DEFAULT NULL,
  `application_id` varchar(400) DEFAULT NULL,
  `application_name` varchar(400) DEFAULT NULL,
  `server_access_token` varchar(400) DEFAULT NULL,
  `status_aktif_wit_ai_acc` varchar(1) DEFAULT NULL,
  `date_wit_ai_acc_last_modified` datetime DEFAULT NULL,
  `id_user_wit_ai_acc_last_modified` int(11) DEFAULT NULL
);

create table tbl_intent(
	id_submit_intent int primary key auto_increment,
    id_wit_ai_acc int,
    intent varchar(400),
    status_aktif_intent varchar(1),
    tgl_intent_last_modified datetime,
    id_user_intent_last_modified int
);