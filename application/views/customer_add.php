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
            <a class="btn btn-primary btn-sm" href="<?php echo base_url('/index.php/Customer/');?>">Go back to Customer list</a>
          </p> <hr />
          <?php
            echo form_open('Customer/add_new', 'class="form-horizontal" id="add_customer_form"');
          ?>
            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="name" name="name" placeholder="Customer Name" value="<?php echo set_value('name'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('name', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group hide">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="owner_name" name="owner_name" placeholder="Owner Name" value="<?php echo set_value('owner_name'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('owner_name', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group hide ">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="gst" name="gst" placeholder="GST No" value="<?php echo set_value('gst'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('gst', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group">
              <div class="col-sm-5">
                <textarea class="form-control" id="address" name="address" placeholder="Shop Adds"><?php echo set_value('address'); ?></textarea>
              </div>
              <div class="col-sm-6"> <?php echo form_error('address', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group hide">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="area" name="area" placeholder="Area" value="<?php echo set_value('area'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('area', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group hide">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="city" name="city" placeholder="city" value="<?php echo set_value('city'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('city', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group hide">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" value="<?php echo set_value('phone'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('phone', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group hide">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="email" name="email" placeholder="Email ID" value="<?php echo set_value('email'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('email', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group hide">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="last_amount" name="last_amount" placeholder="Last Amount" value="<?php echo set_value('last_amount'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('last_amount', '<p class="text-danger">', '</p>'); ?></div>
            </div>

          <div class="form-group">
            <div class="col-sm-5">
              <?php echo form_submit('add_customer','Add Customer','class="btn btn-success"'); ?>
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

<!--footer section-->

<!--END footer section-->

</div><!--close main div-->
