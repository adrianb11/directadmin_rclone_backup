<?php

/**
 * Prepare config file.
 */
if (isset($_SERVER['QUERY_STRING']) && isset($output['cron_id']) && isset($output['cron_state'])) {
    $input_config_file = "/usr/local/directadmin/plugins/rclone_backup/admin/elements/conf/" . $output['cron_state'] . "/" . $output['cron_id'] . ".ini";
    $cronId = $output['cron_id'];
    $cronState = $output['cron_state'];
} else {
    $input_config_file = "/usr/local/directadmin/plugins/rclone_backup/admin/elements/conf/DEFAULT.ini";
    $cronId = uniqid();
    $cronState = "new";
}


/**
 * Load config into variable.
 */
$config_data = ReadINI($input_config_file);


/**
 * Get list of all users, domains, and sub-domains.
 * Used to create the <select> elements.
 */
$domainJSON = getApi("/CMD_API_DOMAIN?action=document_root");
$domainRESULT = json_decode($domainJSON, true);
if (json_last_error() != 0) {
    // JSON is not valid
    $domainRESULT = array();
}

foreach ($domainRESULT["users"] as $userKEY => $userVALUE) {
    if ($config_data["DIRECTADMIN_WWW"]["directadmin_user"] == $userKEY) {
        $directadmin_user_select .= "<option value='" . $userKEY . "' selected='selected'>" . $userKEY . "</option>";
    } else {
        $directadmin_user_select .= "<option value='" . $userKEY . "'>" . $userKEY . "</option>";
    }

    foreach ($userVALUE["domains"] as $domainsKEY => $domainVALUE) {
        if ($config_data["DIRECTADMIN_WWW"]["directadmin_domain"] == $domainsKEY) {
            $directadmin_domain_select .= "<option value='" . $domainsKEY . "' selected='selected' data-project='" . $userKEY . "'>" . $domainsKEY . "</option>";
        } else {
            $directadmin_domain_select .= "<option value='" . $domainsKEY . "' data-project='" . $userKEY . "'>" . $domainsKEY . "</option>";
        }

        foreach ($domainVALUE["subdomains"] as $subdomainsKEY => $subdomainsVALUE) {
            if ($config_data["DIRECTADMIN_WWW"]["directadmin_subdomain"] == $subdomainsKEY) {
                $directadmin_subdomain_select .= "<option value='" . $subdomainsKEY . "' selected='selected' data-project='" . $domainsKEY . "'>" . $subdomainsKEY . "</option>";
            } else {
                $directadmin_subdomain_select .= "<option value='" . $subdomainsKEY . "' data-project='" . $domainsKEY . "'>" . $subdomainsKEY . "</option>";
            }
        }
    }
}


/**
 * Get list of all databases.
 * Used to create the <select> elements.
 */
$databaseJSON = getApi("/CMD_API_DATABASES");
$databaseRESULT = explode('&list[]=', '&' . $databaseJSON);

unset($databaseRESULT[0]);

foreach ($databaseRESULT as $databaseKEY => $databaseVALUE) {
    if ($config_data["DIRECTADMIN_DB"]["database_name"] == $databaseVALUE) {
        $directadmin_database_select .= "<option value='" . $databaseVALUE . "' selected='selected'>" . $databaseVALUE . "</option>";
    } else {
        $directadmin_database_select .= "<option value='" . $databaseVALUE . "'>" . $databaseVALUE . "</option>";
    }
}


/**
 * Get list of available remotes.
 * Used to create the <select> elements.
 */
$remotes = explode(',', $directadminarray["RCLONE"]["remotes"]);
foreach ($remotes as $remote) {
    if ($config_data["RCLONE"]["remote"] == $remote) {
        $remote_select .= "<option value='" . $remote . "' selected='selected'>" . $remote . "</option>";
    } else {
        $remote_select .= "<option value='" . $remote . "'>" . $remote . "</option>";
    }
}

