<div class="container-fluid" id="bg-color"><br /></div>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
                <div class="panel-heading">
                    <h4><?php echo ucwords($username).', ';?><small><?php echo  date('d F, Y');?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Invoice/logout');?>">Log Out</a></span>
                    </h4>
                </div>
				
				<div class="panel-body">	
					<div class="challan-div">			     
						<form id="insider_invoice_form" name="insider_invoice_form" class="form-horizontal" action="<?php echo site_url('Invoice/add_insider_invoice');?>" method="post">
							<div class="form-group">						
								<h3 class="text-center">Pices Recived</h3>
							</div>
							<div class="col-sm-5 leftbox">
								<div class="form-group ">
									<label class="control-label col-sm-3">To</label>
									<div class="col-sm-6">
	  									<select name="customerName" id="customerName" class="form-control">
	  										<option value="" selected="selected">--select customer--</option>
                                            <?php foreach ($custList->result() as $row){
                                                echo '<option value="'.$row->id.'" '.set_select('customerName',$row->id).'>'.$row->bakery_name.'</option>';
                                            } ?>
                                            <input type="hidden" name="cust_adds" value="<?php echo set_value('cust_adds');?>" id="cust_adds">
                                        <input type="hidden" name="cust_name" value="<?php echo set_value('cust_name');?>" id="cust_name"> 
                                            <!-- <option value="other">Other</option> -->
	  									</select> 								
							   		</div>
                                    <div class="col-sm-2">
                                    <a class="btn btn-default" role="button"  href="<?php echo base_url('/index.php/Customer/add_new');?>">Add Customer</a> 
                                    </div>
								</div>
                                <div class="form-group hide">
									<label class="control-label col-sm-3">Customer Name</label>
									<div class="col-sm-9" id="addds_holder">
                                        <input type="text" name="customerName1" id="customerName" class="form-control" value="<?php echo set_value('customerName');?>">
                                        <!-- <input type="hidden" name="cust_adds" value="<?php echo set_value('cust_adds');?>" id="cust_adds">
                                        <input type="hidden" name="cust_name" value="<?php echo set_value('cust_name');?>" id="cust_name">  -->        
                                    </div>
								</div>	
								<div class="form-group">
									<label class="control-label col-sm-3">Address</label>
									<div class="col-sm-9" id="addds_holder">
                                        <input type="text" name="cust_adds_txt" id="cust_adds_txt" class="form-control" value="<?php echo set_value('cust_adds_txt');?>">
                                        <input type="hidden" name="cust_adds" value="<?php echo set_value('cust_adds');?>" id="cust_adds">
                                        <input type="hidden" name="cust_name1" value="<?php echo set_value('cust_name');?>" id="cust_name1">         
                                    </div>
								</div>	
								<div class="form-group hide">
									<label class="control-label col-sm-3">Buyer's GST</label>
									<div class="col-sm-9">
                                        <input type="text" name="cust_gst_txt" id="cust_gst_txt" class="form-control " value="<?php echo set_value('cust_gst_txt');?>" readonly="readonly">
                                        <input type="hidden" name="cust_gst" value="<?php echo set_value('cust_gst');?>" id="cust_gst">
							   		</div>
								</div> 
                                <div class="form-group hide">
                                    <label class="control-label col-sm-3">Select Region</label>
                                    <div class="col-sm-9">
                                        <select id="region" name="region" class="form-control">
                                            <option value="">--select--</option>
                                            <option value="in" <?php echo set_select('region','in');?>>In Maharashtra</option>
                                            <option value="out" <?php echo set_select('region','out');?>>Out of Maharashtra</option>
                                        </select>
                                    </div>
                                </div>   
                                <div class="form-group hide">
                                    <label class="control-label col-sm-3">Invoice Type</label>
                                    <div class="col-sm-9">
                                        <select id="amount_with" name="amount_with" class="form-control">
                                            <option value="">--select--</option>
                                            <option value="with" <?php echo set_select('amount_with','with');?>>Amount With GST</option>
                                            <option value="without" <?php echo set_select('amount_with','without');?>>Amount Without GST</option>
                                        </select>
                                    </div>
                                </div>                                                                                             
							</div>
							<div class="col-sm-1">&nbsp;</div>
							<div class="col-sm-6 leftbox inv">
								<div class="form-group">
									<label class="control-label col-sm-4">INVOICE NO.</label>
									<div class="col-sm-8">&nbsp;&nbsp;
                                        <?php

                                        $invoice_no = '';                
                                        if(!empty($last_invoice->invoice_no))
                                        {
                                            $db_invoice = $last_invoice->invoice_no;
                                            $num_part = substr($db_invoice, 3);
                                            $add_one = intval($num_part)+1;

                                            if(strlen($add_one) < 3)
                                            {
                                                $ch_no = sprintf("%03u", $add_one);
                                                $invoice_no = 'INV'.$ch_no;
                                            }
                                            else
                                            {
                                                $invoice_no = 'INV'.$add_one;
                                            }
                                        }
                                        else
                                        {                    
                                            $invoice_no = 'INV001';
                                        }                    

                                        ?>       
                                        <?php echo '<b>'.$invoice_no.'</b>';?>
                                        <input type="hidden" name="invoice_no" value="<?php echo $invoice_no; ?>">          
                                    </div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4">INVOICE DATE</label>
									<div class="col-sm-8">&nbsp;&nbsp;&nbsp;<?php echo date('d/m/Y');?></div>
								</div>	
								<div class="form-group hide">
									<label class="control-label col-sm-4">DATE OF SUPPLY</label>
									<div class="col-sm-8">&nbsp;<input type="date" name="sup_date" value="<?php echo set_value('sup_date');?>" class="form-control">
									</div>
								</div>	
								<div class="form-group hide">
									<label class="control-label col-sm-4">PLACE OF SUPPLY</label>
									<div class="col-sm-8">
										<input type="text" name="sup_place" value="<?php echo set_value('sup_place');?>" class="form-control">
									</div>
								</div>	
								<div class="form-group hide">
									<label class="control-label col-sm-4">OTHER</label>
									<div class="col-sm-8"><input type="text" name="sup_other" value="<?php echo set_value('sup_other');?>" class="form-control">
									</div>
								</div>																																								
							</div>
                            <div class="col-sm-12">&nbsp;</div>
                            <div class="form-group"><br />
                              <div class="col-sm-8 col-sm-offset-2">
                                <?php echo validation_errors('<p class="text text-danger">', '</p>'); ?>
                              </div>
                            </div>

                            <div class="form-group"><br />
                                <div class="col-sm-8 col-sm-offset-2">                
                                    <?php
                                    if($this->session->flashdata('pass'))
                                    {
                                      echo '<div class="alert alert-success alert-block successMsg"> ';
                                      echo $this->session->flashdata('pass');
                                      echo '</div>';
                                    }
                                    else if($this->session->flashdata('fail'))
                                    {
                                      echo '<div class = "alert alert-warning successMsg">';
                                      echo $this->session->flashdata('fail');
                                      echo '</div>';
                                    }
                                  ?>
                                </div>
                            </div>                             
							<div class="form-group" id="table_with_tax">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>                                            
                                            <th>PRODUCT</th>
                                            <th>HSN</th>
                                            <th>QNTY @kg</th>
                                            <th>AMOUNT WITH TAX</th>
                                            <th>RATE</th>
                                            <th>AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="row_one">
                                            <td>
                                                <select name="items[]" id="items" class="form-control">
                                                    <option value="">--select product--</option>
                                                    <?php foreach ($productList->result() as $row){ 
                                                    echo '<option value="'.$row->product_name.'" '. set_select("items[]", $row->product_name).'>'.$row->product_name.'</option>';
                                                    } ?>
                                                </select>
                                            </td>
                                            <td><input type="text" name="hsn[]" class="hsn form-control" size="3" maxlength="7" value=""></td>
                                            <td><input type="text" name="qnty[]" class="qnty form-control" size="3" maxlength="7"></td>
                                            <td><input type="text" name="amount_with_tax[]" class="amount_with_tax only_num form-control" size="3" maxlength="7"></td>                      
                                            <td><input type="text" name="rate[]" class="rate form-control" size="3" maxlength="7"></td>
                                            <td>
                                                <input type="text" name="amount[]" class="amount form-control" style="width: 40%; display: inline;" value="" size="3" readonly="readonly">&nbsp;
                                                <button type="button" name="add_more" id="add_more" class="add_more btn btn-success btn-sm"><b>+</b></button>&nbsp;
                                                <button type="button" name="remove" id="remove" class="btn btn-warning btn-sm remove"><b>X</b></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--table withouot tax-->
                            <div class="form-group" id="table_without_tax">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>                                            
                                            <th>PRODUCT</th>
                                            <th>Design No</th>
                                            <th>QNTY </th>                                            
                                            <th>RATE</th>
                                            <th>AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="row_one">
                                            <td>
                                                <select name="items[]" id="items" class="form-control">
                                                    <option value="">--select product--</option>
                                                    <?php foreach ($productList->result() as $row){ 
                                                    echo '<option label="" value="'.$row->product_name.'" '. set_select("items[]", $row->product_name).'>'.$row->product_name.'</option>';
                                                    } ?>
                                                </select>
                                            </td>
                                            <td class=""><input type="text" name="hsn[]" class="hsn form-control" size="3" maxlength="7" value=""></td>
                                            <td><input type="text" name="qnty[]" class="qnty form-control" size="3" maxlength="7"></td>                                            
                                            <td><input type="text" name="rate[]" class="rate form-control" size="3" maxlength="7"></td>
                                            <td>
                                                <input type="text" name="amount[]" class="amount form-control" style=" width: 40%; display: inline;" value="" size="3" readonly="readonly">&nbsp;
                                                <button type="button" name="add_more" id="add_more" class="add_more btn btn-success btn-sm"><b>+</b></button>
                                                &nbsp;<button type="button" name="remove" id="remove" class="btn btn-warning btn-sm remove"><b>X</b></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>                            
                            <!--ends here-->
                            <div class="form-group hide">
                                <div class="col-sm-2 col-sm-offset-6">
                                    <b>TRANSPORT CHARGES</b>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="trans_charge" class="form-control only_num" id="trans_charge" style="display: inline; width: 50%" value="" size="3">
                                </div>
                            </div>
                            <div class="form-group hide">
                                <div class="col-sm-2 col-sm-offset-6">
                                    <b>OTHER</b>
                                </div>
                                <div class="col-sm-3"> 
                                    <input type="text" name="other_charge" id="other_charge" class="only_num form-control" style="display: inline; width: 50%" value="" size="3">
                                </div>
                            </div>

                            <div class="form-group hide">
                                <div class="col-sm-2 col-sm-offset-6">
                                    <b>TOTAL TAXABLE VALUE</b>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="total_tax_value" class="form-control" id="total_tax_value" readonly="readonly" style="display: inline; width: 50%" value="" size="3">
                                </div>
                            </div>

                            <div class="form-group in_state hide">
                                <div class="col-sm-2 col-sm-offset-6">
                                    <b>CGST @ <input type="number" step="0.5" name="cgst_per" id="cgst_per" style="display: inline; width: 40%">%</b>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="cgst_charge" class="form-control" id="cgst_charge" readonly="readonly" style="display: inline; width: 50%" value="" size="3">
                                </div>
                            </div>

                            <div class="form-group in_state hide">
                                <div class="col-sm-2 col-sm-offset-6">
                                    <b>SGST @ <input type="number" step="0.5" name="sgst_per" id="sgst_per" style="display: inline; width: 40%">%</b>
                                </div>
                                <div class="col-sm-3"> 
                                    <input type="text" name="sgst_charge" id="sgst_charge" readonly="readonly" class="form-control" style="display: inline; width: 50%" value="" size="3">
                                </div>
                            </div>

                            <div class="form-group out_state hide">
                                <div class="col-sm-2 col-sm-offset-6">
                                    <b>IGST @ <input type="number" step="0.5" name="igst_per" id="igst_per" style="display: inline; width: 40%">%</b>
                                </div>
                                <div class="col-sm-3"> 
                                    <input type="text" name="igst_charge" id="igst_charge" readonly="readonly" class="form-control" style="display: inline; width: 50%" value="" size="3">
                                </div>
                            </div> 
                            <div class="form-group">
                                <div class="col-sm-2 col-sm-offset-6">
                                    <b>TOTAL AMOUNT</b>
                                </div>
                                <div class="col-sm-3"> 
                                    <input type="text" name="total_amount" id="total_amount" readonly="readonly" class="total form-control" style="display: inline; width: 50%" value="" size="3">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-2 col-sm-offset-6">
                                    <b>ROUND OFF TOTAL</b>
                                </div>
                                <div class="col-sm-3"> 
                                    <input type="text" name="total_round" id="total_round" readonly="readonly" class="form-control" style="display: inline; width: 50%" value="" size="3">
                                </div>
                            </div>                                                        
                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <b>AMOUNT IN WORDS:</b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="total_word" id="total_word" class="form-control" style="display: inline; width: 50%" value="" size="3">
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
                                    <p class="text-right">FOR <b>M.A Abaya Manufacturer</b></p>
                                    <p class="text-right"><br />AUTHORISED SIGNATURE</p>
                                </div>
                                <div class="col-sm-2">&nbsp;</div>
                            </div>                              
                            <div class="form-group">
                              <div class="col-sm-6 col-sm-offset-3">
                                <button type="submit" name="add_challan" class="btn btn-primary">SAVE & PRINT</button>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="reset" name="reload" class="btn btn-primary">Reset</button>                    
                              </div>
                            </div>


						</form>
					</div>
				</div>

			 	<div class="panel-footer">
                    <p class="text-right">for M.A Abaya Manufacturer</p>
				</div>
    		</div>
    	</div>    		
	</div>
