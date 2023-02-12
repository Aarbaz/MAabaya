<div class="container-fluid" id="bg-color"><br /></div>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4><?php echo ucwords($username).', ';?><small><?php echo  date('d F, Y');?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Balance/logout');?>">Log Out</a></span></h4>
        </div>
        
        <div class="panel-body">
          <p><a href="<?php echo site_url('Balance/ledger');?>" class="btn btn-primary">Ledger Balance</a>
          </p>
          <!--this form contain customer box and date range to select challan/invoice for that-->
          <div class="balance-div">               
            <?php                    
              echo form_open('Balance/showBalance', 'class="form-inline" id="balance_form"');
            ?> 
            <div class="form-group">
              <label for="bal_type">Balance Type: </label>
              <select id="bal_type" name="bal_type" class="form-control">
                <option value="">--select--</option>
                <option value="challan">Challan</option>
                <option value="invoice">Invoice</option>
              </select>
            </div>

            <div class="form-group">   
              <label for="customerName">Customer: </label>
              <select name="customerName" id="customerName" class="form-control">
                <option value="">-- select customer --</option>
                <?php foreach ($custList->result() as $row){
                  echo '<option value="'.$row->id.'" '.set_select('customerName',$row->id).'>'.$row->bakery_name.', '.$row->bakery_area.'-'.$row->bakery_city.'</option>';
                } ?>
              </select>          
            </div>

            <div class="form-group">
              <span class="text-danger" id="frm_date_er"></span>
              <input type="text" class="form-control" name="frm_date" id="frm_date" placeholder="From Date" value="<?php echo set_value('frm_date'); ?>">        
            </div>

            <div class="form-group">
              <span class="text-danger" id="to_date_er"></span>
              <input type="text" class="form-control" name="to_date" id="to_date" placeholder="To Date" value="<?php echo set_value('to_date'); ?>">        
            </div>  

            <div class="form-group">
              <button type="button" id="add_bal" onclick="show_balance_form();" class="btn btn-primary btn-md">Send</button>
            </div>
            <p class="text-center">
              <span class="text-danger" id="error_box"></span>
            </p>
            <p class="text-center statusMsgDel"> </p>
            <?php echo form_close();  ?>
            <!--end this form-->
            <div><br /><br /></div>
 
          </div> 
          <div id="form_data_box" style="display: none;">
            <?php
            echo form_open('Balance/saveBalance', 'class="form-inline" id="save_balance"');
            ?>                              
            <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>&nbsp;</th>
                  <th>Sr. No</th>
                  <th id="bill_type"></th>
                  <th>Amount</th>
                  <th>Paid</th>
                  <th>Balance</th>
                  <th>Added Date</th>
                </tr>
              </thead> 

              <tbody>
              </tbody>
            </table>
            <p>
              <input type="submit" name="save_balance_btn" value="Save Balance" class="btn btn-primary btn-md">
              &nbsp;&nbsp;&nbsp;<span id="save_balance_msg"></span>
            </p>
            </form>
          </div>
          <br /><br />
          <p class="text-center balMsgDel"> </p>
          <div id="balance_data_box" style="display: none;">
            <table id="balance_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>Sr. No</th>
                  <th id="bill_type"></th>                  
                  <th>Paid</th>
                  <th>Balance</th>
                  <th>Updated Date</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>           
        </div>      
      </div>
    </div>        
  </div>
</div>
<!--footer section-->
<div class="container" style="height: 100px;">&nbsp;<br /></div>
<div class="container-fluid footer">
  <div class="row">
    <div class="col-sm-1">&nbsp;</div>
    <div class="col-sm-5">Made with <span style="color: #e25555;"><i class="glyphicon glyphicon-heart"></i></span> By Shareef Ansari</div>   
    <div class="col-sm-1">&nbsp;</div>   
    <div class="col-sm-4">
      <p><i class="glyphicon glyphicon-envelope"></i> ashareefeng@gmail.com &nbsp;&nbsp;&nbsp;<i class="glyphicon glyphicon-earphone"></i>  90295 79146</p>
    </div>      
  </div>
