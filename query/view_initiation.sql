drop view v_samples_list;
CREATE  VIEW `v_samples_list`  AS  
select `id_submit_samples`,
`tbl_samples`.`id_wit_ai_acc`,
`samples`,
`status_aktif_samples`,
`id_intent`,
`tgl_samples_last_modified`,
`id_user_samples_last_modified`,
`id_submit_intent`,
`intent`,
`status_aktif_intent`,
`id_user_intent_last_modified`
from (`tbl_samples` 
join `tbl_intent` on((`tbl_intent`.`id_submit_intent` = `tbl_samples`.`id_intent`))) ;

CREATE  VIEW `detail_entity`  AS  
select `id_submit_entity`,
`entity_wit_id`,
`entity_name`,
`entity_desc`,
`status_aktif_entity`,
`tgl_entity_last_modified`,
`id_user_entity_last_modified`,
`id_entity`,
`id_submit_entity_value`,
`status_aktif_entity_value`,
`entity_value`,
`tgl_entity_value_last_modified`,
`id_user_entity_value_last_modified`,
`id_submit_entity_value_expression`,
`id_entity_value`,
`entity_value_expression`,
`status_aktif_entity_value_expression`,
`tgl_entity_value_expression_last_modified`,
`id_user_entity_value_expression_last_modified`
from ((`tbl_entity_value_expression` join `tbl_entity_value` on((`tbl_entity_value`.`id_submit_entity_value` = `tbl_entity_value_expression`.`id_entity_value`))) join `tbl_entity` on((`tbl_entity`.`id_submit_entity` = `tbl_entity_value`.`id_entity`))) ;

CREATE VIEW `detail_samples`  AS  
select `id_submit_samples`,
`samples`,
`status_aktif_samples`,
`id_intent`,
`tgl_samples_last_modified`,
`id_user_samples_last_modified`,
`id_submit_entity_value`,
`id_entity`,
`entity_value`,
`status_aktif_entity_value`,
`id_submit_samples_detail`,
`id_samples`,
`start_index`,
`end_index`,
`id_entity_value`,
`sample_entity`.`id_submit_entity_value`,
`sample_entity`.`id_entity`,
`sample_entity`.`entity_value`,
`sample_entity`.`status_aktif_entity_value`,
`sample_entity`.`tgl_entity_value_last_modified`,
`sample_entity`.`id_user_entity_value_last_modified`,
`id_submit_entity`,
`entity_wit_id`,
`entity_name`,
`entity_desc`,
`status_aktif_entity`,
`tgl_entity_last_modified`,
`id_user_entity_last_modified`,
`id_wit_ai_acc`;
 
CREATE VIEW `detail_samples`  AS  
select `id_submit_samples`,
`samples`,
`status_aktif_samples`,
`id_intent`,
`tgl_samples_last_modified`,
`id_user_samples_last_modified`,
`tbl_samples`.`id_wit_ai_acc`,

`id_submit_samples_detail`,
`id_samples`,
`start_index`,
`end_index`,
`id_entity_value`,

`id_submit_entity_value`,
`id_entity`,
`entity_value`,
`status_aktif_entity_value`,
`tgl_entity_value_last_modified`,
`id_user_entity_value_last_modified`,

`id_submit_entity`,
`entity_wit_id`,
`entity_name`,
`entity_desc`,
`status_aktif_entity`,
`tgl_entity_last_modified`,
`id_user_entity_last_modified`,

id_submit_intent,
intent
from ((((`tbl_samples` join `tbl_intent` on((`tbl_intent`.`id_submit_intent` = `tbl_samples`.`id_intent`))) left join `tbl_samples_entity` on((`tbl_samples_entity`.`id_samples` = `tbl_samples`.`id_submit_samples`))) left join `tbl_entity_value` on((`tbl_entity_value`.`id_submit_entity_value` = `tbl_samples_entity`.`id_entity_value`))) left join `tbl_entity` on((`tbl_entity`.`id_submit_entity` = `tbl_entity_value`.`id_entity`))) ;