<div class="container-fluid" id="bg-color"><br /></div>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4><?php echo ucwords($username).', ';?><small><?php echo  date('d F, Y');?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Material/logout');?>">Log Out</a></span></h4>
        </div>
        
        <div class="panel-body">
          <p data-placement="top" data-toggle="tooltip">
            <a class="btn btn-info btn-sm" href="<?php echo base_url('/index.php/Material/');?>">Go back to Material list</a>
          </p>            
          <?php            
            $url = 'Material/edit/'.$mat->id;                   
            echo form_open($url, 'class="form-horizontal" id="add_material_form"');
          ?>      
            <div class="form-group">
              <div class="col-sm-5">                
                <input type="text" class="form-control" name="mat_name" placeholder="Material Name" value="<?php echo set_value('mat_name', $mat->material_name); ?>">
                <input type="hidden" name="mat_id" value="<?php echo $mat->id; ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('mat_name', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="hsn" name="hsn" placeholder="HSN code" value="<?php echo set_value('hsn', $mat->hsn); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('hsn', '<p class="text-danger">', '</p>'); ?></div>
            </div>            

            <div class="form-group">
              <div class="col-sm-5">                
                <input type="text" class="form-control" name="qnty" value="<?php echo set_value('qnty', $mat->quantity); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('qnty', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group">
              <div class="col-sm-5">                
                <input type="text" class="form-control" name="price" placeholder="Total Amount" value="<?php echo set_value('price', $mat->price);?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('price', '<p class="text-danger">', '</p>'); ?></div>
            </div>        

            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="invoice" name="invoice" placeholder="Invoice No" value="<?php echo set_value('invoice', $mat->invoice); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('invoice', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="challan" name="challan" placeholder="Challan No" value="<?php echo set_value('challan', $mat->challan); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('challan', '<p class="text-danger">', '</p>'); ?></div>
            </div>    

            <div class="form-group">
              <div class="col-sm-5">
                <select name="vendorName" id="vendorName" class="form-control">

                  <?php 
                  foreach ($vendors->result() as $row){
                    $selected = ($mat->vendor == $row->vendor_name) ? true: false;
                    echo '<option value="'.$row->vendor_name.'" '.set_select('vendorName',$row->vendor_name, $selected).'>'.$row->vendor_name.'</option>';
                  }
                   ?>
                </select>
              </div>
              <div class="col-sm-6"> <?php echo form_error('vendorName', '<p class="text-danger">', '</p>'); ?></div>
            </div>                                           
      
          <div class="form-group">
            <div class="col-sm-5">              
              <?php echo form_submit('edit_material','Edit Material','class="btn btn-success"'); ?>
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




