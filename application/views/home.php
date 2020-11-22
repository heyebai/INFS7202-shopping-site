<html>
<head>
	<meta charset="utf-8">
	<meta name="viewpoint" content="width=decice-width, initial-scale=1">
	<title>online auction</title>
	<link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">

	<script src="https://api.mapbox.com/mapbox-gl-js/v1.9.1/mapbox-gl.js"></script>
	<link href="https://api.mapbox.com/mapbox-gl-js/v1.9.1/mapbox-gl.css" rel="stylesheet" />

	<script src="https://www.google.com/recaptcha/api.js" async defer></script>

	<script src="<?php echo base_url(); ?>assets/js/jquery-3.4.1.js"></script>

	<style>
		#messages {
			height: 45vh;
			background: white;
			padding: 5px;
			border-radius: 5px;
		}
	</style>

	<script>
		$(document).ready(function () {
			render_chat();
			setInterval(function () {
				render_chat();
			},100);

			function render_chat(query) {
				$.ajax({
					url:"<?php echo base_url(); ?>users/chat",
					method:'POST',
					data:{query:query},
					success:function (response) {
						$('#messages').html("");
						if (response == 'No data') {
							$('#messages').html("");
						} else {
							var obj = JSON.parse(response);
							if (obj.length > 0) {
								var items = [];
								$.each(obj, function (i, val) {
									items.push($('<p>').html(val.user_from+":"));
									items.push($('<p>').html(val.message));
								});
								$("#messages").append.apply($("#messages"), items);
							}
						}
					}
				});
			}

			//
			$('#send').click(function (e) {
				e.preventDefault();
				var message = $('#message').val();
				if (message != "") {
					render_chat(message);
				}
			});



		});
	</script>

</head>
<body>
<nav class="container">
	<h3 class="col-md-5"><a href="<?php echo base_url();?>">MyAuction</a></h3>
	<?php if ($this->session->userdata('logged_in')) : ?>
		<h3 class="col-md-3 col-md-offset-1">G'Day<a href="<?php echo base_url();?>users/profile"><?php echo ' '.$this->session->userdata('first_name'); ?></a></h3>
		<h3 class="col-md-1"><a href="<?php echo base_url()?>users/logout">Logout</a></h3>
		<h3 class="col-md-1"><a href="<?php echo base_url();?>users/sell">Sell</a></h3>

	<?php else : ?>
		<h3 class="col-md-2 col-md-offset-3"><a href="<?php echo base_url()?>users/register">Register/Login</a></h3>
	<?php endif; ?>
	<h3 class="col-md-1"><a href="<?php echo base_url()?>users/cart">Cart</a></h3>
</nav>
<?php if ($this->session->flashdata('registered')) {
			echo '<p class="alert alert-success">';
			echo $this->session->flashdata('registered');
			echo '</p>';
      }

      if ($this->session->flashdata('login_success')) {
		  echo '<p class="alert alert-success">';
		  echo $this->session->flashdata('login_success');
		  echo '</p>';
	  }

      if ($this->session->flashdata('login_failed')) {
		  echo '<p class="alert alert-danger">';
		  echo $this->session->flashdata('login_failed');
		  echo '</p>';
	  }

	if ($this->session->flashdata('logged_out')) {
		echo '<p class="alert alert-success">';
		echo $this->session->flashdata('logged_out');
		echo '</p>';
	}

	if ($this->session->flashdata('email_sent_fail')) {
		echo '<p class="alert alert-danger">';
		echo $this->session->flashdata('email_sent_fail');
		echo '</p>';
	}

	if ($this->session->flashdata('activate_failed')) {
		echo '<p class="alert alert-danger">';
		echo $this->session->flashdata('activate_failed');
		echo '</p>';
	}

	if ($this->session->flashdata('activate_success')) {
		echo '<p class="alert alert-success">';
		echo $this->session->flashdata('activate_success');
		echo '</p>';
	}
?>
<div>
	<?php $this->load->view($main_content); ?>
</div>

<footer class="container">
	<p class="col-md-2">Copyright 2020</p>
</footer>

