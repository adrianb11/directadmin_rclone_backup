<?php
/**
 * Save config into variable
 */
$directadminarray = ReadINI("/usr/local/directadmin/plugins/rclone_backup/admin/elements/conf/directadmin.ini");
?>

<nav class="nav nav-tabs nav-justified">
    <a class="nav-link <?php if ($tab == 'overview') echo "active"; ?>" href="index.html"><?php echo $language["OVERVIEW_PAGE_TITLE"]; ?></a>
    <a class="nav-link <?php if ($tab == 'create') echo "active"; ?>" href="index.html?tab=create"><?php echo $language["CREATE_NEW_CRONJOB_TITLE"]; ?></a>
    <a class="nav-link <?php if ($tab == 'options') echo "active"; ?>" href="index.html?tab=options"><?php echo $language["OPTIONS_PAGE_TITLE"]; ?></a>
    <a class="nav-link <?php if ($tab == 'settings') echo "active"; ?>" href="index.html?tab=settings"><?php echo $language["SETTINGS_PAGE_TITLE"]; ?></a>
</nav>
