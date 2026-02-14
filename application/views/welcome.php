<style type="text/css">
	body {
		/* background-image:url("<?php echo base_url('assets/images/hijab1.jpg');?>"); */
		background-position: center;
	}
	.center-vertical {
		margin-top: 10%;
		display: inline-block;
	    vertical-align: middle;
	    float: none;
	}
</style>
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 center-vertical">
    			<div class="panel panel-default">
				    <div class="panel-heading">
				    	<h4>Welcome to M.A Abaya</h4>
				    </div>
				    <div class="panel-body">
				    <div class="col-sm-offset-2 col-sm-8">
				   		<?php echo form_open('Welcome/login_check', 'class="form-horizontal" id="login_form"'); ?>
				   			<?php

				   			if (isset($login_err))
				   			{
				   				echo '<p class="alert alert-danger">'.$login_err.'</p>';
							}
							if (!empty($_GET['status']))
				   			{
				   				echo '<div class="alert alert-success alert-block successMsg"> ';
                                echo '<span>You have been successfully logged out!</span>';
                                echo '</div>';
							}
							?>

	                    <div class="form-group">
	                        <label for="emailid" class="col-sm-2 control-label">Email</label>
	                        <div class="col-sm-8">
		                        <?php echo form_error('emailid', '<p class="text-danger">', '</p>'); ?>
		                        <input type="text" class="form-control" id="emailid" name="emailid" placeholder="Email" value="<?php echo set_value('emailid'); ?>">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label for="password" class="col-sm-2 control-label">Password</label>
	                        <div class="col-sm-8">
	                         	<p class="text-danger"><?php echo form_error('password'); ?></p>
	                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
	                        </div>
	                    </div>
	                    <div class="form-group last">
                  			<div class="col-sm-offset-2 col-sm-8">
                  				<?php echo form_submit('login','Log In','class="btn btn-primary"'); ?>
                  			</div>
                		</div>
                    	<?php echo form_close(); ?>
				    </div>
				    </div>
				    <div class="panel-footer">
				    	<p class="text-right">For M.A Abaya  </p>
				    </div>
    			</div>
			</div>
		</div>
	</div>

<!-- footer call here -->
<script type="text/javascript">

$(document).ready(function(){
    setTimeout(function(){
        $('.successMsg').fadeIn().fadeOut().fadeIn().fadeOut('slow');
    }, 3000);
});
</script>

</body>
</html>