</div>
<!--END footer section-->

</div><!--close main div-->
<script type="text/javascript">
$(document).ready(function(){
  $("[data-toggle=tooltip]").tooltip();  

  setTimeout(function() {
    $(".show_hide").alert('close');
  }, 4000);

});

function show_balance_form()
{
  var bal_type = $('#bal_type option:selected').val();
  var customerName = $('#customerName option:selected').val();
  var isValid = true;
  $('#error_box').text('');

  if(bal_type == '')
  {
    $('#error_box').text('Please select balance type');
    isValid = false; return false;
  }
  else
  {
    $('#error_box').text(''); isValid = true;
  }
  if(customerName == '')
  {
    $('#error_box').text('Please select customer');
    isValid = false; return false;
  }
  else
  {
    $('#error_box').text(''); isValid = true;
  }

  if(isValid)
  {
    $.ajax({
      type:'POST',
      url: $("#balance_form").attr("action"),
      data: $("#balance_form").serialize(),
      beforeSend: function () {
        $('.statusMsgDel').html('<span style="color:green;">Loading please wait....</span>');
      },                
      success:function(msg){
        //console.log(msg);
        //return;
        $('.statusMsgDel').html('');
        $('#balance_data_box').hide();
        $('#balance_table tbody').empty();
        $('.balMsgDel').empty();
        var resp = JSON.parse(msg);
        if( resp.response == 'passed')
        {          
          if(resp.type=='challan')
          {
            var th = 'Challan No';
            var bil_type = 'challan';
          }
          else
          {
            var th = 'Invoice No';
            var bil_type = 'invoice';
          }

          $('#form_data_box').show();    
          $('#datatable th#bill_type').text(th);
          var trhtml = '';
          var count = 1;
          $.each(resp.data, function(i, item) {
            var paid = item.paid;
            var balance = item.balance;
            trhtml+= '<tr id="'+item.sr_no+'"><td><input type="checkbox" name="chk_update[]" value="1"></td><td>'+count+'<input type="hidden" name="row_id[]" value="'+item.sr_no+'"></td><td><span class="glyphicon glyphicon-arrow-down" id="bill_id">'+ item.bill_no +'</span><input type="hidden" name="bill_no[]" value="'+item.bill_no+'"></td><td class="total">'+ item.total+ '<input type="hidden" name="total[]" value="'+item.total+'"></td><td><input type="text" id="paid" name="paid[]" size="15" value="'+paid+'" class="form-control"><input type="hidden" name="paid_hide[]" id="paid_hide" value="'+paid+'"></td><td><input type="text" readonly="readonly" id="bal" name="bal[]" value="'+balance+'" size="15" class="form-control"><input type="hidden" name="bal_hide[]" id="bal_hide" value="'+balance+'"></td><td>'+ item.date_on+ '<input type="hidden" name="cust_id[]" value="'+item.customer_id+'"><input type="hidden" name="bil_type[]" id="bil_type" value="'+bil_type+'"></td></tr>';
            count++;
          });      
          
          $('#datatable tbody').html(trhtml);
          var table = $('#datatable').dataTable({
              "pageLength": 25,
              retrieve: true,
              paging: true,
          });            
        }

        else
        {
          $('.statusMsgDel').html('<span style="color:red;">Sorry! no record found for this customer.</span>');
          $('#form_data_box').hide(); 
          $('#datatable tbody').empty();
          $('#balance_data_box').hide();
          $('#balance_table tbody').empty();
        }              
      }
    });
  }
    
} //function end


