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
                         <input type="hidden" name="purchaser_no" value="<?php echo $pur->purchaser_no ?>">

               <div class="form-group">
                  <div class="col-sm-5">
                     <label>Owner Name</label>
                     <select name="owner_name" id="owner_name" class="form-control">
                        <option value="" selected="selected">--select owner--</option>
                        <?php
                           foreach ($custList->result() as $row){?>
                        <option value="<?php echo $row->id ?>" <?php echo ($pur->purchaser_owner_id == $row->id) ? 'selected' : '' ?>><?php echo $row->name ?></option>
                        <?php
                           } ?>
                     </select>
                  </div>
                  <div class="col-sm-4">

            </div>
            <div class="col-sm-2">
              <label>Bill Date</label>
              <input type="date" id="bill_date" name="bill_date" value="<?php echo $pur->create_date ?>" />
                  
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
                              <input type="hidden" class="form-control" id="stock_qhidden" name="stock_qhidden[]" value="<?php echo $stk[$i]; ?>">
                                 <input type="text" class="form-control qnty" id="stock_q" name="stock_q[]" placeholder="Quantity" value="<?php echo $stk[$i];?>">
                              <div class="col-sm-6"> <?php echo form_error('stock_q', '<p class="text-danger">', '</p>'); ?></div>
                           </td>
                           <td>
                              <input type="hidden" class="form-control" id="p_price_hidden" name="p_price_hidden[]"  value="<?php echo $prd[$i];?>">
                              <input type="text" class="form-control rate" id="p_price" name="p_price[]" placeholder="Price" value="<?php echo $prd[$i];?>">
                           </td>
                           <td>
                              <input type="hidden" class="form-control " id="price_total_hidden" name="price_total_hidden[]"  value="<?php echo $amt[$i];?>" readonly="readonly">
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
                       <?php
                       $purchaser_no = $pur->purchaser_no;
                     //   print_r($purchaser_no);
                       $get_ledger_invoice = $this->Balance_model->get_bal_user_bill($purchaser_no);
                     //   print_r($get_ledger_invoice);

                       ?>
                  <div class="form-group">
                   <div class="col-sm-2 col-sm-offset-6">
                       <b>TOTAL AMOUNT</b>
                   </div>
                   <div class="col-sm-3">
                       <input type="text" name="total_amount" id="total_amount" readonly="readonly" class="total form-control" style="display: inline; width: 50%" value="<?php echo $get_ledger_invoice->bill_amount; ?>" size="3">
                   </div>
               </div>
               <div class="form-group">
                   <div class="col-sm-2 col-sm-offset-6">
                       <b>ROUND OFF TOTAL</b>
                   </div>
                   <div class="col-sm-3">
                       <input type="text" name="total_round" id="total_round" readonly="readonly" class="form-control" style="display: inline; width: 50%" value="<?php echo round($get_ledger_invoice->bill_amount, 2);  ?>" size="3">
                   </div>
               </div>
               <div class="form-group ">
                   <div class="col-sm-2 col-sm-offset-6">
                       <b>PAID AMOUNT</b>
                   </div>
                   <div class="col-sm-3">
                       <input type="text" name="paid_amount" class="form-control only_num paid_amount" id="paid_amount" style="display: inline; width: 50%" value="<?php echo $get_ledger_invoice->paid_amount; ?>" size="3">
                   </div>
               </div>
               <div class="form-group ">
                   <div class="col-sm-2 col-sm-offset-6">
                       <b>BALANCE AMOUNT</b>
                   </div>
                   <div class="col-sm-3">
                       <input type="text" name="balance_amount" class="form-control only_num balance_amount" id="balance_amount" style="display: inline; width: 50%" value="<?php echo $get_ledger_invoice->last_amount; ?>" size="3">
                   </div>
               </div>
               <div class="form-group">
                   <div class="col-sm-10 col-sm-offset-1">
                       <b>AMOUNT IN WORDS:</b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="total_word" id="total_word" class="form-control" style="display: inline; width: 50%" value="" size="3">
                   </div>
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
<script src="<?php echo base_url('assets/js/to_words.js'); ?>"></script>

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
     else{
       $tr.remove();
     }
   });

   $('.qnty, .rate').on('change', function(){
      // debugger;
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
      //  $('#stock_q, #p_price').on('change', function(){
      //    var qnty = $('#stock_q').val();
      //    var rate = $('#p_price').val();
      //    var the_amount = (qnty*rate).toFixed(2);
      //    $('#price_total').val(the_amount);
      //  });


   $('#total_amount').on('focus', function(){
   // debugger;
//   amountWord();
    total = 0;
            amt= 0;

   $('.amount').each(function(){
         console.log($(this).val());

       if( $(this).val() !== '' )
       {
            amt = $(this).val();
           total += parseFloat(amt);
       }
   });
   // console.log(total);

  
   var total_with_tax = parseFloat(total) + 0 + 0 + 0 ;
   total_with_tax      = total_with_tax.toFixed(2);
   // $(this).val(total_with_tax);
   // console.log(total_with_tax);

   var paid_amt = $('#paid_amount').val();
    var the_amounts = (total_with_tax-paid_amt).toFixed(2);
   $('#balance_amount').val(the_amounts);
   // $('#balance_amount').val('');
   //total round amount
   $('#total_amount').val(total_with_tax);
   $('#total_round').val(Math.round(total));
   //total in words
   var round_amount = total;
   // console.log(round_amount);

   if( round_amount!= null)
   {
       var total_words = NumToWord(round_amount);
       $("#total_word").val(total_words);
   }
   });
   amountWord();
      function amountWord(){
           total = 0;
           var amt;
   $('.amount').each(function(){
       if( $(this).val() !== '' )
       {
           amt = $(this).val();

           total += parseFloat(amt);
       }
   });
   var total_with_tax = parseFloat(total) + 0 + 0 + 0 ;
   total_with_tax      = total_with_tax.toFixed(2);
      // $('#total_amount').val(total_with_tax);

   //total round amount
   // $('#total_round').val(Math.round(total));
   //total in words
   var round_amount = total;
      if( round_amount!= null)
      {
         var total_words = NumToWord(round_amount);
         $("#total_word").val(total_words);
      }
      }


      $('.balance_amount').on('focus', function(){
       var total_amount = $('#total_amount').val();
       var paid_amount = $('#paid_amount').val();
       var the_amount = (total_amount-paid_amount).toFixed(2);
       console.log(the_amount);
       $(this).val(the_amount);
   });

     });
</script>
