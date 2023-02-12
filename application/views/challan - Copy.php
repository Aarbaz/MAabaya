<div class="container-fluid" id="bg-color"><br /></div>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4><?php echo ucwords($username).', ';?><small><?php echo  date('d F, Y');?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Challan/logout');?>">Log Out</a></span></h4>
        </div>
        
        <div class="panel-body"> 
          <div class="challan-div">               
            <?php                    
              echo form_open('Challan/addChallan', 'class="form-horizontal" id="challan_form"');
            ?>
            <h3 class="text-center">DELIVERY CHALLAN</h3><br />
            <?php
              if( $this->session->flashdata('success') )
              {
                 echo '<div class="alert alert-success show_hide" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p class="text-center"><strong>Success!</strong> '.$this->session->flashdata('success').'</p></div>';
              } 
            ?>    
            <div class="form-group"><br />
              <div class="col-sm-8 col-sm-offset-2">
                <?php echo validation_errors('<p class="text text-danger">', '</p>'); ?>
              </div>
            </div>
     
            <div class="form-group">
              <div class="col-sm-1">&nbsp;</div>
              <div class="col-sm-2"><b> Date </b></div>
              <div class="col-sm-6"> <?php echo  date('d F, Y'); ?> </div>             
            </div>

            <div class="form-group">
              <div class="col-sm-1">&nbsp;</div>
              <div class="col-sm-2"> <b>Customer </b></div>
              <div class="col-sm-6">
                <select name="customerName" id="customerName" class="form-control">
                  <option value="">-- select customer --</option>
                  <?php foreach ($custList->result() as $row){
                    echo '<option value="'.$row->id.'" '.set_select('customerName',$row->id).'>'.$row->bakery_name.'</option>';
                  } ?>
                </select>
              </div>             
            </div>

            <div class="form-group">
              <div class="col-sm-1">&nbsp;</div>
              <div class="col-sm-2"> <b>Address</b></div>
              <div class="col-sm-6">
                <input type="text" name="cust_adds_txt" id="cust_adds_txt" class="form-control" value="<?php echo set_value('cust_adds_txt');?>" readonly="readonly">
                <input type="hidden" name="cust_adds" value="<?php echo set_value('cust_adds');?>" id="cust_adds">
                <input type="hidden" name="cust_name" value="<?php echo set_value('cust_name');?>" id="cust_name">
              </div>             
            </div>      

            <div class="form-group">
              <div class="col-sm-1">&nbsp;</div>
              <div class="col-sm-2"> <b>Challan No. </b></div>
              <div class="col-sm-6"> 
                <?php                 
                $chalan_no = '';      

                if(!empty($challan_no->challan_no))
                {
                  $db_ch = $challan_no->challan_no;
                  $num_part = substr($db_ch, 2);
                  $add_one = intval($num_part)+1;
                
                  if(strlen($add_one) < 3)
                  {
                    $ch_no = sprintf("%03u", $add_one);
                    $chalan_no = 'CH'.$ch_no;
                  }
                  else
                  {
                    $chalan_no = 'CH'.$add_one;
                  }
                }
                else
                {                    
                  $chalan_no = 'CH001';
                }                    
                echo $chalan_no;
                echo '<input type="hidden" name="chalan_no" value="'.$chalan_no.'">';
                ?>
              </div>             
            </div>                              
           
            <div class="form-group">                
              <table class="table table-bordered">
                <thead>
                  <tr>                    
                    <th>PARTICULARS</th>
                    <th>QNTY @kg</th>
                    <th>RATE</th>
                    <th>AMOUNT</th>
                  </tr>
                </thead>
               
                <tbody>
                  <tr class="row_one">                    
                    <td>
                      <select name="items[]" class="form-control">
                        <option value="">--select product--</option>
                        <?php foreach ($productList->result() as $row){ 
                        echo '<option label="'.$row->unit_price.'" value="'.$row->product_name.'" '. set_select("items[]", $row->product_name).'>'.$row->product_name.'</option>';
                        } ?>
                      </select>
                    </td>
                    <td><input type="text" name="qnty[]" class="qnty form-control only_num" size="2" value="<?php echo set_value('qnty[]'); ?>"></td>
                    <td><input type="text" name="rate[]" class="rate form-control only_num" size="2" value="<?php echo set_value('rate[]'); ?>"></td>
                    <td>
                      <input type="text" name="amount[]" class="amount form-control" style="display: inline; width: 40%" size="3" readonly="readonly" value="<?php echo set_value('amount[]'); ?>">&nbsp;
                      <button type="button" name="add_more" id="add_more" class="btn btn-success btn-sm add_more"><b>+</b></button>
                      &nbsp;<button type="button" name="remove" id="remove" class="btn btn-warning btn-sm remove"><b>X</b></button>
                    </td>
                  </tr>

                  <!--2nd row-
                  <tr class="row_one">                    
                    <td>
                      <select name="items[]" class="form-control">
                        <option value="">--select product--</option>
                        <?php foreach ($productList->result() as $row){ 
                        echo '<option label="'.$row->unit_price.'" value="'.$row->product_name.'" '. set_select("items[]", $row->product_name).'>'.$row->product_name.'</option>';
                        } ?>
                      </select>
                    </td>
                    <td><input type="text" name="qnty[]" class="qnty form-control only_num" size="2" value="<?php echo set_value('qnty[]'); ?>"></td>
                    <td><input type="text" name="rate[]" class="rate form-control only_num" size="2" value="<?php echo set_value('rate[]'); ?>"></td>
                    <td>
                      <input type="text" name="amount[]" class="amount form-control" style="display: inline; width: 40%" size="3" readonly="readonly" value="<?php echo set_value('amount[]'); ?>">&nbsp;
                      <button type="button" name="add_more" id="add_more" class="btn btn-success btn-sm add_more"><b>+</b></button>
                      &nbsp;<button type="button" name="remove" id="remove" class="btn btn-warning btn-sm remove"><b>X</b></button>
                    </td>
                  </tr>   
                  3rd row
                  <tr class="row_one">                    
                    <td>
                      <select name="items[]" class="form-control">
                        <option value="">--select product--</option>
                        <?php foreach ($productList->result() as $row){ 
                        echo '<option  label="'.$row->unit_price.'" value="'.$row->product_name.'" '. set_select("items[]", $row->product_name).'>'.$row->product_name.'</option>';
                        } ?>
                      </select>
                    </td>
                    <td><input type="text" name="qnty[]" class="qnty form-control only_num" size="2" value="<?php echo set_value('qnty[]'); ?>"></td>
                    <td><input type="text" name="rate[]" class="rate form-control only_num" size="2" value="<?php echo set_value('rate[]'); ?>"></td>
                    <td>
                      <input type="text" name="amount[]" class="amount form-control" style="display: inline; width: 40%" size="3" readonly="readonly" value="<?php echo set_value('amount[]'); ?>">&nbsp;
                      <button type="button" name="add_more" id="add_more" class="btn btn-success btn-sm add_more"><b>+</b></button>
                      &nbsp;<button type="button" name="remove" id="remove" class="btn btn-warning btn-sm remove"><b>X</b></button>
                    </td>
                  </tr>-->                                         
                </tbody>
                  </table>
                </div>
                <div class="form-group">
                  <div class="col-sm-4 col-sm-offset-8">
                    <b>TOTAL</b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="total_amt" class="total form-control" style="display: inline; width: 50%" id="total_amt" value="<?php echo set_value('total_amt');?>" size="3" readonly="readonly">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-10 col-sm-offset-1">                 
                    <b>AMOUNT IN WORDS:</b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="total_word" name="total_word" class="form-control" style="display: inline; width: 50%" value="<?php echo set_value('total_word');?>" size="3" readonly="readonly">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">&nbsp;</div>
                </div>
                <div class="form-group">
                  <div class="col-sm-6 col-sm-offset-1">
                    <b>This receipt should be signed by the person having the authority. No complaints will be entertained if the same are received after 24 hours of the delivery.</b>
                  </div>
                  <div class="col-sm-3">
                    <p class="text-right">FOR <b>LABBAIK ENTERPRISES</b></p>
                    <p class="text-right"><br />AUTHORISED SIGNATURE</p>
                  </div>
                  <div class="col-sm-2">&nbsp;</div>
                </div>
                <div class="form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                    <button type="submit" name="add_challan" class="btn btn-primary">SAVE & PRINT</button> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="reset" name="reload" id="reload" class="btn btn-primary">Reset</button>                    
                  </div>
                </div>                 

            <?php echo form_close();  ?>

          </div><!--challan div-->
        </div><!--panel body-->
      </div>
    </div>        
  </div>
