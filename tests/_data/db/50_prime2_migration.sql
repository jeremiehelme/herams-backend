SET FOREIGN_KEY_CHECKS=0;
SET NAMES 'utf8';
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m000000_000000_base', 1542208794);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m140209_132017_init', 1542208885);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m140403_174025_create_account_table', 1542208886);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m140504_113157_update_tables', 1542208887);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m140504_130429_create_token_table', 1542208887);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m140506_102106_rbac_init', 1542209404);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m140830_171933_fix_ip_field', 1542208887);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m140830_172703_change_account_table_name', 1542208887);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m141222_110026_update_ip_field', 1542208887);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m141222_135246_alter_username_length', 1542208887);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m150614_103145_update_social_account_table', 1542208887);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m150623_212711_fix_username_notnull', 1542208888);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m150921_103849_add_columns_to_user_drop_username', 1542209020);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m150921_110227_create_settings_table', 1542209020);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m150928_101102_setting_add_primary', 1542209020);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151005_081802_create_tools', 1542209020);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151005_134258_create_project', 1542209020);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151007_122334_add_permissions', 1542209021);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151020_084719_add_progress_type_to_tool', 1542209316);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151021_114420_create_userData', 1542209316);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151022_132419_create_report', 1542209316);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151026_090031_add_thumbnail_to_tool', 1542209021);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151026_112253_create_user_lists', 1542209021);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151026_152935_add_generators_to_tool', 1542209316);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151027_100100_add_default_generator_to_project', 1542209316);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151104_081900_create_country', 1542209021);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151105_135351_add_latitude_longitude_to_project', 1542209021);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151105_145625_add_closed_to_project', 1542209021);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151112_092421_update_country', 1542209022);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151113_125634_add_title_to_report', 1542209316);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151120_121551_add_acronym_to_tool', 1542209316);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151120_122716_add_locality_name_and_created_to_project', 1542209316);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151120_144934_remove_project_countries', 1542209317);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151124_084028_create_translation_tables', 1542209317);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151126_112952_project_add_token', 1542209317);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151130_132254_update_report_data', 1542209317);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151130_134444_add_file', 1542209317);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151130_134703_update_report_to_use_file', 1542209318);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m151218_234654_add_timezone_to_profile', 1542208888);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m160114_120158_profile_defaults', 1542209318);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m160114_121645_migrate_users_to_prime2', 1542209318);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m160119_132345_set_dashboard_settings', 1542209318);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m160208_111454_readd_username_column', 1542209318);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m160223_125642_add_listed_to_tool', 1542209318);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m160225_082821_add_fields_to_profile', 1542209318);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m160308_122659_user_add_access_token', 1542209318);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m160407_130403_batch_user_import', 1542209318);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m160408_114341_tool_progress_optional', 1542209709);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m160531_124602_tool_add_default_report', 1542209720);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m160929_103127_add_last_login_at_to_user_table', 1542208888);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m161026_083647_tool_add_explorer_fields', 1542209720);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m161116_132913_explorer_add_geo_and_services', 1542209720);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m170907_052038_rbac_add_index_on_auth_assignment_user_id', 1542209404);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m170913_152617_add_favorite_project_to_profile', 1542209720);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m180319_094924_create_category_table', 1542209720);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m180405_145645_create_response_master_table', 1542209721);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m180405_145759_create_response_data_table', 1542209721);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m180405_150034_create_indicator_table', 1542209721);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m180405_150114_create_indicator_option_table', 1542209721);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m180405_150444_create_category_chart_table', 1542209721);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m180405_150519_create_geography_level_table', 1542209721);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m180405_150531_create_geography_table', 1542209721);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m180405_212213_create_country_status_table', 1542209721);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m180410_155147_add_charturl_column_to_category_table', 1542209721);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m180410_155214_add_mapurl_column_to_category_table', 1542209721);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m180410_155232_add_layout_column_to_category_table', 1542209721);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m180523_151638_rbac_updates_indexes_without_prefix', 1555336258);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m180605_220711_add_aggregated_to_category_table', 1542209722);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m181115_144357_tool_drop_fields', 1542293156);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m181115_152543_project_drop_fields', 1542295691);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m190115_091112_create_page_element_table', 1550053628);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m190213_095249_tool_add_coordinates', 1550053628);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m190213_140119_rename_project_table', 1550066523);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m190213_140128_rename_tool_table', 1550066523);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m190221_083716_project_status', 1550738299);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m190227_093439_project_add_typemap', 1551260145);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m190305_153424_drop_username_column', 1551800123);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m190315_124746_project_add_overrides', 1553164364);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m190415_130133_make_owners_admin', 1555336847);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m190415_135252_drop_report_table', 1555336847);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m190415_135316_drop_category_tables', 1555336847);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m190415_135402_drop_geography_tables', 1555336847);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m190415_135506_drop_user_list', 1555336847);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m190417_141532_clean_up_permissions', 1555510707);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m190621_161448_page_add_services', 1562492663);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m190722_084803_create_response_table', 1564751070);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m190828_131450_element_add_size', 1566998156);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m190925_131139_response_table_foreign_keys', 1571216010);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m191024_124402_assignments_to_permissions', 1571928494);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m191025_093828_drop_profile', 1571998475);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m191114_143505_page_rename_project', 1573742161);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m191203_140540_CreateKeyTable', 1579684496);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m200117_083725_workspace_token_per_project', 1579684496);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m200120_142141_permission_id_string', 1579684496);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m200120_154544_workspace_project_foreign_key', 1579684496);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m200204_101056_response_project_foreign_key', 1580908837);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m200205_123138_project_visibility', 1580908837);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m200210_092104_change_write_to_ls', 1581673484);
INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES ('m200214_094238_drop_social_account', 1581673484);
SET FOREIGN_KEY_CHECKS=1;