<?php if ($this->session->userdata('logged_in')) : ?>
	<div id="chat" class="container" style="position: relative;">
		<button type="button" class="btn btn-primary btn-lg" >
			 <span class="glyphicon glyphicon-comment"></span>
		</button>
		<div class="row">
			<div class="well col-md-4 container">
				<div id="messages"></div>
				<form class="form-inline row" method="post" action="chat">
					<input type="text" name="message" id="message" class="form-control" required style="width: 80%">
					<input type="submit" value="Send" id="send" class="btn btn-primary">
				</form>
			</div>
		</div>
	</div>
<?php endif; ?>



<!--Chatbot-->
<style>

	/* Button used to open the chat form - fixed at the bottom of the page */
	.open-button {
		background-color: #555;
		color: white;
		padding: 8px 10px;
		border: none;
		cursor: pointer;
		opacity: 0.8;
		position: fixed;
		bottom: 0px;
		right: 60px;
		width: 100px;
	}

	/* The popup chat - hidden by default */
	.chat-popup {
		display: none;
		position: fixed;
		bottom: 0;
		right: 15px;
		border: 3px solid #f1f1f1;
		z-index: 9999999 !important;
	}

	/* Add styles to the form container */
	.form-container {
		max-width: 300px;
		padding: 10px;
		background-color: white;
	//min-height: 400px;
	}

	/* Full-width textarea */
	.form-container textarea {
		width: 100%;
		padding: 15px;
		margin: 5px 0 22px 0;
		border: none;
		background: #f1f1f1;
		resize: none;
		min-height: 100px;
	}

	/* When the textarea gets focus, do something */
	.form-container textarea:focus {
		background-color: #ddd;
		outline: none;
	}

	/* Set a style for the submit/send button */
	.form-container .btn {
		background-color: #4CAF50;
		color: white;
		padding: 16px 20px;
		border: none;
		cursor: pointer;
		width: 100%;
		margin-bottom:10px;
		opacity: 0.8;
	}

	/* Add a red background color to the cancel button */
	.form-container .cancel {
		background-color: red;
	}

	/* Add some hover effects to buttons */
	.form-container .btn:hover, .open-button:hover {
		opacity: 1;
	}

	.chatmsg{
		width: 100%;
		padding: 15px;
		margin: 5px 0 22px 0;
		border: none;
		background: #f1f1f1;
		resize: none;
		min-height: 100px;
	}

	.chatlabel{
		width: 100%;
		padding: 15px;
		margin: 5px 0 22px 0;
		border: none;
		background: #2ED046;
		resize: none;
	}
</style>

<button class="open-button" onclick="openForm()"><i class="fa fa-comments-o fa-5" aria-hidden="true"></i> Chat</button>

<div class="chat-popup" id="myForm" style="z-index: 99999 !important;background:#fff;">

	<h3 class="chatlabel">Chat</h3>
	<div id="chatmsg" class="chatmsg"  style="z-index: 99999 !important;"></div>
	<form action="javascript:void();" class="form-container"  style="z-index: 99999 !important;">

		<label for="msg"><b>Message</b></label>
		<textarea placeholder="Type message.." name="msg" id="msg" required></textarea>

		<button type="submit" class="btn">Send</button>
		<button type="button" class="btn cancel" onclick="closeForm()">Close</button>
	</form>

</div>

<script>
	function openForm() {
		document.getElementById("myForm").style.display = "block";
	}

	function closeForm() {
		document.getElementById("myForm").style.display = "none";
	}

	$(document).ready(function() {

		$(".btn").on('click',function(){
			if($("#msg").val()=="")
			{
				return;
			}
			$("#chatmsg").append("You:"+$("#msg").val()+"<br>");
			sendReceive($("#msg").val());
			$("#msg").val("");
		});
	});

	function sendReceive(msg)
	{
		$.post( "<?php echo site_url('chatbot/get_chat_data'); ?>", { msg: msg })
			.done(function( data ) {
				var len = $("#chatmsg").html().length;
				if(len>400)
				{
					$("#chatmsg").html( $("#chatmsg").html().substring(len-200, len-1));
				}
				$("#chatmsg").append(data+"<br>");
			}).fail(function( data ) {
			alert( "Data Loaded Fail");
		});
	}
</script>
<!--Chatbot-->


<script src="<?php echo base_url(); ?>assets/js/jquery-3.4.1.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
</body>
</html>