</div>




<!--footer section-->
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
<script src="<?php echo base_url('assets/js/to_words.js'); ?>"></script>
<script type="text/javascript">
var mytable;
$(document).ready(function(){

  //show customer address on customer name change event
  var cust_list = <?php echo json_encode($custList->result()); ?>;
  $("#customerName").on('change', function(){
    var cust_id = $("#customerName option:selected").val();

      for (var key = 0; key < cust_list.length; key++) 
      {
        if( cust_list[key].id == cust_id )
        {
          $("#cust_adds_txt").val(cust_list[key].bakery_area+' - '+cust_list[key].bakery_city);
          $("#cust_adds").val(cust_list[key].bakery_area+', '+cust_list[key].bakery_city);
          $("#cust_name").val(cust_list[key].bakery_name);
        }
      }
  });

  //set product rate on change
  $("select[name^='items']").on('change', function(){
    var prod_name = $(this).children("option:selected").attr("label");
    var ro = $(this).closest("tr");
    if(prod_name != '')
    {
      ro.find('.rate').val(prod_name);
    }
  });


  mytable = $('#datatable').dataTable({"pageLength": 25});
  $("[data-toggle=tooltip]").tooltip();  

  setTimeout(function() {
    $(".show_hide").alert('close');
  }, 4000);

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

    //add only NUMBER and .12.33.22
    $('.only_num').on('keyup', function(){
        var val = $(this).val();
        if(isNaN(val))
        {
            val = val.replace(/[^0-9\.]/g,'');
            if(val.split('.').length>2) 
            val = val.replace(/\.+$/,"");       
        }
        $(this).val(val); 
    });


    //show AMOUNT by qnty*rate
    $('.amount').on('focus', function(){
        var ro  = $(this).parents('tr');
        var qnty = ro.find('.qnty').val();
        var rate = ro.find('.rate').val();
        var the_amount = (qnty*rate).toFixed(2);
        $(this).val(the_amount);      
    });

    $('.qnty, .rate').on('change', function(){
        var ro  = $(this).closest('tr');
        var qnty = ro.find('.qnty').val();
        var rate = ro.find('.rate').val();
        var the_amount = (qnty*rate).toFixed(2);
        ro.find('.amount').val(the_amount);      
    });

    //show TOTAL AMOUNT by qnty*rate
    var tot_num ;
    $('.total').on('focus', function(){ 
        var crnt_val = parseFloat($(this).val());
        var total = 0;
        $('.amount').each(function(){            
            if( $(this).val() != '' )
            {
                var amt = $(this).val();
                total += parseFloat(amt);                
            }   
        });
        if(total != crnt_val)
        {
          tot_num = total;
          $(this).val(Math.round(total));
          if(tot_num != null)
          {      
            var total_wrd = NumToWord(Math.round(tot_num));
            $("#total_word").val(total_wrd);
          }

        }
    });    
    
   
    // amount in WORDS
    $("#total_word").on('focus', function(){
      if(tot_num != null)
      {      
        var tot = NumToWord(Math.round(tot_num));
        $(this).val(tot);
      }
    });

</script>
