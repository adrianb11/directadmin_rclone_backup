<?php

$language = array(
    //Days
    "MONDAY" => "Monday",
    "TUESDAY" => "Tuesday",
    "WEDNESDAY" => "Wednesday",
    "THURSDAY" => "Thursday",
    "FRIDAY" => "Friday",
    "SATURDAY" => "Saturday",
    "SUNDAY" => "Sunday",

    //Months
    "JANUARY" => "January",
    "FEBRUARY" => "February",
    "MARCH" => "March",
    "APRIL" => "April",
    "MAY" => "May",
    "JUNE" => "June",
    "JULY" => "July",
    "AUGUST" => "August",
    "SEPTEMBER" => "September",
    "OCTOBER" => "October",
    "NOVEMBER" => "November",
    "DECEMBER" => "December",

    //Times
    "MINUTES" => "Minutes",
    "EVERY_MINUTE" => "Every Minute",
    "HOURS" => "Hours",
    "EVERY_HOUR" => "Every Hour",
    "MONTH" => "Month",
    "EVERY_MONTH" => "Every Month",
    "WEEK_DAY" => "Week Day",
    "EVERY_WEEK_DAY" => "Every Week Day",
    "DAY_OF_MONTH" => "Day Of Month",
    "EVERY_DAY" => "Every Day",
    "SELECT_ALL" => "Select All",
    "EVERY_5_MINUTES" => "Every 5 Minutes",
    "EVERY_15_MINUTES" => "Every 15 Minutes",
    "EVERY_30_MINUTES" => "Every 30 Minutes",
    "EVERY_3_HOURS" => "Every 3 Hours",
    "EVERY_4_HOURS" => "Every 4 Hours",
    "EVERY_6_HOURS" => "Every 6 Hours",
    "EVERY_12_HOURS" => "Every 12 Hours",
    "EVERY_DAY_AT" => "Every Day At",
    "EVERY_SUNDAY_AT" => "Every Sunday At",

    //Status
    "ENABLED" => "Enabled",
    "DISABLED" => "Disabled",
    "active" => "Active",
    "inactive" => "Inactive",
    "pending" => "Pending",
    "NOT_INSTALLED" => "NOT INSTALLED",
    "INSTALLED" => "INSTALLED",
    "TRUE" => "True",
    "FALSE" => "False",

    //Page Titles
    "OVERVIEW_PAGE_TITLE" => "Cron Overview",
    "CREATE_NEW_CRONJOB_TITLE" => "Create/Edit Cronjob",
    "OPTIONS_PAGE_TITLE" => "View/Change Options",
    "SETTINGS_PAGE_TITLE" => "View Settings",
    "RCLONE_NOT_FOUND" => "RClone Not Found",

    //Sentences
    "RCLONE_NOT_FOUND_DESC" => "RClone was not found on your system.  Please check if it is installed correctly.",
    "LANGUAGE_DESC" => "Please select a language file.  All currently available languages will be displayed.",
    "COMPRESSION_DESC" => "Please select which compression method to use.  Available options are: Zip, tar, and gzip.",
    "DEFAULT_FILEHOST_ROOT_PATH_DESC" => "Please enter the default file host root path.",
    "FILEHOST_ROOT_PATH_DESC" => "Please enter the file host root path.",
    "ENABLED_DESC" => "Please select whether this should be enabled or disabled.",
    "DEFAULT_EMAIL_DESC" => "Please enter the default email address reports should be sent from.",
    "EMAIL_DESC" => "Please enter the email address reports should be sent to.",
    "AVAILABLE_REMOTES_DESC" => "Please enter available remotes.  Multiple remotes should be separated by a comma.",
    "AVAILABLE_REMOTES_SELECT_DESC" => "Please select a remote to use.",
    "DEFAULT_FOLDER_STRUCTURE_DESC" => "Please enter the folder structure to use.",
    "FOLDER_STRUCTURE_DESC" => '<strong>Available options are: U D S o e a y m d</strong><br><ul class="list-group"> <li class="list-group-item">U = DirectAdmin Username (selected when creating new job)</li> <li class="list-group-item">D = DirectAdmin Domain (selected when creating new job)</li> <li class="list-group-item">S = DirectAdmin Sub-Domain (selected when creating new job)</li> <li class="list-group-item">o = Text "Monthly"</li> <li class="list-group-item">e = Text "Weekly"</li> <li class="list-group-item">a = Text "Daily"</li> <li class="list-group-item">y = Year</li> <li class="list-group-item">m = Month</li> <li class="list-group-item">a = Day</li></ul>e.g.<br>UDSo = admin/yourdomain.com/sub.yourdomain.com/Monthly/ <br>UDSymd = admin/yourdomain/sub.yourdomain.com/2021/01/01/',
    "DIRECTADMIN_USERNAME_DESC" => "Please select the DirectAdmin username.",
    "DIRECTADMIN_DOMAIN_DESC" => "Please select the DirectAdmin domain.",
    "DIRECTADMIN_SUBDOMAIN_DESC" => "Please select the DirectAdmin sub-domain (not required).",
    "DIRECTADMIN_BACKUP_PATH_DESC" => "This backup path has been automatically generated.  Please amend the path if required.",
    "DIRECTADMIN_EXCLUDE_PATH_DESC" => "If required, please enter the full path to a directory you wish to exclude from the backup (not required).",
    "DIRECTADMIN_DATABASE_DESC" => "Please select a database to backup (not required).",
    "DIRECTADMIN_TYPE_DESC" => "Please select the database type (not required).",
    "IGNORE_CERTIFICATE_DESC" => "Set to TRUE if root certificate cannot be verified.",


    //Operations
    "RECHECK_SOFTWARE" => "Recheck Installed Software",
    "RECHECK_ALL" => "Recheck All",
    "RUN" => "Run",
    "CREATE" => "Create",
    "SAVE_CRON" => "Save Cron",
    "SAVE_OPTIONS" => "Save Options",
    "EDIT" => "Edit",
    "SUBMIT" => "Submit",
    "RUN_SCRIPTS" => "Run Scripts",
    "NEW_CRON_CREATED" => "New cron created successfully.",
    "CRON_EDITED" => "Cron was edited successfully.",
    "DEFAULT_OPTIONS_SAVED" => "Default options were saved successfully.",

    "LANGUAGE_SELECTION" => "Language Selection",
    "LANGUAGE" => "Language",
    "SETTINGS" => "Settings",
    "SELECT_OPTION" => "Select option",

    //Directories
    "FILEHOST_ROOT_PATH" => "File Host Root Path",
    "DEFAULT_FILEHOST_ROOT_PATH" => "Default File Host Root Path",
    "DEFAULT_FOLDER_STRUCTURE" => "Default Folder Structure",
    "FOLDER_STRUCTURE" => "Folder Structure",
    "BACKUP_PATH" => "Backup Path",
    "EXCLUDE_FOLDER" => "Exclude Folder",

    //Email
    "DEFAULT_SEND_EMAIL" => "Default Send Email",
    "EMAIL_LOG" => "Email Log",
    "SEND_EMAIL_NOTIFICATIONS" => "Send Email Notifications",
    "EMAIL_ADDRESS" => "Email Address",
    "EMAIL" => "Email",

    //Cron
    "CRON_CREATOR" => "Cron Creator",
    "CRON_PRESET_TEMPLATES" => "Cron Preset Templates",
    "CRON_OUTPUT" => "Cron Output",
    "RUN_CRON_MANAGE" => "Run Cron Manager",

    //RClone
    "RCLONE" => "RClone",
    "AVAILABLE_REMOTES" => "Available Remotes",
    "RCLONE_REMOTE" => "RClone Remote",

    //DirectAdmin
    "DIRECTADMIN" => "DirectAdmin",
    "DIRECTADMIN_USERNAME" => "DirectAdmin Username",
    "DIRECTADMIN_DOMAIN" => "DirectAdmin Domain",
    "DIRECTADMIN_SUBDOMAIN" => "DirectAdmin Sub-Domain",

    //Database
    "DATABASE_NAME" => "Database Name",
    "DATABASE_TYPE" => "Database Type",
    "DATABASE_TO_BACKUP" => "Database To Backup",

    //Compression
    "DEFAULT_COMPRESSION" => "Default Compression",
    "COMPRESSION" => "Compression",

    "FILEHOST" => "File Host",

    //SSL
    "IGNORE_CERTIFICATE" => "Ignore Peer Certificate"
);