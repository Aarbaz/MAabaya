<style type="text/css">

  .navbar {
    min-height: 80px;
  }

  .navbar-brand {
    padding: 0 15px;
    height: 80px;
    line-height: 80px;
  }

  .navbar-toggle {
    /* (80px - button height 34px) / 2 = 23px */
    margin-top: 23px;
    padding: 9px 10px !important;
  }


  .navbar-nav li {
  	padding: 5px;
  }

  .form-group label.error {
    color: #FB3A3A;
    display: inline-block;
    margin: 0px 0 0px 0px;
    padding: 0;
    text-align: left;
  }

  .inv .form-control {width: 90%; display: inline;}

</style>

</head>
<body>
<div id="mainDiv">
<nav class="navbar navbar-default">
	<div class="container">
    	<div class="navbar-header">
      		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
            </button>
      		<a class="navbar-brand" href="<?php echo base_url('/index.php/Dashboard');?>">
      			<!-- <img src="<?php echo base_url('assets/images/admin-logo.jpg');?>"></a> -->
    	</div>

   		<div id="navbar" class="navbar-collapse collapse navbar-right">
			<ul class="nav navbar-nav">
        <!-- <li>
          <a class="btn btn-default" role="button" href="<?php echo base_url('/index.php/Vendor');?>">Vendor</a>
        </li> -->
        <!-- <li>
          <a class="btn btn-default" role="button" href="<?php echo base_url('/index.php/Material');?>">Material</a>
				</li> -->
        <li>
					<a  class="btn btn-default" role="button"  href="<?php echo base_url('/index.php/Dashboard');?>">Dashboard</a>
				</li>
				<li>


        <li>
					<a  class="btn btn-default" role="button"  href="<?php echo base_url('/index.php/Purchaser');?>">Purchaser</a>
				</li>
        <li>
					<a  class="btn btn-default" role="button"  href="<?php echo base_url('/index.php/Making');?>">Making</a>
        </li>

        <li>
         <a class="btn btn-default" role="button"  href="<?php echo base_url('/index.php/Pices');?>">Pices</a>
       </li>

				<li>
					<a class="btn btn-default" role="button"  href="<?php echo base_url('/index.php/Invoice');?>">Sell</a>
				</li>

        <li>
          <a class="btn btn-default" role="button"  href="<?php echo base_url('/index.php/Stock');?>">Inventory</a>
        </li>
        <li>
					<a class="btn btn-default" role="button"  href="<?php echo base_url('/index.php/Customer');?>">Customer</a>
				</li>
        <li>
         <a class="btn btn-default" role="button" href="<?php echo base_url('/index.php/Material');?>">Material</a>
       </li>

     <li>
        <a  class="btn btn-default" role="button"  href="<?php echo base_url('/index.php/Design');?>">Designs</a>
      </li>
        <li>
          <!-- <a class="btn btn-default" role="button"  href="<?php echo base_url('/index.php/Balance');?>">Balance</a> -->
        </li>
			</ul>
    	</div>
    <!--/.nav-collapse -->
  </div>
  	<!--/.container-fluid -->
</nav>
