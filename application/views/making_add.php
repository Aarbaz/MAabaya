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
               <?php
               $maker_no = '';

               if(!empty($makList->maker_no))
               {
                   $db_invoice = $makList->maker_no;
                   $num_part = substr($db_invoice, 3);
                   $add_one = intval($num_part)+1;

                   if(strlen($add_one) < 3)
                   {
                       $ch_no = sprintf("%03u", $add_one);
                       $maker_no = 'MAK'.$ch_no;
                   }
                   else
                   {
                       $maker_no = 'MAK'.$add_one;
                   }
               }
               else
               {
                   $maker_no = 'MAK001';
               }

               ?>
               <input type="hidden" name="maker_no" value="<?php echo $maker_no; ?>">

               <!-- <div class="form-group">
                  <div class="col-sm-5">
                     <label>Purchaser Name</label>
                     <select name="master_id" id="master_id" class="form-control">
                        <option value="" selected="selected">--select master--</option>
                          <?php
                              foreach ($PurchaserList->result() as $row){
                                  echo '<option value="'.$row->id.'" '.set_select('ownerName',$row->name).'>'.$row->name.'</option>';
                              } ?>

                     </select>
                  </div>
                  <div class="col-sm-6"> <?php echo form_error('master_name', '<p class="text-danger">', '</p>'); ?></div>
               </div> -->
               <div class="form-group">
                  <div class="col-sm-5">
                     <label>Master Name</label>
                     <select name="master_name" id="master_name" class="form-control">
                        <option value="" selected="selected">--select master--</option>
                        <?php
                           foreach ($custList->result() as $row){
                               echo '<option value="'.$row->id.'" '.set_select('ownerName',$row->name).'>'.$row->name.'</option>';
                           } ?>
                     </select>
                  </div>
                  <div class="col-sm-12"> <?php echo form_error('master_name', '<p class="text-danger">', '</p>'); ?></div>
               </div>
               <div class="form-group" id="table_without_tax">
                  <table class="table table-bordered">
                     <thead>
                        <tr>
                           <th>Material Name</th>
                           <th>Available Stock</th>
                           <th>Quantity</th>
                        </tr>
                     </thead>
                     <tbody id="rows-list">
                        <tr>
                           <td class="">
                              <select name="material_name[]" id="material_name" class="form-control check_stock ">
                                 <option value="" selected="selected">--select material--</option>
                                 <?php foreach ($matList->result() as $row){
                                    echo '<option  value="'.$row->id.'" '.set_select('materialName',$row->material_name).'>'.$row->material_name.'</option>';
                                    } ?>
                              </select>
                           </td>
                           <td>
                              <input type="text" class="form-control stock_in" id="stock_in" name="stock_in[]" placeholder="Stock/Quantity"  value="" readonly>
                           </td>
                           <td>
                              <input type="text" class="form-control" id="stock_q" name="stock_q[]" placeholder="Quantity" value="<?php echo set_value('price'); ?>">
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

                             </td>
                             <td>
                               <div class="col-sm-12"> <?php echo form_error('stock_q[]', '<p class="text-danger">', '</p>'); ?></div>

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
<script type="text/javascript">
   function add_new_master(){
       $('#add_master').modal('show');
   }

      $(document).ready(function(){
        var list = $("#rows-list");
        $(list).on('change', ".check_stock", function () {
            var row = $(this).closest('tr');
            var material_id = $(this).val();
            var baseURL= "<?php echo base_url();?>";
            $.ajax({
                type: 'post',
                url: '<?=base_url()?>index.php/Making/quantityById',
                data: {material_id: material_id},
            }).then(function (res) {
              console.log(res);
              var res = $.parseJSON(res);
                row.find(".stock_in").val(res.quantity);
            }, function () {
                alert("Sorry cannot get the product details!");
            });
        });

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
