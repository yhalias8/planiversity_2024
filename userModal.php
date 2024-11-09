<div class="modal fade" id="new-user-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content wht-bg">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
			  <div class="error_cont"><?php echo $output; ?></div>
                  <form action="/adminpanel_users.php" name="admin_users" method="POST" class="create_user">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input type="text" class="admin-form-control form-control input-lg inp1" name="c_name" id="c_name" maxlength="50" value="<?php echo $_POST['c_name']; ?>" placeholder="write a name">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input class="admin-form-control form-control input-lg inp1" name="c_email" id="c_email" maxlength="100" value="<?php echo $_POST['c_email']; ?>" placeholder="write an email" type="text" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input type="password" class="admin-form-control form-control input-lg inp1" name="c_pass" id="c_pass" maxlength="50" value="<?php echo $_POST['c_pass']; ?>" placeholder="write a password" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input name="c_pass2" id="c_pass2" maxlength="50" value="<?php echo $_POST['c_pass2']; ?>" type="password" class="admin-form-control form-control input-lg inp1" placeholder="Password Confirm">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="select-style">
                                <select  name="c_type" id="c_type"  class="input-lg inp1">
                                  <option <?php echo $Individual; ?> value="Individual">Individual</option>
								  <option <?php echo $Business; ?> value="Business">Business</option>
								  <option <?php echo $Admin; ?> value="Admin">Admin</option>
                               </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <input type="submit" class="add-user-btn" value="Create" name="create_user" />
                    </div>   
                </form>
              </div>
            </div>
          </div>
        </div>