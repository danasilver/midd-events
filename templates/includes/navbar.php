<nav class="navbar navbar-default" role="navigation">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand visible-xs" href="<?php echo $index_prefix; ?>index.php">Midd Events</a>
  </div>

  <div class="collapse navbar-collapse" id="navbar-collapse">
    <div class="container">
      <div class="row index-header">
        <div class="col-md-3">
          <a class="navbar-brand-link" href="<?php echo $index_prefix; ?>index.php"><span class="h2 hidden-xs">Midd Events</span></a>
        </div>

        <form role="form" class="" action="<?php echo $index_prefix; ?>search.php" method="GET">
          <div class="col-md-4 col-md-offset-1">
            <div class="form-group">
              <div class="input-group">
                <input name="q" type="text" class="form-control" id="search" placeholder="Search events" autocomplete="off">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
                </span>
              </div>
            </div>
          </div>

          <div class="col-sm-1 col-xs-3">
            <button id="searchFilterToggle" type="button" data-toggle="button" class="btn btn-default">
              <span class="glyphicon glyphicon-filter"></span>
            </button>
          </div>

          <?php if (array_key_exists("username", $_SESSION)) { ?>
          <div class="col-md-3">
            <ul class="list-unstyled list-inline pull-right">
              <li><a href="<?php echo $index_prefix; ?>new.php" class="btn btn-primary">New Event</a></li>
              <li><a href="<?php if (!$in_users) { echo "users/"; } ?>logout.php" class="btn btn-default"><span class="glyphicon glyphicon-off"></span></a></li>
            </ul>
          </div>
          <?php } else { ?>
          <div class="col-md-3">
            <ul class="list-unstyled list-inline pull-right">
              <li><a href="<?php if (!$in_users) { echo "users/"; } ?>login.php" class="btn btn-default">Login</a></li>
              <li><a href="<?php if (!$in_users) { echo "users/"; } ?>signup.php" class="btn btn-primary">Sign up</a></li>
            </ul>
          </div>
          <?php } ?>

          </div>

          <div id="searchFilter" class="form-group hide">
            <div class="row">

              <div class=" form-group col-md-3 col-md-offset-3">
                <select name="o[]" multiple class="form-control" id="searchOrg">
                <?php foreach ($orgs as $org) { ?>
                  <option value="<?php echo $org ?>"><?php echo $org ?></option>
                <?php } ?>
                </select>
              </div>

              <div class="form-group col-md-3">
                <select name="c[]" multiple class="form-control" id="searchCat">
                <?php foreach ($cats as $cat) { ?>
                  <option value="<?php echo $cat ?>"><?php echo $cat ?></option>
                <?php } ?>
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</nav>