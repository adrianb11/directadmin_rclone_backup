<?php

/**
 * Check if RClone is installed and display warning if false.
 */
if ($directadminarray["INSTALLED"]["rclone_installed"] != 1) { ?>
    <br>
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading"><?php echo $language["RCLONE_NOT_FOUND"]; ?></h4>
        <p><?php echo $language["RCLONE_NOT_FOUND_DESC"]; ?></p>
    </div>
<?php }


/**
 * Display Output From Scripts.
 */
if (isset($output['software']) || isset($output['run_script'])) { ?>
    <br>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>
            <pre><?php if (isset($output['software'])) {
                    echo $output['software'];
                } else {
                    echo $output['run_script'];
                } ?></pre>
        </strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php }

if (isset($output['options_saved']) || isset($output['cron_edited']) || isset($output['cron_new'])) { ?>
    <br>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>
            <pre>
                <?php if (isset($output['options_saved'])) {
                    echo $language["DEFAULT_OPTIONS_SAVED"];
                } elseif (isset($output['cron_edited'])) {
                    echo $language["CRON_EDITED"];
                } elseif (isset($output['cron_new'])) {
                    echo $language["NEW_CRON_CREATED"];
                } ?>
            </pre>
        </strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php }
