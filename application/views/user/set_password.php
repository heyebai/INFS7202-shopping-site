


<h1 style="text-align: center; padding: 2em">Reset password</h1>
<form method="post" action="set_password" class="container">
    <div class="form-group form-inline  row">
        <label  class="col-md-offset-4">New Password</label>
        <input type="password" name="new_password" class="form-control "  placeholder="Password"
			   required minlength="6" maxlength="20">
    </div>
    <input type="hidden" name="email" value="<?php echo $user_info->email;?>">
    <input type="submit" class="btn btn-success col-md-offset-5" value="Reset Password">
</form>
