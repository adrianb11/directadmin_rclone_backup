<! --
Settings
-->
<div class="container-fluid">

    <! --
    Run Scripts
    -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">
                <?php echo $language["RUN_SCRIPTS"]; ?>
            </h5>

            <! --
            Run Manage Cron Script
            -->
            <div class="row">
                <div class="col-1">
                    <div class="form-floating">
                        <a href="index.html?tab=settings&run_script=manage" class="btn btn-primary" role="button"><?php echo $language["RUN"]; ?></a>
                    </div>
                </div>
                <div class="col-11">
                    <div class="form-floating">
                        <h5>
                            <?php echo $language["RUN_CRON_MANAGE"]; ?>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>

    <! --
    ReCheck Installed Software
    -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">
                <?php echo $language["RECHECK_SOFTWARE"]; ?>
            </h5>

            <! --
            ReCheck ZIP
            -->
            <div class="row">
                <div class="col-1">
                    <div class="form-floating">
                        <a href="index.html?tab=settings&software=zip" class="btn btn-primary" role="button"><?php echo $language["RUN"]; ?></a>
                    </div>
                </div>
                <div class="col-11">
                    <div class="form-floating">
                        <h5>
                            ZIP <?php if ($directadminarray["INSTALLED"]["zip_installed"] != 1) { echo $directadminarray["INSTALLED"]["zip_installed"]; ?>
                            <span class="badge bg-danger"><?php echo $language["NOT_INSTALLED"]; ?></span>
                            <?php } else { ?>
                            <span class="badge bg-success"><?php echo $language["INSTALLED"]; ?></span>
                            <?php } ?>
                        </h5>
                    </div>
                </div>
            </div>
            <hr>

            <! --
            ReCheck MySQL
            -->
            <div class="row">
                <div class="col-1">
                    <div class="form-floating">
                        <a href="index.html?tab=settings&software=mysql" class="btn btn-primary" role="button"><?php echo $language["RUN"]; ?></a>
                    </div>
                </div>
                <div class="col-11">
                    <div class="form-floating">
                        <h5>
                            MySQL <?php if ($directadminarray["INSTALLED"]["mysql_installed"] != 1) { echo $directadminarray["INSTALLED"]["mysql_installed"]; ?>
                                <span class="badge bg-danger"><?php echo $language["NOT_INSTALLED"]; ?></span>
                            <?php } else { ?>
                                <span class="badge bg-success"><?php echo $language["INSTALLED"]; ?></span>
                            <?php } ?>
                        </h5>
                    </div>
                </div>
            </div>
            <hr>

            <! --
            ReCheck PostgreSQL
            -->
            <div class="row">
                <div class="col-1">
                    <div class="form-floating">
                        <a href="index.html?tab=settings&software=postgresql" class="btn btn-primary" role="button"><?php echo $language["RUN"]; ?></a>
                    </div>
                </div>
                <div class="col-11">
                    <div class="form-floating">
                        <h5>
                            PostgreSQL <?php if ($directadminarray["INSTALLED"]["postgresql_installed"] != 1) { echo $directadminarray["INSTALLED"]["postgresql_installed"]; ?>
                                <span class="badge bg-danger"><?php echo $language["NOT_INSTALLED"]; ?></span>
                            <?php } else { ?>
                                <span class="badge bg-success"><?php echo $language["INSTALLED"]; ?></span>
                            <?php } ?>
                        </h5>
                    </div>
                </div>
            </div>
            <hr>

            <! --
            ReCheck MongoDB
            -->
            <div class="row">
                <div class="col-1">
                    <div class="form-floating">
                        <a href="index.html?tab=settings&software=mongodb" class="btn btn-primary" role="button"><?php echo $language["RUN"]; ?></a>
                    </div>
                </div>
                <div class="col-11">
                    <div class="form-floating">
                        <h5>
                            MongoDB <?php if ($directadminarray["INSTALLED"]["mongodb_installed"] != 1) { echo $directadminarray["INSTALLED"]["mongodb_installed"]; ?>
                                <span class="badge bg-danger"><?php echo $language["NOT_INSTALLED"]; ?></span>
                            <?php } else { ?>
                                <span class="badge bg-success"><?php echo $language["INSTALLED"]; ?></span>
                            <?php } ?>
                        </h5>
                    </div>
                </div>
            </div>
            <hr>

            <! --
            ReCheck RClone
            -->
            <div class="row">
                <div class="col-1">
                    <div class="form-floating">
                        <a href="index.html?tab=settings&software=rclone" class="btn btn-primary" role="button"><?php echo $language["RUN"]; ?></a>
                    </div>
                </div>
                <div class="col-11">
                    <div class="form-floating">
                        <h5>
                            RClone <?php if ($directadminarray["INSTALLED"]["rclone_installed"] != 1) { echo $directadminarray["INSTALLED"]["rclone_installed"]; ?>
                                <span class="badge bg-danger"><?php echo $language["NOT_INSTALLED"]; ?></span>
                            <?php } else { ?>
                                <span class="badge bg-success"><?php echo $language["INSTALLED"]; ?></span>
                            <?php } ?>
                        </h5>
                    </div>
                </div>
            </div>
            <hr>

            <! --
            ReCheck All
            -->
            <div class="row">
                <div class="col-1">
                    <div class="form-floating">
                        <a href="index.html?tab=settings&software=all" class="btn btn-primary" role="button"><?php echo $language["RUN"]; ?></a>
                    </div>
                </div>
                <div class="col-11">
                    <div class="form-floating">
                        <h5>
                            <?php echo $language["RECHECK_ALL"] ?>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
