<?php
if ($this->session->flashdata('registered_fail')) {
	echo '<p class="alert alert-danger">';
	echo $this->session->flashdata('registered_fail');
	echo '</p>';
}

if ($this->session->flashdata('set_password_success')) {
    echo '<p class="alert alert-success">';
    echo $this->session->flashdata('set_password_success');
    echo '</p>';
}


?>
<div class="r_and_l">
	<div class="register">
		<h1>Create an account</h1>
		<form action="register" method="post">
			<div class="self">
				<input type="text" name="firstname" placeholder="First Name" class="form-control">
				<input type="text" name="lastname" placeholder="Last Name" class="form-control">
			</div> <br>

			<input type="email" name="email" placeholder="Email" class="form-control"> <br>
			<input type="test" name="question" placeholder="Secret question" class="form-control"> <br>
			<input type="test" name="answer" placeholder="Answer of the secret question" class="form-control"> <br>
			<input type="text" name="username" placeholder="Username" class="form-control"> <br>
			<input type="password" name="password" id="password" placeholder="Password" class="form-control"> <br>
			<p id="pw_strength" ></p>
			<input type="submit" name="register" value="Register" class="btn btn-primary">

		</form>
		<?php if (!empty($_POST['register'])){
			echo validation_errors('<p class="alert alert-danger">','</p>');
		}
		?>

		<script>
			var pw = document.getElementById('password');
			
			pw.onkeyup = function pw_strength() {
				if (pw.value.length < 6) {
					document.getElementById('pw_strength').innerHTML="The min length of the password is six.";
					document.getElementById('pw_strength').className="alert alert-danger";
					//document.getElementById('pw_strength').style="display: block";
				} else {
					if (pw.value.search(/[0-9]/) != -1 && pw.value.search(/[a-z]/) != -1 && pw.value.search(/[A-Z]/) != -1) {
						document.getElementById('pw_strength').innerHTML="Strong";
						document.getElementById('pw_strength').className="alert alert-success";
					} else if((pw.value.search(/[0-9]/) != -1 && pw.value.search(/[A-Z]/) != -1)
								|| (pw.value.search(/[a-z]/) != -1 && pw.value.search(/[0-9]/) != -1)
								|| (pw.value.search(/[a-z]/) != -1 && pw.value.search(/[A-Z]/) != -1)) {
						document.getElementById('pw_strength').innerHTML="Middle";
						document.getElementById('pw_strength').className="alert alert-warning";
					} else if (pw.value.search(/[0-9]/) == -1 || pw.value.search(/[a-z]/) == -1 || pw.value.search(/[A-Z]/) == -1) {
						document.getElementById('pw_strength').innerHTML="Week";
						document.getElementById('pw_strength').className="alert alert-danger";
					}
				}
			}
		</script>

	</div>

	<div class="halvingLine">
	</div>

	<div class="login">
		<h1>Sign in to MyAuction</h1>
		<form action="login" method="post" id="captcha_form">

			<input type="text" name="username" id="username_l" placeholder="Username" class="form-control" value="<?php if (isset($_COOKIE['username'])) {echo $_COOKIE['username'];}?>"> <br>
			<input type="password" name="password" id="password_l" placeholder="Password" class="form-control" value="<?php if (isset($_COOKIE['password'])) {echo $_COOKIE['password'];}?>"> <br>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="remember"> Remember me
				</label>
			</div>
			<div class="g-recaptcha" data-sitekey="6Lcta_wUAAAAAMA7aqFkmC11SKOn74wMqb33ZRtx"></div>
			<p id="captcha_error"></p>
			<input type="submit" name="login" value="Login" class="btn btn-primary">
		</form>
        <a href="<?php echo base_url();?>users/forgot">Forgot your password?</a>
		<?php if (!empty($_POST['login'])){
			echo validation_errors('<p class="alert alert-danger alert-dismissable">','</p>');
		}
		?>
	</div>

</div>

<!--<script>-->
<!--	$(document).ready(function () {-->
<!--		$("#capcha_form").on('submit', function (e) {-->
<!--			e.preventDefault();-->
<!--			-->
<!--		});-->
<!--	});-->
<!--</script>-->
