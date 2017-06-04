<?php
require_once dirname(__FILE__).'/common.php';
upgrade_query(
    "重命名 `filedata` => `files`",
    "RENAME TABLE `icms_filedata` TO `icms_files`;",
    "filedata"
);

upgrade_query(
    "重命名 `tags` => `tag`",
    "RENAME TABLE `icms_tags` TO `icms_tag`;",
    "tags"
);

upgrade_query(
    "重命名 `tags_map` => `tag_map`",
    "RENAME TABLE `icms_tags_map` TO `icms_tag_map`;",
    "tags_map"
);

upgrade_query(
    "删除无用表 `menu`",
    "DROP TABLE `icms_menu`;",
    "menu"
);

upgrade_query(
    "删除无用表 `sessions`",
    "DROP TABLE `icms_sessions`;",
    "sessions"
);

redirect('upgrade.2.php');
