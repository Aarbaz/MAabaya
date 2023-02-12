<div class="container-fluid" id="bg-color"><br /></div>

<div class="container-fluid">
  <div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				  <h4><?php echo ucwords($username).', ';?><small><?php echo  date('d F, Y');?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Purchaser/logout');?>">Log Out</a></span></h4>
				</div>

        <div class="panel-body">
          <p data-placement="top" data-toggle="tooltip">
            <a class="btn btn-info btn-sm" href="<?php echo base_url('/index.php/Purchaser/');?>">Go back to Purchaser list</a>
          </p>
          <?php
            $url = 'Purchaser/edit/'.$cust->id;
            echo form_open($url, 'class="form-horizontal" id="add_customer_form"');
          ?>
            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="bakery_name" name="bakery_name" value="<?php echo set_value('bakery_name', $cust->bakery_name); ?>">
                <input type="hidden" name="cust_id" value="<?php echo $cust->id; ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('bakery_name', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" name="owner_name" value="<?php echo set_value('owner_name', $cust->owner_name); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('owner_name', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <!-- <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" name="gst" placeholder="GST No" value="<?php echo set_value('gst', $cust->bakery_gst);?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('gst', '<p class="text-danger">', '</p>'); ?></div>
            </div> -->

            <!-- <div class="form-group">
              <div class="col-sm-5">
                <textarea class="form-control" name="bakery_adds" placeholder="Bakery Adds"><?php echo set_value('bakery_adds',$cust->bakery_address); ?></textarea>
              </div>
              <div class="col-sm-6"> <?php echo form_error('bakery_adds', '<p class="text-danger">', '</p>'); ?></div>
            </div> -->
            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" value="<?php echo set_value('phone',$cust->owner_phone); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('phone', '<p class="text-danger">', '</p>'); ?></div>
            </div>
            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="area" name="area" placeholder="Area" value="<?php echo set_value('area', $cust->bakery_area);?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('area', '<p class="text-danger">', '</p>'); ?></div>
            </div>


            <!-- <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="city" name="city" placeholder="city" value="<?php echo set_value('city',$cust->bakery_city); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('city', '<p class="text-danger">', '</p>'); ?></div>
            </div> -->



            <!-- <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="email" name="email" placeholder="Email ID" value="<?php echo set_value('email',$cust->owner_email); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('email', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="last_amount" name="last_amount" placeholder="Last Amount" value="<?php echo set_value('last_amount',$cust->last_amount); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('last_amount', '<p class="text-danger">', '</p>'); ?></div>
            </div>                   -->

          <div class="form-group">
            <div class="col-sm-5">
              <?php echo form_submit('edit_purchaser','Edit Purchaser','class="btn btn-success"'); ?>
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
