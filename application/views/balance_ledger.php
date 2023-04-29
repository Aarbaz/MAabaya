<div class="container-fluid" id="bg-color"><br /></div>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4><?php echo ucwords($username).', ';?><small><?php echo  date('d F, Y');?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Balance/logout');?>">Log Out</a></span></h4>
        </div>

        <div class="panel-body">
          <p data-placement="top" data-toggle="tooltip">
            <a class="btn btn-primary btn-sm" href="<?php echo base_url('/index.php/Balance/');?>">Go back to Balance list</a>
          </p><hr />
          <?php
            echo form_open('Balance/ledger', 'class="form-horizontal" id="add_material_form"');
          ?>
            <!-- <div class="form-group">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Product Name</th>
                    <th>HSN</th>
                    <th>Batch No</th>
                    <th>QNTY @kg</th>
                    <th>RATE</th>
                  </tr>
                </thead>

                <tbody>
                  <tr class="row_one">
                    <td>
                      <select name="mat_name[]" class="form-control">
                        <option value="">--select--</option>
                        
                      </select>
                    </td>
                    <td><input type="text" name="hsn[]" class="form-control" value=""></td>
                    <td><input type="text" name="batch[]" class="rate form-control" value=""></td>
                    <td><input type="text" name="qnty[]" class="form-control"></td>
                    <td><input type="text" name="rate[]" style="width: 40%; display: inline;" class="form-control">&nbsp;
                      <button type="button" name="add_more" id="add_more" class="btn btn-success btn-sm add_more"><b>+</b></button>
                      &nbsp;<button type="button" name="remove" id="remove" class="btn btn-warning btn-sm remove"><b>X</b></button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div> -->
            <div class="form-group">
              <div class="col-sm-2">Customer</div>
              <div class="col-sm-4">
                <select name="vendorName" id="vendorName" style="width: 60%; display: inline;" class="form-control">
                  <option value="">-- select Customer --</option>
                  <?php foreach ($custList->result() as $row){
                    echo '<option value="'.$row->id.'" '.set_select('vendorName',$row->id).'>'.$row->name.'</option>';
                  } ?>
                </select>
              </div>
              <div class="col-sm-6"> <?php echo form_error('vendorName', '<p class="text-danger">', '</p>'); ?></div>
            </div>
            <div class="form-group">
              <div class="col-sm-2">Invoice No</div>
              <div class="col-sm-4">
                <!-- <input type="text" style="width: 60%; display: inline;" class="form-control" id="invoice" name="invoice"  value="<?php echo set_value('invoice'); ?>"> -->
                <select name="invoice" id="invoice" style="width: 60%; display: inline;" class="form-control invoice">
                  <!-- <option value="">-- select bill --</option> -->
                  
                </select>
                <input type="hidden" class="invoice_hidden"name="invoice_hidden">

              </div>
              <div class="col-sm-6"> <?php echo form_error('invoice', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group hide">
              <div class="col-sm-2">Challan No</div>
              <div class="col-sm-4">
                <input type="text" style="width: 60%; display: inline;" class="form-control" id="challan" name="challan" value="<?php echo set_value('challan'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('challan', '<p class="text-danger">', '</p>'); ?></div>
            </div>

           

            <div class="form-group ">
              <div class="col-sm-2">Last Amount</div>
              <div class="col-sm-4">
                <input type="text" readonly="readonly" style="width: 60%; display: inline;" class="form-control" id="last_bal" name="last_bal">
              </div>
              <div class="col-sm-6"></div>
            </div>

            <div class="form-group hide">
              <div class="col-sm-2">Bill Amount</div>
              <div class="col-sm-4">
                <input type="text" style="width: 60%; display: inline;" class="form-control" id="bill_amount" name="bill_amount" value="<?php echo set_value('bill_amount'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('bill_amount', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group">
              <div class="col-sm-2">Paid Amount</div>
              <div class="col-sm-4">
                <input type="text" style="width: 60%; display: inline;" class="form-control" id="paid" name="paid" value="<?php echo set_value('paid'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('paid', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group">
              <div class="col-sm-2">New Balance</div>
              <div class="col-sm-4">
                <input type="text" style="width: 60%; display: inline;" class="form-control" id="new_bal" name="new_bal" readonly="readonly" value="<?php echo set_value('new_bal'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('new_bal', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group">
              <div class="col-sm-2">Payment Mode</div>
              <div class="col-sm-4">
                <div>
                  <label class="radio-inline">
                    <input type="radio" name="mode" value="Cash" id="mode1" <?php echo  set_radio('mode', 'Cash'); ?>>&nbsp;Cash
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="mode" value="Cheque" id="mode2" <?php echo  set_radio('mode', 'Cheque'); ?>>&nbsp;Cheque
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="mode" value="IMPS" id="mode3" <?php echo  set_radio('mode', 'IMPS'); ?>>&nbsp;IMPS
                  </label>
                </div>
              </div>
              <div class="col-sm-6"> <?php echo form_error('mode', '<p class="text-danger">', '</p>'); ?></div>
            </div>
            <div class="form-group" id="chk_row">
              <div class="col-sm-2">Cheque No</div>
              <div class="col-sm-4">
                <input type="text" style="width: 60%; display: inline;" class="form-control" id="cheque_no" name="cheque_no" value="<?php echo set_value('cheque_no'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('cheque_no', '<p class="text-danger">', '</p>'); ?></div>
            </div>
            <div class="form-group" id="imps_row">
              <div class="col-sm-2">Transaction No</div>
              <div class="col-sm-4">
                <input type="text" style="width: 60%; display: inline;" class="form-control" id="trn_no" name="trn_no" value="<?php echo set_value('trn_no'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('trn_no', '<p class="text-danger">', '</p>'); ?></div>
            </div>



            <div class="form-group">
              <div class="col-sm-5">
                <?php echo form_submit('add_material','Add Balance','class="btn btn-success"'); ?>
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
<!-- <div class="container-fluid footer">
  <div class="row">
    <div class="col-sm-1">&nbsp;</div>
    <div class="col-sm-5">Made with <span style="color: #e25555;"><i class="glyphicon glyphicon-heart"></i></span> By Shareef Ansari</div>
    <div class="col-sm-5">
      <p class="text-right"><i class="glyphicon glyphicon-envelope"></i> ashareefeng@gmail.com &nbsp;&nbsp;&nbsp;<i class="glyphicon glyphicon-earphone"></i>  90295 79146</p>
    </div>
  </div>
</div> -->
<!--END footer section-->

</div><!--close main div-->

<script type="text/javascript">
  $(document).ready(function(){
    // echo '<option value="'.$row->id.'" '.set_select('invoice',$row->id).'>'.$row->name.'</option>';

    $(document).on('change', "#vendorName", function () {
            // var row = $(this).closest('tr');
            var vendorName = $('#vendorName').val();
            var baseURL= "<?php echo base_url();?>";
            $.ajax({
                type: 'post',
                url: '<?=base_url()?>index.php/Balance/billBycust',
                data: {vendorName: vendorName},
            }).then(function (res) {
              var res = $.parseJSON(res);
              var newOpt = '';
              var oldOpt = '<option value="">-- select bill --</option>';
                  for (let jk = 0; jk < res.length; jk++) {
                  var id = res[jk].id;
                        var bill_no = res[jk].bill_no;
                        
                   newOpt = newOpt +  '<option value="'+id+'">'+bill_no+'</option>';
                  
               }
               $(".invoice").html(oldOpt+newOpt);
              
            }, function () {
                alert("Sorry cannot get the details!");
            });
        });

        $(document).on('change', ".invoice", function () {
            // var row = $(this).closest('tr');
            // var invoice = $('#invoice').text();
            var select = document.getElementById("invoice");
            var invoice = select.options[select.selectedIndex].text;
            console.log(invoice);

            var baseURL= "<?php echo base_url();?>";
            $.ajax({
                type: 'post',
                url: '<?=base_url()?>index.php/Balance/amountByBill',
                data: {invoice: invoice},
            }).then(function (data) {
              console.log(data);
              var res = $.parseJSON(data);
              // var newamount = '';
                  for (let lm = 0; lm < res.length; lm++) {
                  // var total_bill = res[lm].total_bill;
                  // var paid_bill = res[lm].paid_bill;
                  var balance_bill = res[lm].balance_bill;
                  // newamount = newamount +  '<option value="'+id+'">'+bill_no+'</option>';
                  
               }
               $(".invoice_hidden").val(invoice);

               $("#last_bal").val(balance_bill);
              
            }, function () {
                alert("Sorry cannot get the details!");
            });
        });

    //add new rows
    $(".add_more").click(function(){
        $(".row_one:first").clone(true).find(':input').val('').end().insertAfter(".row_one:last");
    });

    setTimeout(function() {
        $('.successMsg, .err_db').fadeIn().fadeOut().fadeIn().fadeOut('slow');
    }, 3000);

    //remove selcted row
    $(document).on('click', '.remove', function(){
      var $tr = $(this).closest('tr');
      if ($tr.index() != '0') {
        $tr.remove();
      }
    });

    //show customer address on customer name change event
    var cust_list = <?php echo json_encode($custList->result()); ?>;
    $("#vendorName").on('change', function(){
      var cust_id = $("#vendorName option:selected").val();
      for (var key = 0; key < cust_list.length; key++)
      {
        if( cust_list[key].id == cust_id )
        {
          $("#last_bal").val(cust_list[key].last_amount);
        }
      }
    });

    $("input[name^='hsn']").on('click',function(){
      $(this).val('1901');
    });

    //show/hide cheque OR IMPS row
    $("input[name='mode']").change(function(){
      var mode_type = $(this).val();
      if(mode_type =='Cheque')
      {
        $('#chk_row').fadeIn('slow');
        $('#imps_row').fadeOut('slow');
      }
      else if(mode_type =='IMPS')
      {
        $('#chk_row').fadeOut('slow');
        $('#imps_row').fadeIn('slow');
      }
      else
      {
        $('#chk_row').fadeOut('slow');
        $('#imps_row').fadeOut('slow');
      }
    });

    //update balance,paid
    $(document).on('change', '#bill_amount,#paid', function(){
      var bill_amount =   parseFloat($('#bill_amount').val());
      var paid        =    parseFloat($('#paid').val());
      var last_bal    =    parseFloat($('#last_bal').val());

      if(bill_amount > 0 && paid > 0)
      {
        var new_balance  = bill_amount-paid;
      }
      else if(bill_amount > 0 && (isNaN(paid) || paid==0))
      {
        var new_balance  = bill_amount;
      }
      else if(paid > 0 && (isNaN(bill_amount) || bill_amount==0))
      {
        var new_balance  = -paid;
      }
      new_balance = last_bal+new_balance;
      $('#new_bal').val(new_balance);;
    });

  });
</script>
