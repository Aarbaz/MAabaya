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
               <div class="form-group">
                  <div class="col-sm-5">
                     <label>Master Name</label>
                     <input type="text" class="form-control" name="master_name" placeholder="Master Name" value="<?php echo set_value('master_name'); ?>">
                  </div>
                  <div class="col-sm-6"> <?php echo form_error('master_name', '<p class="text-danger">', '</p>'); ?></div>
               </div>
              
                  <div class="form-group" id="table_without_tax">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Material Name</th>
                                            <th>Stock/Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="row_one">

                                            <td class="">
                                              <input type="text" class="form-control" name="material_name[]" placeholder="Material Name" value="<?php echo set_value('material_name'); ?>">
                                            </td>
                                            <td>
                                              <input type="text" class="form-control" id="stock_q" name="stock_q[]" placeholder="Stock/Quantity" value="<?php echo set_value('price'); ?>">
                                            </td>
                                            <td>
                                                <button type="button" name="add_more" id="add_more" class="add_more btn btn-success btn-sm" fdprocessedid="1s22ut"><b>+</b></button>
                                                &nbsp;<button type="button" name="remove" id="remove" class="btn btn-warning btn-sm remove" fdprocessedid="vik1a"><b>X</b></button>
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
<script type="text/javascript">



   $(document).ready(function(){

     // add new row
     $(document).on('click', '.add_more', function(){
         $(this).closest('tr').clone(true).find(':input:not(".hsn")').val('').end().insertAfter($(this).closest('tr'));
     });
     //Remove table row
     $(document).on('click', '.remove', function(){
       var $tr = $(this).closest('tr');
       if ($tr.index() != '0') {
         $tr.remove();
       }
     });

$('#some_id').on('click', '.add_more', function() {
  $('.add_more').closest('#some_id').first().clone().appendTo('.results');
});

 $('#some_id').on('click', '#remove', function() {
  alert();
  $('#remove').closest('#some_id').not(':first').last().remove();
});

     $('#stock_q, #price').on('change', function(){
       var qnty = $('#stock_q').val();
       var rate = $('#price').val();
       var the_amount = (qnty*rate).toFixed(2);
       $('#price_total').val(the_amount);
     });
   });
</script>
