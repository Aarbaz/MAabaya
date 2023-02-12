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
              echo form_open('Balance/addBalance', 'class="form-inline" id="balance_form"');
            ?>    
            
     
            <div class="form-group">
              <label for="bal_type">Balance Type: </label>
              <select id="bal_type" name="bal_type" class="form-control">
                <option value="">--select--</option>
                <option value="Challan">Challan</option>
                <option value="Invocie">Invocie</option>
              </select>
            </div>

              <?php
              echo '<pre>';
              print_r($db_data);
              echo '</pre>';
              ?>
            <div class="form-group">
              <label for="frm_date">From: </label>
              <input type="text" name="frm_date" id="frm_date" class="form-control">
            </div>
            
            <div class="form-group">
              <label for="to_date">To: </label>
              <?php echo date('Y-m-d', strtotime('12-mar-2019')); ?>
              <input type="text" name="to_date" id="to_date" class="form-control">
            </div>
             
            <div class="form-group">
              <button type="submit" name="send_bal" class="btn btn-primary">Send</button>
            </div>
            <?php echo form_close();  ?>

          </div><!--challan div-->
        </div><!--panel body-->
      </div>
    </div>        
  </div>
</div>




<!--footer section-->
<div class="container-fluid">
  <div class="footer">
    <div class="row">
      <div class="col-sm-1">&nbsp;</div>
      <div class="col-sm-5">Made with <span style="color: #e25555;"><i class="glyphicon glyphicon-heart"></i></span> By Shareef Ansari</div>   
      <div class="col-sm-1">&nbsp;</div>   
      <div class="col-sm-4">
        <p><i class="glyphicon glyphicon-envelope"></i> ashareefeng@gmail.com &nbsp;&nbsp;&nbsp;<i class="glyphicon glyphicon-earphone"></i>  90295 79146</p>
      </div>      
    </div>
  </div>
</div>
<!--END footer section-->

</div><!--close main div-->
<script src="<?php echo base_url('assets/js/to_words.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript">
var mytable;
$(document).ready(function(){

  mytable = $('#datatable').dataTable({"pageLength": 25});
  $("[data-toggle=tooltip]").tooltip();  

  setTimeout(function() {
    $(".show_hide").alert('close');
  }, 4000);


});
  


    setTimeout(function() {
        $('.successMsg, .err_db').fadeIn().fadeOut().fadeIn().fadeOut('slow');
    }, 3000); 
        

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
    


</script>
