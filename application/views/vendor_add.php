<div class="container-fluid" id="bg-color"><br /></div>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4><?php echo ucwords($username).', ';?><small><?php echo  date('d F, Y');?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Vendor/logout');?>">Log Out</a></span></h4>
        </div>
        
        <div class="panel-body">
          <p data-placement="top" data-toggle="tooltip">
            <a class="btn btn-primary btn-sm" href="<?php echo base_url('/index.php/Vendor/');?>">Go back to Vendor list</a>
          </p> <hr />     
          <?php            
            echo form_open('Vendor/add_new', 'class="form-horizontal" id="add_vendor_form"');
          ?>      
            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" name="vendor_name" placeholder="Vendor Name" value="<?php echo set_value('vendor_name'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('vendor_name', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" name="contact_name" placeholder="Contact Person" value="<?php echo set_value('contact_name'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('contact_name', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" name="phone1" placeholder="Phone 1" value="<?php echo set_value('phone1'); ?>">        
              </div>
              <div class="col-sm-6"> <?php echo form_error('phone1', '<p class="text-danger">', '</p>'); ?></div>
            </div> 

            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" name="phone2" placeholder="Phone 2" value="<?php echo set_value('phone2'); ?>">        
              </div>
              <div class="col-sm-6"> <?php echo form_error('phone2', '<p class="text-danger">', '</p>'); ?></div>
            </div> 

            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" name="email1" placeholder="Email 1" value="<?php echo set_value('email1'); ?>">        
              </div>
              <div class="col-sm-6"> <?php echo form_error('email1', '<p class="text-danger">', '</p>'); ?></div>
            </div>        

            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" name="email2" placeholder="Email 2" value="<?php echo set_value('email2'); ?>">        
              </div>
              <div class="col-sm-6"> <?php echo form_error('email2', '<p class="text-danger">', '</p>'); ?></div>
            </div> 

            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" name="address" placeholder="Address" value="<?php echo set_value('address'); ?>">        
              </div>
              <div class="col-sm-6"> <?php echo form_error('address', '<p class="text-danger">', '</p>'); ?></div>
            </div> 

            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" name="area" placeholder="Area" value="<?php echo set_value('area'); ?>">        
              </div>
              <div class="col-sm-6"> <?php echo form_error('area', '<p class="text-danger">', '</p>'); ?></div>
            </div> 

            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" name="city" placeholder="City" value="<?php echo set_value('city'); ?>">        
              </div>
              <div class="col-sm-6"> <?php echo form_error('city', '<p class="text-danger">', '</p>'); ?></div>
            </div>   
            
            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" name="gst" placeholder="GST" value="<?php echo set_value('gst'); ?>">        
              </div>
              <div class="col-sm-6"> <?php echo form_error('gst', '<p class="text-danger">', '</p>'); ?></div>
            </div> 

            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" name="pan" placeholder="PAN" value="<?php echo set_value('pan'); ?>">        
              </div>
              <div class="col-sm-6"> <?php echo form_error('pan', '<p class="text-danger">', '</p>'); ?></div>
            </div> 
            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" name="bank_name" placeholder="Bank Name" value="<?php echo set_value('bank_name'); ?>">        
              </div>
              <div class="col-sm-6"> <?php echo form_error('bank_name', '<p class="text-danger">', '</p>'); ?></div>
            </div> 

            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" name="branch" placeholder="Bank Branch" value="<?php echo set_value('branch'); ?>">        
              </div>
              <div class="col-sm-6"> <?php echo form_error('branch', '<p class="text-danger">', '</p>'); ?></div>
            </div> 

            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" name="account" placeholder="Account number" value="<?php echo set_value('account'); ?>">        
              </div>
              <div class="col-sm-6"> <?php echo form_error('account', '<p class="text-danger">', '</p>'); ?></div>
            </div> 

            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" name="rtgs" placeholder="RTGS/IFSC" value="<?php echo set_value('rtgs'); ?>">        
              </div>
              <div class="col-sm-6"> <?php echo form_error('rtgs', '<p class="text-danger">', '</p>'); ?></div>
            </div>                                                                                                                                                 
            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" name="dbt_bal" placeholder="Debit Balance" value="<?php echo set_value('dbt_bal'); ?>" maxlength="10">        
              </div>
              <div class="col-sm-6"> <?php echo form_error('dbt_bal', '<p class="text-danger">', '</p>'); ?></div>
            </div>

          <div class="form-group">
            <div class="col-sm-5">              
              <?php echo form_submit('add_vendor','Add Vendor','class="btn btn-success"'); ?>
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
<div class="container-fluid footer">
  <div class="row">
    <div class="col-sm-1">&nbsp;</div>
    <div class="col-sm-5">Made with <span style="color: #e25555;"><i class="glyphicon glyphicon-heart"></i></span> By Shareef Ansari</div>      
    <div class="col-sm-5">
      <p class="text-right"><i class="glyphicon glyphicon-envelope"></i> ashareefeng@gmail.com &nbsp;&nbsp;&nbsp;<i class="glyphicon glyphicon-earphone"></i>  90295 79146</p>
    </div>      
  </div>
</div>
<!--END footer section-->

</div><!--close main div-->




