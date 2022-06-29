<?php

/**
 * Get saved language files.
 */
$languageArray = GetDirContents("/usr/local/directadmin/plugins/rclone_backup/admin/elements/lang");

?>

<! --
Create settings form
-->
<div class="container-fluid">
    <form class="form">

        <! --
        Language Options
        -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <?php echo $language["LANGUAGE_SELECTION"]; ?>
                </h5>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <select class="form-select" id="language" name="language">
                                <?php
                                /**
                                 * Display all saved language files
                                 */
                                foreach ($languageArray as $languages) {
                                    $languagefile = pathinfo($languages); ?>
                                    <option value="<?php echo $languagefile['filename']; ?>"
                                        <?php if ($directadminarray["LANGUAGE"]["language"] == $languagefile['filename']) {
                                            echo 'selected="selected"';
                                        } ?> ><?php echo $languagefile['filename']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <label for="language"><?php echo $language["LANGUAGE"]; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["LANGUAGE_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <! --
        Compression Options
        -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <?php echo $language["DEFAULT_COMPRESSION"]; ?>
                </h5>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <select class="form-select" id="compression" name="compression">
                                <option value="zip" <?php if ($directadminarray["COMPRESSION"]["compression"] == "zip") {
                                    echo 'selected="selected"';
                                }
                                if ($directadminarray["INSTALLED"]["zip_installed"] != 1) {
                                    echo "disabled";
                                } ?> >
                                    zip <?php if ($directadminarray["INSTALLED"]["zip_installed"] != 1) {
                                        echo " - " . $language["NOT_INSTALLED"];
                                    } ?>
                                </option>
                                <option value="tar" <?php if ($directadminarray["COMPRESSION"]["compression"] == "tar") {
                                    echo 'selected="selected"';
                                } ?> >
                                    tar
                                </option>
                                <option value="tgz" <?php if ($directadminarray["COMPRESSION"]["compression"] == "tgz") {
                                    echo 'selected="selected"';
                                } ?> >
                                    tgz
                                </option>
                            </select>
                            <label for="compression"><?php echo $language["COMPRESSION"]; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["COMPRESSION_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <! --
        File Host Options
        -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <?php echo $language["FILEHOST"]; ?>
                </h5>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <input placeholder="*" class="form-control" id="filehost_root_path"
                                   name="filehost_root_path"
                                   value="<?php echo $directadminarray["FILEHOST"]["filehost_root_path"] ?>"/>
                            <label for="filehost_root_path"><?php echo $language["FILEHOST_ROOT_PATH"]; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["DEFAULT_FILEHOST_ROOT_PATH_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <! --
        Email Options
        -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <?php echo $language["EMAIL"]; ?>
                </h5>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <select class="form-select" id="send_email_enabled" name="send_email_enabled">
                                <option value="true" <?php if ($directadminarray["EMAIL"]["send_email_enabled"] == true) {
                                    echo 'selected="selected"';
                                } ?> >
                                    <?php echo $language["ENABLED"]; ?>
                                </option>
                                <option value="false" <?php if ($directadminarray["EMAIL"]["send_email_enabled"] == false) {
                                    echo 'selected="selected"';
                                } ?> >
                                    <?php echo $language["DISABLED"]; ?>
                                </option>
                            </select>
                            <label for="send_email_enabled"><?php echo $language["ENABLED"]; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["ENABLED_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <input placeholder="*" class="form-control" id="send_email_address"
                                   name="send_email_address"
                                   value="<?php echo $directadminarray["EMAIL"]["send_email_address"] ?>"/>
                            <label for="send_email_address"><?php echo $language["DEFAULT_SEND_EMAIL"]; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["DEFAULT_EMAIL_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <! --
        RClone Options
        -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    RClone
                </h5>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <input placeholder="*" class="form-control" id="remotes" name="remotes"
                                   value="<?php echo $directadminarray["RCLONE"]["remotes"] ?>"/>
                            <label for="remotes"><?php echo $language["AVAILABLE_REMOTES"]; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["AVAILABLE_REMOTES_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <input placeholder="*" class="form-control" id="structure" name="structure"
                                   value="<?php echo $directadminarray["RCLONE"]["structure"] ?>"
                                   oninput="this.value = this.value.replace(/[^UDSoeaymd]/, '')"/>
                            <label for="structure"><?php echo $language["FOLDER_STRUCTURE"]; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["DEFAULT_FOLDER_STRUCTURE_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["FOLDER_STRUCTURE_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <! --
        Ignore Peer's Certificate
        -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <?php echo $language["IGNORE_CERTIFICATE"]; ?>
                </h5>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <select class="form-select" id="ignore_certificate" name="ignore_certificate">
                                <option value="1" <?php if ($directadminarray["SETTINGS"]["ignore_certificate"] == 1) {
                                    echo 'selected="selected"';
                                } ?> >
                                    <?php echo $language["TRUE"]; ?>
                                </option>
                                <option value="0" <?php if ($directadminarray["SETTINGS"]["ignore_certificate"] == 0) {
                                    echo 'selected="selected"';
                                } ?> >
                                    <?php echo $language["FALSE"]; ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["IGNORE_CERTIFICATE_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <! --
        MySQL Login Option
        -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <?php echo $language["MYSQL_CONF_LOGIN"]; ?>
                </h5>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <select class="form-select" id="my_conf_enabled" name="my_conf_enabled">
                                <option value="1" <?php if ($directadminarray["SETTINGS"]["my_conf_enabled"] == 1) {
                                    echo 'selected="selected"';
                                } ?> >
                                    <?php echo $language["TRUE"]; ?>
                                </option>
                                <option value="0" <?php if ($directadminarray["SETTINGS"]["my_conf_enabled"] == 0) {
                                    echo 'selected="selected"';
                                } ?> >
                                    <?php echo $language["FALSE"]; ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["MYSQL_CONF_ENABLED_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <input placeholder="*" class="form-control" id="my_conf_login" name="my_conf_login"
                                   value="<?php echo $directadminarray["SETTINGS"]["my_conf_login"] ?>"
                                   />
                            <label for="my_conf_login"><?php echo $language["MYSQL_CONF_LOGIN"]; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["MYSQL_CONF_LOGIN_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <! --
        Form submit
        -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="form-floating">
                            <input type="hidden" id="form_id" name="form_id" value="options">
                            <button type="submit" class="button"
                                    value="submit"><?php echo $language["SAVE_OPTIONS"]; ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