$(document).ready(function(){
  //save balance details after updating paid box value
  $("#save_balance").submit(function(e){
    e.preventDefault();
    var chk = $('input:checkbox:checked').length;
    if(chk == 0)
    {
      alert('Please select checkbox');
      return false;
    }

    $.ajax({
      type:'POST',
      url: $("#save_balance").attr("action"),
      data: $("#save_balance").serialize(),
      beforeSend: function () {
        $('#save_balance_msg').html('<i style="color:green;">Loading please wait...</i>');
      },                
      success:function(msg){
        $('#save_balance_msg').html('');
        var resp = JSON.parse(msg);
        if(resp.updated == 'yes')
        {
          $('#save_balance_msg').html('<i style="color:green">'+resp.message+'</i>').fadeIn().fadeOut().fadeIn().fadeOut().fadeIn().fadeOut();
          show_balance_form();  //to display updated paid and balance amount
        }
        else
        {
          $('#save_balance_msg').html('<i style="color:red">'+resp.message+'</i>').fadeIn().fadeOut().fadeIn().fadeOut().fadeIn().fadeOut();
        }

      }
    });  
  });

  //add only NUMBER and .
  $(document).on("change", "#paid" , function() {
    var val = $(this).val();
    var ro = $(this).closest('tr');
    var ro_id = ro.attr('id');
    var ro_total = ro.find('.total').text();  
    if(isNaN(val) === false)
    {      
      val = parseFloat(val);
      val = val.toFixed(2);     
    }
    else
    {      
      val = '';
      ro.find('#bal').val('');
    }

    $(this).val(val);    
    ro_total = parseFloat(ro_total);  
    var bal_field = ro.find('#bal');   
    var bal_hide = ro.find('#bal_hide');
    var paid_hide = ro.find('#paid_hide');
    //if 0 amount is paid
    if(bal_hide.val()  == '')
    {      
      if(val > 0 && val <= ro_total )
      {
        var bal_amt = ro_total - val;
        bal_amt = bal_amt.toFixed(2);
        bal_field.val(bal_amt );
      }
      else
      {       
        ro.find('#paid').val('');
      }
    }
    else
    {    
      val = parseFloat(val);
      var bal_val =  bal_field.val();
      bal_val = parseFloat(bal_val);      

      if(val > 0 && val <=  bal_val)
      {        
        var bal_amt = bal_val - val;        
        bal_amt = bal_amt.toFixed(2);        
        bal_field.val(bal_amt) ;
      }
      else
      {        
        ro.find('#paid').val(ro.find('#paid_hide').val());
      }
    }

  });

  $(document).on("click", "#bill_id" , function() {
    var val = $(this).text();
    var ro = $(this).closest('tr');
    var bill_type = ro.find('#bil_type').val();
    $.ajax({
      type:'POST',
      url: "<?php echo  base_url('/index.php/Balance/balanceList');?>",
      data: {billno:val, bill_type:  bill_type},
      beforeSend: function () {
        $('.balMsgDel').html('<span style="color:green;">Loading please wait...</span>');
      },
      success:function(msg){   
        $('.balMsgDel').html('');           
        var resp = JSON.parse(msg);
        if( resp.response == 'passed')
        {
          if(resp.type=='challan')
          {
            var th = 'Challan No';
          }
          else
          {
            var th = 'Invoice No';
          }
          
          $('#balance_data_box').show();
          $('#balance_table th#bill_type').text(th);       
           
          var trhtml = '';
          var count = 1;
          $.each(resp.data, function(i, item) {
            trhtml+= '<tr><td>'+count+'</td><td>'+ item.bill_no +'</td><td>'+item.paid_bill+'</td><td>'+item.balance_bill+'</td><td>'+ item.updated_on+ '</td></tr>';
            count++;
          });      
          
          $('#balance_table tbody').html(trhtml);
        }
        else
        {
          $('.balMsgDel').html('<span style="color:red;">Sorry! no record found.</span>');
          $('#balance_data_box').hide();
          $('#balance_table tbody').empty();
        }              
      }
    });

  }); 

});

</script>


