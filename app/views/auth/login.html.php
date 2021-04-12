
    <div class="container">
        <div class="row">
        <div id="login">
            <div class="brand text-center col-xs-offset-1 col-xs-10 col-md-offset-4 col-md-4">
                <span class="letter">M</span>&nbsp;
                <span class="letter">o</span>&nbsp;
                <span class="letter">s</span>&nbsp;
                <span class="letter">h</span>&nbsp;
                <span class="letter">i</span>&nbsp;
                <span class="letter">M</span>&nbsp;
                <span class="letter">o</span>&nbsp;
                <span class="letter">s</span>&nbsp;
                <span class="letter">h</span>&nbsp;
                <span class="letter">i</span>
            </div>  
                <div id="login-outer" class="card col-xs-offset-1 col-xs-10 col-md-offset-4 col-md-4">
                    <div class="error-msg-list-login invisible">
                    </div>
                    <form id="login-form" class="form-horizontal" action="login">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input id="login-email" type="text" class="form-control"
                                    placeholder="Username or Email address" name=val[username]>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input id="login-password" type="password" class="form-control" placeholder="Password" name=val[password]>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary btn-block"> Log In</button>
                            </div>
                        </div>
                        <div class="text-center">
                            <a href="#">Forgotten password?</a>
                        </div>
                    </form>
                    <hr>
                    <div class="col-sm-12 text-center">
                        <button id="create-account-btn" class="btn btn-success btn-lg" data-toggle="modal"
                            data-target="#create-account-modal">
                            Create New Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modals -->
    <!-- create account -->
    <div class="modal fade" id="create-account-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button id="close-create-account" type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Sign Up</h4>
                    <div>Its quick and easy</div>
                </div>
                <div class="modal-body">
                    <div class="error-msg-list invisible">
                    </div>
                    <form id="create-account-form" class="form-horizontal" action="user-register">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="createEmail">Email</label>
                            <div class="col-sm-8">
                                <input id="create-email" type="text" class="form-control" name="val[email]">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="createPassword">New Password</label>
                            <div class="col-sm-8">
                                <input id="create-password" type="password" class="form-control" name="val[pwd1]">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="Confirmpassword">Confirm password</label>
                            <div class="col-sm-8">
                                <input id="confirm-create-password" type="password" class="form-control" name="val[pwd2]">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="create-password">Gender</label>
                            <div id="sex-radio" class="col-sm-8">
                                <div class="col-sm-4">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="val[gender]"
                                            id="sex-female" value="female">
                                        <label class="form-check-label" for="female">Female</label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="val[gender]"
                                            id="sex-male" value="male">
                                        <label class="form-check-label" for="male">Male</label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="val[gender]"
                                            id="sex-custom" value="custom">
                                        <label class="form-check-label" for="sex-custom">Custom</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button id="sign-up-button" type="submit" class="btn btn-success btn-lg">
                                Sign Up
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
