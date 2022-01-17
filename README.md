# DirectAdmin RClone Backup Interface

![GitHub release (latest by date)](https://img.shields.io/github/v/release/adrianb11/directadmin_rclone_backup)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Scrutinizer code quality (GitHub/Bitbucket)](https://img.shields.io/scrutinizer/quality/g/adrianb11/directadmin_rclone_backup/master?color=teal)](https://scrutinizer-ci.com/g/adrianb11/directadmin_rclone_backup/?branch=master)

## Description

This is a DirectAdmin plugin that creates Cron Jobs to backup files/databases and uses RClone to upload them to a
filehost.

* Files can be compressed into zip, tar, and gzip formats.
* MySQL, PostgreSQL, and MongoDB databases are supported.

This was built to make my life easier and because of this, is tailored more to my needs.

Built for DirectAdmin 1.61.5 using Evolution skin. I can not guarantee other DA versions or skins will work correctly. I
have also only tested this with Dropbox but other hosts should work too. My DA license is only for 1 account, so I
haven't tested this on a multi account server.

**Warning** - This plugin adds jobs to the root crontab and runs shell scripts as root. This is so all domains can be
backed up from a single account. This also makes backing up databases easier as password does not need to be recorded.
While this is not strictly necessary on a single account license (can be run as admin), files not accessible by admin
cannot be backed up. If this makes you nervous, please do not install.

### Requirements

* [RClone](https://github.com/rclone/rclone)
* RClone remotes already created.

### Installation

To install, simple download the package and install via the package manager in DirectAdmin. During installation, this
plugin will check if RClone and other packages are installed

### How It Works

There are 4 main sections to this plugin:-

* **Configuration files**
    * Each job has its details saved into an .ini file which is used for the actual backup process and to display
      details on the web interface.
* **The web interface**
    * This allows you to create, edit jobs and view any jobs which have been created.
    * Jobs which have been created are separated into active, inactive, and pending categories.
    * Each job on the overview tab can be expanded to show its details.
* **manage_cron script**
    * This shell script is used to manage any newly created or edited jobs.
    * On install, a new cronjob is added runs manage_cron.sh on a regular basis.
    * On each run, this will scan for any newly created or edited jobs and create a new cronjob for them.
    * The cron created will include a path to the .ini for that job.
* **backup_job script**
    * This is responsible for the actual backup of files and execution of RClone.
    * The backup_job script will use the .ini file passed to create a backup of files and databases requested.

### Usage

* **Once installed, check "View Settings" tab to view software installed.**
    * Software check can be re-run individually or collectively.
    * manage_cron.sh script can be run from here instead of waiting from scheduled cronjob to run.
* **Enter default options in "View/Change Options" tab.**
    * Language Selection:-
        * Language: Any language files created will be displayed in this dropdown box.
    * Default Compression:-
        * Compression: Sets the default compression method to use.
    * File Host:-
        * File Host Root Path: Sets the root path where files should be uploaded. This will be appended with the full
          directory path created.
    * Email:-
        * Enabled: Sets whether emailed reports should be sent by default.
        * Default Send Email: This is the email address reports will be sent from.
    * RClone:-
        * Available Remotes: List all available remotes you wish to be used. All remotes must be separated by a comma.
        * Folder Structure: Sets the path to be appended to Root Path. Options are **Case Sensitive!**
* **Create a new job on the "Create/Edit Cronjob" tab.**
    * Cron Creator:-
        * Selectable fields to create cronjob times.
        * Links to select every minute, hour, day, month, and week day.
    * Cron Preset Templates:-
        * Clickable preset templates which automatically fill in cron time.
    * Settings:-
        * Enabled: Sets whether this job should be enabled (creates ini and cronjob) or disabled (creates ini only).
        * File Host Root Path: Sets the root path where files should be uploaded. This will be appended with the full
          directory path created. Field taken from default option saved in options tab.
        * List of all remotes saved in options tab.
        * Folder Structure: Sets the path to be appended to Root Path. Options are **Case Sensitive!**. Field taken from
          default option saved in options tab.
    * DirectAdmin:-
        * DirectAdmin Username: A selectable list of all users.
        * DirectAdmin Domain: A selectable list of all domains. When a username is selected, this field will only show
          domains assigned to it.
        * DirectAdmin Sub-Domain:  A selectable list of all sub-domains. When a domain is selected, this field will only
          show sub-domains assigned to it.
        * Backup Path: This is automatically generated based on the username, domain, and sub-domain fields. Field can
          be edited to backup a specific folder.
        * Exclude Path: Specify the full path to a folder you wish to exclude from the backup.
        * Database To Backup: A dropdown list of all databases.
        * Database Type: Select the database type.
        * Compression: Sets the compression method to use. Field taken from default option saved in options tab.
    * Email Log:-
        * Send Email Notifications: Sets whether a notification should be sent when cron is completed.
        * Email Address: The email address report should be sent to.
* **View created jobs on "Cron Overview" tab.**
    * Active: Displays all active jobs.
    * Inactive: Displays all inactive jobs.
    * Pending: Displays all pending jobs.

### Support

If you find any issues or have a feature
request, [please create a new issue](https://github.com/adrianb11/directadmin_rclone_backup/issues).

### ScreenShots

#### Overview

![](https://github.com/adrianb11/directadmin_rclone_backup/raw/master/ScreenShots/Overview.png)

#### Create/Edit Cronjob

![](https://github.com/adrianb11/directadmin_rclone_backup/raw/master/ScreenShots/Create.png)

#### View/Change Options

![](https://github.com/adrianb11/directadmin_rclone_backup/raw/master/ScreenShots/Options.png)

#### View Settings

![](https://github.com/adrianb11/directadmin_rclone_backup/raw/master/ScreenShots/Settings.png)

### Acknowledgments

- [Thomas Ba - Cron Expression Generator](https://github.com/thomasba/cron-expression-generator)
- [Terrorhawk - DirectAdmin API Communication](https://github.com/Terrorhawk/Capri)

### License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.