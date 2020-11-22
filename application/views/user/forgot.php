<?php
if ($this->session->flashdata('email_not_found')) {
    echo '<p class="alert alert-danger">';
    echo $this->session->flashdata('email_not_found');
    echo '</p>';
}
?>

<h1 style="text-align: center; padding: 2em">Account Recovery</h1>
<form method="post" action="check_email" class="container">
    <div class="form-group form-inline  row">
        <label for="exampleInputEmail1" class="col-md-offset-4">Email address</label>
        <input type="email" name="check_email" class="form-control " id="exampleInputEmail1" placeholder="Email">
    </div>
    <input type="submit" class="btn btn-success col-md-offset-5" value="Reset Password">
</form>