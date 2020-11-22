<?php
if ($this->session->flashdata('wrong_answer')) {
    echo '<p class="alert alert-danger">';
    echo $this->session->flashdata('wrong_answer');
    echo '</p>';
}
?>

<h1 style="text-align: center; padding: 2em">Please answer secret question</h1>
<form method="post" action="check_answer" class="container">
    <div class="form-group ">
        <label  class=""><?php echo $user_info->question; ?>?</label>
        <input type="text" name="check_answer" class="form-control "  placeholder="Please enter your answer.">
    </div>
    <input type="hidden" name="email" value="<?php echo $user_info->email;?>">
    <input type="submit" class="btn btn-success" >
</form>