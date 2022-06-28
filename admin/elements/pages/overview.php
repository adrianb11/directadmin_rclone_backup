<?php
/**
 * Get all saved cronjobs.
 */
$dirContents = array(
    "active" => GetDirContents("/usr/local/directadmin/plugins/rclone_backup/admin/elements/conf/active"),
    "inactive" => GetDirContents("/usr/local/directadmin/plugins/rclone_backup/admin/elements/conf/inactive"),
    "pending" => GetDirContents("/usr/local/directadmin/plugins/rclone_backup/admin/elements/conf/pending")
);
?>
<br>
<div class="container-fluid">
    <div class="accordion" id="accordion-status">
        <?php foreach ($dirContents as $dirContentsKey => $dirContentsValue) {
            ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-<?php echo $dirContentsKey ?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse-<?php echo $dirContentsKey ?>"
                            aria-controls="collapse-<?php echo $dirContentsKey ?>">
                        <h5>
                            <?php echo $language[$dirContentsKey]; ?>
                        </h5>
                    </button>
                </h2>
                <div id="collapse-<?php echo $dirContentsKey ?>" class="accordion-collapse collapse"
                     aria-labelledby="heading-<?php echo $dirContentsKey ?>" data-bs-parent="#accordion-status">
                    <div class="accordion-body">
                        <div class="accordion" id="accordion-<?php echo $dirContentsKey ?>">
                            <?php
                            foreach ($dirContentsValue as $dirContent) {
                                $iniContent = ReadINI("/usr/local/directadmin/plugins/rclone_backup/admin/elements/conf/" . $dirContentsKey . "/" . $dirContent);
                                ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header"
                                        id="heading-<?php echo $iniContent["CRON"]["cron_id"] ?>">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse-<?php echo $iniContent["CRON"]["cron_id"] ?>"
                                                aria-controls="collapse-<?php echo $iniContent["CRON"]["cron_id"] ?>">
                                            <h5>
                                                <?php echo $language["BACKUP_PATH"] . ": " . $iniContent["DIRECTADMIN_WWW"]["directadmin_backup_path"] ?>
                                            </h5>
                                        </button>
                                    </h2>
                                    <div id="collapse-<?php echo $iniContent["CRON"]["cron_id"] ?>"
                                         class="accordion-collapse collapse"
                                         aria-labelledby="heading-<?php echo $iniContent["CRON"]["cron_id"] ?>"
                                         data-bs-parent="#accordion-<?php echo $dirContentsKey ?>">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col">
                                                    <ul class="list-group">
                                                        <li class="list-group-item">
                                                            <?php echo $language["CRON_OUTPUT"] . ": " . $iniContent["CRON"]["cron_output_minutes"] . " " . $iniContent["CRON"]["cron_output_hours"] . " " . $iniContent["CRON"]["cron_output_dom"] . " " . $iniContent["CRON"]["cron_output_months"] . " " . $iniContent["CRON"]["cron_output_dow"] ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <?php echo $language["DIRECTADMIN_USERNAME"] . ": " . $iniContent["DIRECTADMIN_WWW"]["directadmin_user"] ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <?php echo $language["DIRECTADMIN_DOMAIN"] . ": " . $iniContent["DIRECTADMIN_WWW"]["directadmin_domain"] ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <?php echo $language["DIRECTADMIN_SUBDOMAIN"] . ": " . $iniContent["DIRECTADMIN_WWW"]["directadmin_subdomain"] ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <?php echo $language["DATABASE_TO_BACKUP"] . ": " . $iniContent["DIRECTADMIN_DB"]["database_name"] ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col">
                                                    <ul class="list-group">
                                                        <li class="list-group-item">
                                                            <?php echo $language["SEND_EMAIL_NOTIFICATIONS"] . ": ";
                                                            if ($iniContent["EMAIL"]["send_email_enabled"] == "1") {
                                                                echo $language["ENABLED"];
                                                            } else {
                                                                echo $language["DISABLED"];
                                                            } ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <?php echo $language["EMAIL_ADDRESS"] . ": " . $iniContent["EMAIL"]["send_email_address"] ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <?php echo $language["COMPRESSION"] . ": " . $iniContent["COMPRESSION"]["compression"] ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <?php echo $language["RCLONE_REMOTE"] . ": " . $iniContent["RCLONE"]["remote"] ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <?php echo $language["FOLDER_STRUCTURE"] . ": " . $iniContent["RCLONE"]["structure"] ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col">
                                                    <ul class="list-group">
                                                        <li class="list-group-item">
                                                            <?php echo $language["BACKUP_PATH"] . ": " . $iniContent["DIRECTADMIN_WWW"]["directadmin_backup_path"] ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <?php echo $language["EXCLUDE_FOLDER"] . ": " . $iniContent["DIRECTADMIN_WWW"]["directadmin_exclude_path"] ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col">
                                                    <ul class="list-group">
                                                        <li class="list-group-item">
                                                            <a href="index.html?tab=create&cron_id=<?php echo $iniContent["CRON"]["cron_id"] ?>&cron_state=<?php echo $dirContentsKey; ?>"
                                                               class="btn btn-primary"
                                                               role="button"><?php echo $language["EDIT"]; ?></a>
                                                            <a href="index.html?form_id=cron&cron_id=<?php echo $iniContent["CRON"]["cron_id"] ?>&cron_state=<?php echo $dirContentsKey; ?>&delete=true"
                                                               class="btn btn-danger"
                                                               role="button"
                                                               onclick="return confirm(<?php echo $language["DELETE_CRON_CONFIRM"]; ?>)"
                                                               style="float: right;"><?php echo $language["DELETE"]; ?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