?>

<! --
Start Main Content
-->
<div class="container-fluid">
    <form class="form" id="cron">

        <! --
        Cron Options
        -->
        <div class="card">
            <div class="card-header text-center">
                <?php echo $language["CRON_CREATOR"]; ?>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <select size="7" multiple="multiple" class="form-select" name="cron-minutes"
                                id="cron-minutes" onchange="updateField('minutes')">
                            <option selected="selected" value="0">00</option>
                            <option selected="selected" value="1">01</option>
                            <option selected="selected" value="2">02</option>
                            <option selected="selected" value="3">03</option>
                            <option selected="selected" value="4">04</option>
                            <option selected="selected" value="5">05</option>
                            <option selected="selected" value="6">06</option>
                            <option selected="selected" value="7">07</option>
                            <option selected="selected" value="8">08</option>
                            <option selected="selected" value="9">09</option>
                            <option selected="selected" value="10">10</option>
                            <option selected="selected" value="11">11</option>
                            <option selected="selected" value="12">12</option>
                            <option selected="selected" value="13">13</option>
                            <option selected="selected" value="14">14</option>
                            <option selected="selected" value="15">15</option>
                            <option selected="selected" value="16">16</option>
                            <option selected="selected" value="17">17</option>
                            <option selected="selected" value="18">18</option>
                            <option selected="selected" value="19">19</option>
                            <option selected="selected" value="20">20</option>
                            <option selected="selected" value="21">21</option>
                            <option selected="selected" value="22">22</option>
                            <option selected="selected" value="23">23</option>
                            <option selected="selected" value="24">24</option>
                            <option selected="selected" value="25">25</option>
                            <option selected="selected" value="26">26</option>
                            <option selected="selected" value="27">27</option>
                            <option selected="selected" value="28">28</option>
                            <option selected="selected" value="29">29</option>
                            <option selected="selected" value="30">30</option>
                            <option selected="selected" value="31">31</option>
                            <option selected="selected" value="32">32</option>
                            <option selected="selected" value="33">33</option>
                            <option selected="selected" value="34">34</option>
                            <option selected="selected" value="35">35</option>
                            <option selected="selected" value="36">36</option>
                            <option selected="selected" value="37">37</option>
                            <option selected="selected" value="38">38</option>
                            <option selected="selected" value="39">39</option>
                            <option selected="selected" value="40">40</option>
                            <option selected="selected" value="41">41</option>
                            <option selected="selected" value="42">42</option>
                            <option selected="selected" value="43">43</option>
                            <option selected="selected" value="44">44</option>
                            <option selected="selected" value="45">45</option>
                            <option selected="selected" value="46">46</option>
                            <option selected="selected" value="47">47</option>
                            <option selected="selected" value="48">48</option>
                            <option selected="selected" value="49">49</option>
                            <option selected="selected" value="50">50</option>
                            <option selected="selected" value="51">51</option>
                            <option selected="selected" value="52">52</option>
                            <option selected="selected" value="53">53</option>
                            <option selected="selected" value="54">54</option>
                            <option selected="selected" value="55">55</option>
                            <option selected="selected" value="56">56</option>
                            <option selected="selected" value="57">57</option>
                            <option selected="selected" value="58">58</option>
                            <option selected="selected" value="59">59</option>
                        </select>
                    </div>
                    <div class="col">
                        <select size="7" multiple="multiple" class="form-select" name="cron-hours"
                                id="cron-hours" onchange="updateField('hours')">
                            <option selected="selected" value="0">00</option>
                            <option selected="selected" value="1">01</option>
                            <option selected="selected" value="2">02</option>
                            <option selected="selected" value="3">03</option>
                            <option selected="selected" value="4">04</option>
                            <option selected="selected" value="5">05</option>
                            <option selected="selected" value="6">06</option>
                            <option selected="selected" value="7">07</option>
                            <option selected="selected" value="8">08</option>
                            <option selected="selected" value="9">09</option>
                            <option selected="selected" value="10">10</option>
                            <option selected="selected" value="11">11</option>
                            <option selected="selected" value="12">12</option>
                            <option selected="selected" value="13">13</option>
                            <option selected="selected" value="14">14</option>
                            <option selected="selected" value="15">15</option>
                            <option selected="selected" value="16">16</option>
                            <option selected="selected" value="17">17</option>
                            <option selected="selected" value="18">18</option>
                            <option selected="selected" value="19">19</option>
                            <option selected="selected" value="20">20</option>
                            <option selected="selected" value="21">21</option>
                            <option selected="selected" value="22">22</option>
                            <option selected="selected" value="23">23</option>
                        </select>
                    </div>
                    <div class="col">
                        <select size="7" multiple="multiple" class="form-select" name="cron-dom" id="cron-dom"
                                onchange="updateField('dom')">
                            <option selected="selected" value="1">01</option>
                            <option selected="selected" value="2">02</option>
                            <option selected="selected" value="3">03</option>
                            <option selected="selected" value="4">04</option>
                            <option selected="selected" value="5">05</option>
                            <option selected="selected" value="6">06</option>
                            <option selected="selected" value="7">07</option>
                            <option selected="selected" value="8">08</option>
                            <option selected="selected" value="9">09</option>
                            <option selected="selected" value="10">10</option>
                            <option selected="selected" value="11">11</option>
                            <option selected="selected" value="12">12</option>
                            <option selected="selected" value="13">13</option>
                            <option selected="selected" value="14">14</option>
                            <option selected="selected" value="15">15</option>
                            <option selected="selected" value="16">16</option>
                            <option selected="selected" value="17">17</option>
                            <option selected="selected" value="18">18</option>
                            <option selected="selected" value="19">19</option>
                            <option selected="selected" value="20">20</option>
                            <option selected="selected" value="21">21</option>
                            <option selected="selected" value="22">22</option>
                            <option selected="selected" value="23">23</option>
                            <option selected="selected" value="24">24</option>
                            <option selected="selected" value="25">25</option>
                            <option selected="selected" value="26">26</option>
                            <option selected="selected" value="27">27</option>
                            <option selected="selected" value="28">28</option>
                            <option selected="selected" value="29">29</option>
                            <option selected="selected" value="30">30</option>
                            <option selected="selected" value="31">31</option>
                        </select>
                    </div>
                    <div class="col">
                        <select size="7" multiple="multiple" class="form-select" name="cron-months"
                                id="cron-months" onchange="updateField('months')">
                            <option selected="selected" value="1"><?php echo $language["JANUARY"]; ?></option>
                            <option selected="selected" value="2"><?php echo $language["FEBRUARY"]; ?></option>
                            <option selected="selected" value="3"><?php echo $language["MARCH"]; ?></option>
                            <option selected="selected" value="4"><?php echo $language["APRIL"]; ?></option>
                            <option selected="selected" value="5"><?php echo $language["MAY"]; ?></option>
                            <option selected="selected" value="6"><?php echo $language["JUNE"]; ?></option>
                            <option selected="selected" value="7"><?php echo $language["JULY"]; ?></option>
                            <option selected="selected" value="8"><?php echo $language["AUGUST"]; ?></option>
                            <option selected="selected" value="9"><?php echo $language["SEPTEMBER"]; ?></option>
                            <option selected="selected" value="10"><?php echo $language["OCTOBER"]; ?></option>
                            <option selected="selected" value="11"><?php echo $language["NOVEMBER"]; ?></option>
                            <option selected="selected" value="12"><?php echo $language["DECEMBER"]; ?></option>
                        </select>
                    </div>
                    <div class="col">
                        <select size="7" multiple="multiple" class="form-select" name="cron-dow" id="cron-dow"
                                onchange="updateField('dow')">
                            <option selected="selected" value="0"><?php echo $language["SUNDAY"]; ?></option>
                            <option selected="selected" value="1"><?php echo $language["MONDAY"]; ?></option>
                            <option selected="selected" value="2"><?php echo $language["TUESDAY"]; ?></option>
                            <option selected="selected" value="3"><?php echo $language["WEDNESDAY"]; ?></option>
                            <option selected="selected" value="4"><?php echo $language["THURSDAY"]; ?></option>
                            <option selected="selected" value="5"><?php echo $language["FRIDAY"]; ?></option>
                            <option selected="selected" value="6"><?php echo $language["SATURDAY"]; ?></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <! --
        Cron Select All
        -->
        <div class="card">
            <div class="card-body text-center">
                <div class="row">
                    <div class="col">
                        <div class="form-floating">
                            <a href="javascript:cronHelperSelectAll('#cron-minutes')"><?php echo $language["EVERY_MINUTE"]; ?></a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating">
                            <a href="javascript:cronHelperSelectAll('#cron-hours')"><?php echo $language["EVERY_HOUR"]; ?></a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating">
                            <a href="javascript:cronHelperSelectAll('#cron-dom')"><?php echo $language["EVERY_DAY"]; ?></a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating">
                            <a href="javascript:cronHelperSelectAll('#cron-months')"><?php echo $language["EVERY_MONTH"]; ?></a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating">
                            <a href="javascript:cronHelperSelectAll('#cron-dow')"><?php echo $language["EVERY_WEEK_DAY"]; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <! --
        Cron Output
        -->
        <div class="card">
            <div class="card-header text-center">
                <?php echo $language["CRON_OUTPUT"]; ?>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="form-floating">
                            <input value="<?php echo $config_data["CRON"]["cron_output_minutes"]; ?>"
                                   id="cron_output_minutes" name="cron_output_minutes" class="form-control"/>
                            <label for="cron_output_minutes"><?php echo $language["MINUTES"]; ?></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating">
                            <input value="<?php echo $config_data["CRON"]["cron_output_hours"]; ?>"
                                   id="cron_output_hours" name="cron_output_hours" class="form-control"/>
                            <label for="cron_output_hours"><?php echo $language["HOURS"]; ?></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating">
                            <input value="<?php echo $config_data["CRON"]["cron_output_dom"]; ?>" id="cron_output_dom"
                                   name="cron_output_dom" class="form-control"/>
                            <label for="cron_output_dom"><?php echo $language["DAY_OF_MONTH"]; ?></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating">
                            <input value="<?php echo $config_data["CRON"]["cron_output_months"]; ?>"
                                   id="cron_output_months" name="cron_output_months" class="form-control"/>
                            <label for="cron_output_months"><?php echo $language["MONTH"]; ?></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating">
                            <input value="<?php echo $config_data["CRON"]["cron_output_dow"]; ?>" id="cron_output_dow"
                                   name="cron_output_dow" class="form-control"/>
                            <label for="cron_output_dow"><?php echo $language["WEEK_DAY"]; ?></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <! --
        Cron Template
        -->
        <div class="card">
            <div class="card-header text-center">
                <?php echo $language["CRON_PRESET_TEMPLATES"]; ?>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="list-group">
                            <a href="javascript:cronTemplate(0);" title=""
                               class="a-template"><?php echo $language["SELECT_ALL"]; ?></a>
                            <a href="javascript:cronTemplate(1);" title=""
                               class="a-template"><?php echo $language["EVERY_5_MINUTES"]; ?></a>
                            <a href="javascript:cronTemplate(2);" title=""
                               class="a-template"><?php echo $language["EVERY_15_MINUTES"]; ?></a>
                            <a href="javascript:cronTemplate(3);" title=""
                               class="a-template"><?php echo $language["EVERY_30_MINUTES"]; ?></a>
                            <a href="javascript:cronTemplate(4);" title=""
                               class="a-template"><?php echo $language["EVERY_HOUR"]; ?></a>
                            <a href="javascript:cronTemplate(5);" title=""
                               class="a-template"><?php echo $language["EVERY_3_HOURS"]; ?></a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="list-group">
                            <a href="javascript:cronTemplate(6);" title=""
                               class="a-template"><?php echo $language["EVERY_4_HOURS"]; ?></a>
                            <a href="javascript:cronTemplate(7);" title=""
                               class="a-template"><?php echo $language["EVERY_6_HOURS"]; ?></a>
                            <a href="javascript:cronTemplate(8);" title=""
                               class="a-template"><?php echo $language["EVERY_12_HOURS"]; ?></a>
                            <a href="javascript:cronTemplate(9);" title=""
                               class="a-template"><?php echo $language["EVERY_DAY_AT"]; ?> 12:30am</a>
                            <a href="javascript:cronTemplate(10);" title=""
                               class="a-template"><?php echo $language["EVERY_SUNDAY_AT"]; ?> 12:10am</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <! --
        Settings Options
        -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <?php echo $language["SETTINGS"]; ?>
                </h5>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <select class="form-control" name="active" id="active">
                                <option value="true" <?php if ($config_data["ACTIVE"]["active"] == true) {
                                    echo "selected='selected'";
                                } ?>><?php echo $language["ENABLED"]; ?></option>
                                <option value="false" <?php if ($config_data["ACTIVE"]["active"] == false) {
                                    echo "selected='selected'";
                                } ?>><?php echo $language["DISABLED"]; ?></option>
                            </select>
                            <label for="active"><?php echo $language["ENABLED"]; ?></label>
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
                            <input placeholder="*" class="form-control" name="filehost_root_path"
                                   id="filehost_root_path"
                                   value="<?php echo $config_data["FILEHOST"]["filehost_root_path"] ?>" required/>
                            <label for="filehost_root_path"><?php echo $language["FILEHOST_ROOT_PATH"]; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["FILEHOST_ROOT_PATH_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <select class="form-control" name="remote" id="remote" required>
                                <option value="">- <?php echo $language["SELECT_OPTION"]; ?> -</option>
                                <?php echo $remote_select; ?>
                            </select>
                            <label for="remote"><?php echo $language["RCLONE_REMOTE"]; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["AVAILABLE_REMOTES_SELECT_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <input placeholder="*" class="form-control" name="structure" id="structure"
                                   value="<?php echo $config_data["RCLONE"]["structure"] ?>"
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
        DirectAdmin Options
        -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <?php echo $language["DIRECTADMIN"]; ?>
                </h5>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <select class="form-control" id="directadmin_user" name="directadmin_user" required>
                                <option value="">- <?php echo $language["SELECT_OPTION"]; ?> -</option>
                                <?php echo $directadmin_user_select; ?>
                            </select>
                            <label for="directadmin_user"><?php echo $language["DIRECTADMIN_USERNAME"]; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["DIRECTADMIN_USERNAME_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <select class="form-control" id="directadmin_domain" name="directadmin_domain" required>
                                <option value="">- <?php echo $language["SELECT_OPTION"]; ?> -</option>
                                <?php echo $directadmin_domain_select; ?>
                            </select>
                            <label for="directadmin_domain"><?php echo $language["DIRECTADMIN_DOMAIN"]; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["DIRECTADMIN_DOMAIN_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <select class="form-control" id="directadmin_subdomain" name="directadmin_subdomain">
                                <option value="">- <?php echo $language["SELECT_OPTION"]; ?> -</option>
                                <?php echo $directadmin_subdomain_select; ?>
                            </select>
                            <label for="directadmin_subdomain"><?php echo $language["DIRECTADMIN_SUBDOMAIN"]; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["DIRECTADMIN_SUBDOMAIN_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <input placeholder="*" class="form-control" name="directadmin_backup_path"
                                   id="directadmin_backup_path"
                                   value="<?php echo $config_data["DIRECTADMIN_WWW"]["directadmin_backup_path"]; ?>"
                                   required/>
                            <label for="directadmin_backup_path"><?php echo $language["BACKUP_PATH"]; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["DIRECTADMIN_BACKUP_PATH_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <input placeholder="*" class="form-control" name="directadmin_exclude_path"
                                   id="directadmin_exclude_path"
                                   value="<?php echo $config_data["DIRECTADMIN_WWW"]["directadmin_exclude_path"]; ?>"/>
                            <label for="directadmin_exclude_path"><?php echo $language["EXCLUDE_FOLDER"]; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["DIRECTADMIN_EXCLUDE_PATH_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <select class="form-control" name="database_name" id="database_name">
                                <option value="">- <?php echo $language["SELECT_OPTION"]; ?> -</option>
                                <?php echo $directadmin_database_select; ?>
                            </select>
                            <label for="database_name"><?php echo $language["DATABASE_TO_BACKUP"]; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["DIRECTADMIN_DATABASE_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <select class="form-control" name="database_type" id="database_type">
                                <option value="">- <?php echo $language["SELECT_OPTION"]; ?> -</option>
                                <option value="mysql" <?php if ($config_data["DIRECTADMIN_DB"]["database_type"] == "mysql") {
                                    echo 'selected="selected"';
                                }
                                if ($directadminarray["INSTALLED"]["mysql_installed"] != 1) {
                                    echo "disabled";
                                } ?> >
                                    MySQL <?php if ($directadminarray["INSTALLED"]["mysql_installed"] != 1) {
                                        echo " - " . $language["NOT_INSTALLED"];
                                    } ?>
                                </option>
                                <option value="postgresql" <?php if ($config_data["DIRECTADMIN_DB"]["database_type"] == "postgresql") {
                                    echo 'selected="selected"';
                                }
                                if ($directadminarray["INSTALLED"]["postgresql_installed"] != 1) {
                                    echo "disabled";
                                } ?> >
                                    PostgreSQL <?php if ($directadminarray["INSTALLED"]["postgresql_installed"] != 1) {
                                        echo " - " . $language["NOT_INSTALLED"];
                                    } ?>
                                </option>
                                <option value="mongodb" <?php if ($config_data["DIRECTADMIN_DB"]["database_type"] == "mongodb") {
                                    echo 'selected="selected"';
                                }
                                if ($directadminarray["INSTALLED"]["mongodb_installed"] != 1) {
                                    echo "disabled";
                                } ?> >
                                    MongoDB <?php if ($directadminarray["INSTALLED"]["mongodb_installed"] != 1) {
                                        echo " - " . $language["NOT_INSTALLED"];
                                    } ?>
                                </option>
                            </select>
                            <label for="compression"><?php echo $language["DATABASE_TYPE"]; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["DIRECTADMIN_TYPE_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <select class="form-control" name="compression" id="compression">
                                <option value="zip" <?php if ($config_data["COMPRESSION"]["compression"] == "zip") {
                                    echo 'selected="selected"';
                                }
                                if ($directadminarray["INSTALLED"]["zip_installed"] != 1) {
                                    echo "disabled";
                                } ?> >
                                    zip <?php if ($directadminarray["INSTALLED"]["zip_installed"] != 1) {
                                        echo " - " . $language["NOT_INSTALLED"];
                                    } ?>
                                </option>
                                <option value="tar" <?php if ($config_data["COMPRESSION"]["compression"] == "tar") {
                                    echo 'selected="selected"';
                                } ?> >
                                    tar
                                </option>
                                <option value="gzip" <?php if ($config_data["COMPRESSION"]["compression"] == "gzip") {
                                    echo 'selected="selected"';
                                } ?> >
                                    gzip
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
        Email Options
        -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <?php echo $language["EMAIL_LOG"]; ?>
                </h5>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <select class="form-control" name="send_email_enabled" id="send_email_enabled">
                                <option value="true" <?php if ($config_data["EMAIL"]["send_email_enabled"] == true) {
                                    echo 'selected="selected"';
                                } ?> ><?php echo $language["ENABLED"]; ?></option>
                                <option value="false" <?php if ($config_data["EMAIL"]["send_email_enabled"] == false) {
                                    echo 'selected="selected"';
                                } ?> ><?php echo $language["DISABLED"]; ?></option>
                            </select>
                            <label for="send_email_enabled"><?php echo $language["SEND_EMAIL_NOTIFICATIONS"]; ?></label>
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
                            <input placeholder="Enter email address." class="form-control" name="send_email_address"
                                   id="send_email_address"
                                   value="<?php echo $config_data["EMAIL"]["send_email_address"] ?>" required/>
                            <label for="send_email_address"><?php echo $language["EMAIL_ADDRESS"]; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span><?php echo $language["EMAIL_DESC"]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <! --
        Submit Form
        -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="form-floating">
                            <input type="hidden" id="cron_id" name="cron_id" value="<?php echo $cronId ?>">
                            <input type="hidden" id="cron_state" name="cron_state" value="<?php echo $cronState ?>">
                            <input type="hidden" id="form_id" name="form_id" value="cron">
                            <button type="submit" class="button" id="igd2d"
                                    value="submit"><?php echo $language["SAVE_CRON"]; ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    /**
     * JS to filter user, domain, and sub-domain select elements.
     */
    $('#directadmin_domain option').each(function () {
        if (!this.selected) {
            $(this).prop('disabled', true);
        }
    });

    $('#directadmin_subdomain option').each(function () {
        if (!this.selected) {
            $(this).prop('disabled', true);
        }
    });

    $("#directadmin_user").on("change", function () {
        let sel = this.value,
            $ddl = $("#directadmin_domain, #directadmin_subdomain");
        if (!sel) {
            $ddl.find("option").show().prop("disabled", false);
            $ddl.val("");
            $ddl.prop('disabled', true);
        } else {
            $ddl.find("option").show().prop("disabled", false);
            $ddl.val("");
            $("#directadmin_domain").find("option").hide().prop('disabled', true);
            $("#directadmin_domain").find("option[data-project*='" + sel + "']").show().prop('disabled', false);
            $("#directadmin_domain").prop('disabled', false);
        }
    });

    $("#directadmin_domain").on("change", function () {
        let sel = this.value,
            $ddl = $("#directadmin_subdomain");
        if (!sel) {
            $ddl.find("option").show().prop("disabled", false);
            $ddl.val("");
            $("#directadmin_backup_path").val("");
        } else {
            let selArray = <?php echo $domainJSON; ?>,
                selUsr = $("#directadmin_user").val(),
                selDir = $("#directadmin_domain").val(),
                jsonStr = JSON.stringify(selArray["users"][selUsr]["domains"][selDir]["public_html"]);
            $ddl.find("option").show().prop("disabled", false);
            $ddl.val("");
            $("#directadmin_subdomain").find("option").hide().prop('disabled', true);
            $("#directadmin_subdomain").find("option[data-project*='" + sel + "']").show().prop('disabled', false);
            $("#directadmin_subdomain").prop('disabled', false);
            $("#directadmin_backup_path").val(jsonStr.slice(1, jsonStr.length - 1));
        }
    });

    $("#directadmin_subdomain").on("change", function () {
        let selArray = <?php echo $domainJSON; ?>,
            selUsr = $("#directadmin_user").val(),
            selDir = $("#directadmin_domain").val(),
            selSubDir = $("#directadmin_subdomain").val(),
            jsonStr = JSON.stringify(selArray["users"][selUsr]["domains"][selDir]["subdomains"][selSubDir]["public_html"]);
        $("#directadmin_backup_path").val(jsonStr.slice(1, jsonStr.length - 1));
    });
</script>
