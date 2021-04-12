<div class="container">
      <div class="row">
        <div id="profile" class="col-md-3 hidden-sm hidden-xs">
          
          <div id="profile-resume" class="card">
            <a href="#"><img class="card-img-top img-responsive" src="imgs/landscape.jpg"></a>
            <div class="card-block">
              <img src="imgs/jon.png" class="card-img">
              <h4 class="card-title">Jonny Doo <small>@jonnydoo</small></h4>
              <p class="card-text">Dog goes woofy. Did you said squirrel?</p>
              <ul class="list-inline list-unstyled">
                <li id="card-tweets">
                  <a href="#">
                    <span class="profile-stats">Tweets</span>
                    <span class="profile-value">99k</span>
                  </a>
                </li>
                <li class="card-following">
                  <a href="#">
                    <span class="profile-stats">Following</span>
                    <span class="profile-value">7</span>
                  </a>
                </li>
                <li class="card-followers">
                  <a href="#">
                    <span class="profile-stats">Followers</span>
                    <span class="profile-value">132k</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div id="profile-settings" class="card">
            <ul class="nav nav-pills nav-stacked">
              <li role="presentation" class="active" data-toggle="tab">
                <a href="#">
                  Account
                  <span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span>
                </a>
              </li>
              <li role="presentation">
                <a href="#">
                  Security
                  <span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span>
                </a>
              </li>
              <li role="presentation">
                <a href="#">
                  Notifications
                  <span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span>
                </a>
              </li>
              <li role="presentation">
                <a href="#">
                  Design
                  <span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span>
                </a>
              </li>
            </ul>
          </div>

        </div>

        <div id="main" class="col-sm-12 col-md-6">
          <ul id="account-tabs" class="nav nav-tabs nav-justified">
            <li role="presentation" class="active">
              <a href="#account-user" data-toggle="tab">User info</a>
            </li>
            <li role="presentation">
              <a href="#account-language" data-toggle="tab">Language</a>
            </li>
            <li role="presentation">
              <a href="#account-mobile" data-toggle="tab">Mobile</a>
            </li>
          </ul>

        <div class="tab-content card">
          <div role="tabpanel" class="tab-pane active" id="account-user">
            <form class="form-horizontal">
              <div class="form-group">
                <label class="col-sm-3 control-label">Name</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" value="Jonny Doo">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Username</label>
                <div class="col-sm-9">
                  <div class="input-group">
                    <div class="input-group-addon">@</div>
                    <input type="text" class="form-control" value="jonnydoo">
                  </div>    
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Email</label>
                <div class="col-sm-9">
                  <input type="email" class="form-control" value="jonnydoo@dogmail.com">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                  <button type="submit" class="btn btn-primary">
                    Save changes
                  </button>
                </div>
              </div>
            </form>
          </div>


          <div role="tabpanel" class="tab-pane" id="account-language">
            Language tab pane
          </div>
          <div role="tabpanel" class="tab-pane" id="account-mobile">
            Mobile tab pane
          </div>
        </div>

        </div>

        <div id="right-content" class="col-md-3 hidden-sm hidden-xs">
          <ul class="list-group">
            <li class="list-group-item list-group-item-info">
              Dog stats
            </li>
            <li class="list-group-item">
              Number of day rides
              <span class="label label-success">3</span>
            </li>
            <li class="list-group-item">
              Captured mice
              <span class="label label-danger">87</span>
            </li>
            <li class="list-group-item">
              Postmen frightened
              <span class="label label-default">2</span>
            </li>
            <li class="list-group-item">
              Always alert badge
              <span class="badge glyphicon glyphicon-star" aria-hidden="true">
              </span>
            </li>
          </ul>
        </div>
      </div>
    </div>