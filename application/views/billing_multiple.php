<style>
   input[type="date"]:not(.has-value):before{
   color: #ccc;
   content: attr(placeholder);
   margin-right: 10px;
   }
   input[type="date"]{
   display: block;
   width: 100%;
   height: 34px;
   padding: 6px 12px;
   font-size: 14px;
   line-height: 1.42857143;
   color: #000;
   background-image: none;
   border: 1px solid #ccc;
   border-radius: 4px;
   }
</style>
<div class="container-fluid" id="bg-color"><br /></div>
<div class="container-fluid">
   <div class="row">
      <div class="col-sm-12">
         <div class="panel panel-default">
            <div class="panel-heading">
               <h4><?php echo ucwords($username).', ';?><small><?php echo  date('d F, Y');?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Product/logout');?>">Log Out</a></span></h4>
            </div>
            <div class="panel-body">
               <p data-placement="top" data-toggle="tooltip">
                  <a class="btn btn-primary btn-sm" href="<?php echo base_url('/index.php/Making/');?>">Go back to Making list</a>
               </p>
               <hr />
               <?php
                  echo form_open('Making/add_new', 'class="form-horizontal" id="add_product_form"');
                  ?>
               <!-- <p>
                  <span class="btn btn-primary btn-sm" onclick="add_new_master()">Add New Master</span>
               </p>
               <br> -->
               

               
               <div class="form-group">
                 
                  <div class="col-sm-12"> <?php echo form_error('master_name', '<p class="text-danger">', '</p>'); ?></div>
               </div>
               <div class="form-group" id="table_without_tax">
               <table class="table table-bordered">
                     <thead>
                        <tr>
                           <th>Material Name</th>
                           <th>Meters</th>
                           <th>Per Meter Price</th>
                           <th>Total Price</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr class="row_one">
                           <td class="">
                             
                           </td>
                           <td>
                              <input type="text" class="form-control qnty" id="stock_q" name="stock_q[]" placeholder="Meters"  value="<?php echo set_value('price'); ?>">
                           </td>
                           <td>
                              <input type="text" class="form-control rate" id="price" name="p_price[]" placeholder="Price"  value="<?php echo set_value('price'); ?>">
                           </td>
                           <td>
                              <input type="text" class="form-control amount" id="price_total" name="price_total[]" placeholder="Total Amount" value="" readonly="readonly">
                           </td>
                           <td>
                              <button type="button" name="add_more" id="add_more" class="add_more btn btn-success btn-sm" fdprocessedid="1s22ut"><b>+</b></button>
                              &nbsp;<button type="button" name="remove" id="remove" class="btn btn-warning btn-sm remove" fdprocessedid="vik1a"><b>X</b></button>
                           </td>
                        </tr>
                          <tr class="row_two">
                               <td>
                                 <div class="col-sm-12"> <?php echo form_error('material_name[]', '<p class="text-danger">', '</p>'); ?></div>

                               </td>
                               <td>
                                 <div class="col-sm-12"> <?php echo form_error('stock_q[]', '<p class="text-danger">', '</p>'); ?></div>

                               </td>
                               <td>
                                 <div class="col-sm-12"> <?php echo form_error('p_price[]', '<p class="text-danger">', '</p>'); ?></div>
                               </td>
                          </tr>
                     </tbody>
                  </table>
               </div>
               <div class="form-group">
                  <div class="col-sm-5">
                     <?php echo form_submit('add_making','Add Material','class="btn btn-success"'); ?>
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
<div class="modal fade" id="add_master" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <form id="add_owner_form" method="post" action="<?php echo site_url('/Customer/add_mowner');?>">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
               <h4 class="modal-title custom_align" id="Heading">Add Master</h4>
            </div>
            <div class="modal-body">
               <div class="col-sm-12">
                  <div class="form-group">
                     <label class="control-label col-sm-3">Master Name</label>
                     <div class="col-sm-9" id="design_holder">
                        <input type="text" name="master_name_inside" id="master_name_inside" class="form-control" value="">
                        <div class="col-sm-12"> <?php echo form_error('master_name_inside', '<p class="text-danger">', '</p>'); ?></div>

                        <input type="hidden" name="id" value="">
                     </div>
                  </div>
               </div>
               <p class="statusMsgDel text-center"></p>
            </div>
            <div class="modal-footer " style="margin-top: 50px;">
               <div class="col-sm-12">
                  <button type="submit" class="btn btn-success" id="insert_update" ><span class="glyphicon glyphicon-ok-sign"></span>Save</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>Close</button>
               </div>
            </div>
         </form>
      </div>
      <!-- /.modal-content -->
   </div>
</div>

<script src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
<script>
   $(function() {

      $("form#add_product_form").validate({
         rules: {

            master_name: "required",
            'stock_q[]': {
                     required: true,
                     // Add more rules for quantity if needed
                  },
            'material_name[]': {
                     required: true,
                     // Add more rules for quantity if needed
                  },
         },
         // Specify validation error messages
         messages: {
            master_name: "Please Select Master",
            stock_q: "Please enter a quantity"
         },
      });
   });
</script>
