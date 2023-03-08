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
                  <a class="btn btn-info btn-sm" href="<?php echo base_url('/index.php/Purchaser/');?>">Go back to Material list</a>
               </p>
               <?php
                  $url = 'Purchaser/edit/'.$pur->id;
                  echo form_open($url, 'class="form-horizontal" id="add_puruct_form"');
                  ?>
               <input type="hidden" name="pur_id" value="<?php echo $pur->id; ?>">
               <div class="form-group">
                  <div class="col-sm-5">
                     <label>Owner Name</label>
                     <select name="owner_name" id="owner_name" class="form-control">
                        <option value="" selected="selected">--select owner--</option>
                        <?php
                           foreach ($custList->result() as $row){?>
                        <option value="<?php echo $row->id ?>" <?php echo ($pur->owner_id == $row->id) ? 'selected' : '' ?>><?php echo $row->bakery_name ?></option>
                        <?php
                           } ?>
                     </select>
                  </div>
                  <div class="col-sm-6"> <?php echo form_error('owner_name', '<p class="text-danger">', '</p>'); ?></div>
               </div>
               <div class="form-group" id="table_without_tax">
                  <table class="table table-bordered">
                     <thead>
                        <tr>
                           <th>Material Name</th>
                           <th>Quantity</th>
                           <th>Price</th>
                           <th>Total Amount</th>
                        </tr>
                     </thead>
                     <?php
                        $mat = explode(',', $pur->material_id);
                        $stk = explode(',', $pur->stock);
                        $prd = explode(',', $pur->price);
                        $amt = explode(',', $pur->total_amount);
                        $cnt= count($mat);

                        for ($i=0; $i < $cnt; $i++) {
                          ?>
                     <tbody>
                        <tr class="row_one">
                           <td class="">
                              <select name="material_name[]" id="material_name" class="form-control">
                                 <option value="" selected="selected">--select material--</option>
                                 <?php foreach ($matList->result() as $row){
                                    ?>
                                 <option value="<?php echo $row->id ?>" <?php echo ($mat[$i] == $row->id) ? 'selected' : '' ?>><?php echo $row->material_name ?></option>
                                 <?php
                                    } ?>
                              </select>
                              <div class="col-sm-6"> <?php echo form_error('material_name', '<p class="text-danger">', '</p>'); ?></div>
                           </td>
                           <td>
                              <input type="text" class="form-control qnty" id="stock_q" name="stock_q[]" placeholder="Stock/Quantity" value="<?php echo $stk[$i];?>">
                              <div class="col-sm-6"> <?php echo form_error('stock_q', '<p class="text-danger">', '</p>'); ?></div>
                           </td>
                           <td>
                              <input type="text" class="form-control rate" id="p_price" name="p_price[]" placeholder="Price" value="<?php echo $prd[$i];?>">
                           </td>
                           <td>
                              <input type="text" class="form-control amount" id="price_total" name="price_total[]" placeholder="Total Amount" value="<?php echo $amt[$i];?>" readonly="readonly">
                           </td>
                           <td>
                              <button type="button" name="add_more" id="add_more" class="add_more btn btn-success btn-sm" fdprocessedid="1s22ut"><b>+</b></button>
                              &nbsp;<button type="button" name="remove" id="remove" class="btn btn-warning btn-sm remove" fdprocessedid="vik1a"><b>X</b></button>
                           </td>
                        </tr>
                     </tbody>
                     <?php
                        }
                        ?>
                  </table>
               </div>
               <div class="form-group">
                  <div class="col-sm-5">
                     <?php echo form_submit('edit_purchaser','Edit & Save','class="btn btn-success"'); ?>
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

   $('.qnty, .rate').on('change', function(){
       var ro  = $(this).closest('tr');
       var qnty = ro.find('.qnty').val();
       var rate = ro.find('.rate').val();

       if(qnty && rate)
       {
           var the_amount = (qnty*rate);
           ro.find('.amount').val(the_amount);
       }
   });


     $(document).ready(function(){
       $('#stock_q, #p_price').on('change', function(){
         var qnty = $('#stock_q').val();
         var rate = $('#p_price').val();
         var the_amount = (qnty*rate).toFixed(2);
         $('#price_total').val(the_amount);
       });
     });
</script>