</div>
</div><!--close main div-->
<script src="<?php echo base_url('assets/js/to_words.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript">

$(document).ready(function(){
    //hide invoice data table on load
    //$('#table_with_tax, #table_without_tax').hide();
    $('#table_with_tax').hide();
    setTimeout(function(){
        $('.successMsg, .err_db').fadeIn().fadeOut().fadeIn().fadeOut('slow');
    }, 3000);    
    $('#items, .qnty, .amount_with_tax, .rate, .amount, #trans_charge, #other_charge, #total_tax_value, #cgst_charge, #sgst_charge, #igst_charge, #total_amount, #total_round, #total_word').val('');                         
                $('#table_with_tax').hide();  
                $('#table_without_tax').show();
                //set product rate on change
                $("select[name^='items']").on('change', function(){
                    var prod_name = $(this).children("option:selected").attr("label");
                    var ro = $(this).closest("tr");
                    if(prod_name != '')
                    {
                        ro.find('.rate').val(prod_name);
                       // ro.find('.qnty').val(1);
                    }
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
                 
                    if(qnty && rate)
                    {
                        var the_amount = (qnty*rate).toFixed(2);
                        ro.find('.amount').val(the_amount);      
                    }
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

    $(document).on('click', '.hsn', function(){
        $(this).val('1901');
    });

    $('#insider_invoice_form').validate({
        rules: {
            customerName: "required",
            region: "required",
            amount_with: "required",
            total_word: "required",
        }
    });

    //show IGSC OR CGST and SGST hilton 
    $('#region').click(function(){
        var reg = $(this).val();
        if(reg == 'in')
        {
            $('.in_state').fadeIn('slow');
            $('.out_state').fadeOut('slow');
        }
        else
        {
            $('.out_state').fadeIn('slow');
            $('.in_state').fadeOut('slow');
        }
    });   
    //add only NUMBER and .
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

    //show customer address on customer name change event
    var cust_list = <?php echo json_encode($custList->result()); ?>;
    $("#customerName").on('change', function(){
        // alert();
        var cust_id = $("#customerName option:selected").val();

        for (var key = 0; key < cust_list.length; key++) 
        {
            if( cust_list[key].id == cust_id )
            {
                $("#cust_adds_txt").val(cust_list[key].bakery_area+', '+cust_list[key].bakery_city);
                $("#cust_adds").val(cust_list[key].bakery_area+', '+cust_list[key].bakery_city);
                $("#cust_name").val(cust_list[key].bakery_name);
                $("#cust_gst_txt").val(cust_list[key].bakery_gst);
                $("#cust_gst").val(cust_list[key].bakery_gst);
            }
        }         
    });


    //show form for amount with TAX or without TAX and validation logic
    $('#amount_with').change(function(){
        var a_w_t = $(this).val();
        if(a_w_t != '')
        {
            if(a_w_t == 'with')
            { 
               // $('#insider_invoice_form')[0].reset(); 
                $('#items, .qnty, .rate, .amount, #trans_charge, #other_charge, #total_tax_value, #cgst_charge, #sgst_charge, #igst_charge, #total_amount, #total_round, #total_word').val('');              
                $('#table_with_tax').show();  
                $('#table_without_tax').hide();
                //show AMOUNT by qnty*rate
                $('.amount, .rate').on('focus', function(){                    
                    var ro  = $(this).parents('tr');
                    var tot_amount = ro.find("input[name*= 'amount_with_tax']").val();
                    if(tot_amount !== undefined)
                    {                        
                        var qnty = ro.find(".qnty").val() ? ro.find(".qnty").val() : 1;                        
                        var amount = ((tot_amount*100)/105).toFixed(2);                    
                        var rate = (amount/qnty).toFixed(2);                        
                        ro.find('.amount').val(unit_price);        
                        ro.find('.rate').val(price);
                    }
                });   
                
            }
            else
            {
               // $('#insider_invoice_form')[0].reset();    
               $('#items, .qnty, .amount_with_tax, .rate, .amount, #trans_charge, #other_charge, #total_tax_value, #cgst_charge, #sgst_charge, #igst_charge, #total_amount, #total_round, #total_word').val('');                         
                $('#table_with_tax').hide();  
                $('#table_without_tax').show();
                //set product rate on change
                $("select[name^='items']").on('change', function(){
                    var prod_name = $(this).children("option:selected").attr("label");
                    var ro = $(this).closest("tr");
                    if(prod_name != '')
                    {
                        ro.find('.rate').val(prod_name);
                       // ro.find('.qnty').val(1);
                    }
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
                 
                    if(qnty && rate)
                    {
                        var the_amount = (qnty*rate).toFixed(2);
                        ro.find('.amount').val(the_amount);      
                    }
                });                            
            }   //end else brace

            //total taxable value before gst
            var total = 0, total_in_word = 0;
            //total Amount before GST
            $('#total_tax_value').on('focus', function(){ 
                var crnt_val = parseFloat($(this).val());
                total = 0;
                $('.amount').each(function(){                                
                    if( $(this).val() !== '' )
                    {
                        var amt = $(this).val();
                        total += parseFloat(amt);                
                    }                       
                });

                var other_charge = $('#other_charge').val() != '' ? $('#other_charge').val() : 0;
                var trans_charge = $('#trans_charge').val() != '' ? $('#trans_charge').val() : 0;        
                total +=  parseFloat(other_charge) + parseFloat(trans_charge);  
                if(total != crnt_val)
                {
                    $(this).val(total.toFixed(2));
                }
            });

            // add CGST and SGST 2.5% and igst 5%
            $('#cgst_charge').on('focus', function(){
                var cgst = $('#cgst_per').val();
                if(cgst != '')
                {
                    cgst = parseFloat(cgst);
                    var tax = parseFloat( (total*cgst)/100 );
                    $(this).val(tax.toFixed(2));
                }                
            });

            $('#sgst_charge').on('focus', function(){
                var sgst = $('#sgst_per').val();
                if(sgst != '')
                {
                    sgst = parseFloat(sgst);
                    var tax = parseFloat( (total*sgst)/100 );
                    $(this).val(tax.toFixed(2));
                }
            });  

            $('#igst_charge').on('focus', function(){
                var igst = $('#igst_per').val();
                if(igst != '')
                {
                    igst = parseFloat(igst);
                    var tax = parseFloat( (total*igst)/100 );
                    $(this).val(tax.toFixed(2));
                }
            });

                //total with TAX
           

            $('#total_round').on('focus', function(){ 
                var rupee = Math.round( $('#total_amount').val() );      
                $(this).val(rupee);
            }); 

            // amount in WORDS
            $("#total_word").on('focus', function(){
                total_in_word = $('#total_round').val();
                if(total_in_word != null)
                {
                    var total_words = NumToWord(total_in_word);
                    $(this).val(total_words);
                }                
            });

        }   //end main if
    });         
    $('#total_amount').on('focus', function(){


console.log("in total_amount");
total = 0;
$('.amount').each(function(){                                
    if( $(this).val() !== '' )
    {
        var amt = $(this).val();
        total += parseFloat(amt);                
    }                       
});
var crnt_val = parseFloat(total);

var other_charge = $('#other_charge').val() != '' ? $('#other_charge').val() : 0;
var trans_charge = $('#trans_charge').val() != '' ? $('#trans_charge').val() : 0;        
total +=  parseFloat(other_charge) + parseFloat(trans_charge);  
if(total != crnt_val)
{
    $(this).val(total.toFixed(2));
}

var cgst = $('#cgst_charge').val() != '' ? parseFloat($('#cgst_charge').val()) : 0;
var sgst = $('#sgst_charge').val() != '' ? parseFloat($('#sgst_charge').val()) : 0;
var igst = $('#igst_charge').val() != '' ? parseFloat($('#igst_charge').val()) : 0;

/* var total_with_tax = parseFloat($('#total_tax_value').val()) + cgst + sgst + igst ; */
var total_with_tax = parseFloat(total) + 0 + 0 + 0 ;
total_with_tax      = total_with_tax.toFixed(2);
$(this).val(total_with_tax); 
//total round amount
$('#total_round').val(Math.round(total_with_tax));
//total in words
var round_amount = $('#total_round').val();
if( round_amount!= null)
{
    var total_words = NumToWord(round_amount);
    $("#total_word").val(total_words);   
}

});
})

</script>
<script type="text/javascript">
    $(function () {
        $("#ddlModels").change(function () {
            if ($(this).val() == 'other') {
                $("#txtOther").removeAttr("disabled");
                $("#txtOther").focus();
            } else {
                $("#txtOther").attr("disabled", "disabled");
            }
        });
    });
</script>
