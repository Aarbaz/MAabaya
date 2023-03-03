<div class="container-fluid" id="bg-color"><br /></div>

<div class="container-fluid">
  <div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				  <h4><?php echo ucwords($username).', ';?><small><?php echo  date('d F, Y');?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Customer/logout');?>">Log Out</a></span></h4>
				</div>

        <div class="panel-body">
          <p data-placement="top" data-toggle="tooltip">
            <a class="btn btn-primary btn-sm" href="<?php echo base_url('/index.php/Purchaser/');?>">Go back to Purchaser list</a>
          </p> <hr />
          <?php
            echo form_open('Purchaser/add_new', 'class="form-horizontal" id="add_customer_form"');
          ?>
            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="bakery_name" name="bakery_name" placeholder="Company Name" value="<?php echo set_value('bakery_name'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('bakery_name', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="owner_name" name="owner_name" placeholder="Purchaser Name" value="<?php echo set_value('owner_name'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('owner_name', '<p class="text-danger">', '</p>'); ?></div>
            </div>


            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" value="<?php echo set_value('phone'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('phone', '<p class="text-danger">', '</p>'); ?></div>
            </div>
            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="area" name="area" placeholder="Address" value="<?php echo set_value('area'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('area', '<p class="text-danger">', '</p>'); ?></div>
            </div>

          

          <div class="form-group">
            <div class="col-sm-5">
              <?php echo form_submit('add_purchaser','Add Purchaser','class="btn btn-success"'); ?>
            </div>
             <div class="col-sm-6">
              <?php
                if( $this->session->flashdata('failed') )
                { echo '<p class="text-danger">'.$this->session->flashdata('failed').'</p>'; }
              ?>
             </div>
          </div>
      <?php echo form_close();  ?>
        </div>
    	</div>
    </div>
	</div>
</div>



</div><!--close main div-->
