<div class="container-fluid" id="bg-color">
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>Welcome to Labbaik Bakery<span class="text-sm pull-right"><a href="<?php echo base_url('/index.php/dashboard/logout');?>">Log Out</a></span>
					</h4>
				</div>
				
				<div class="panel-body">	
					<div class="challan-div">		
						<div class="row">
							<img src="<?php echo base_url('assets/images/labbaik-bill-logo.jpg');?>" class="img-responsive">
						</div>	     
						<form id="outsider_invoice_form" name="outsider_invoice_form" class="form-horizontal" action="<?php echo site_url('billing/add_outsider_invoice');?>" method="post">
							<div class="form-group">
								<hr style="height: 1px; border-top: 1px solid black">
								<h3 class="text-center">TAX INVOICE</h3>
							</div>
							<div class="col-sm-5 leftbox">
								<div class="form-group">
									<label class="control-label col-sm-3" for="customer">To</label>
									<div class="col-sm-9">
	  									<select name="customerName" id="customerName" class="form-control">
	  										<option value="" selected="selected">--select customer--</option>
	  										<?php foreach ($custList->result() as $row){  ?>
	  										<option value="<?php echo $row->bakery_name;?>" <?php echo  set_select("customerName", "$row->bakery_name"); ?> label="<?php echo $row->id;?>"><?php echo $row->bakery_name. '- '.$row->bakery_city;?></option>
	  										<?php } ?>
	  									</select>									
							   		</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3" for="customer">Address</label>
									<div class="col-sm-9" id="addds_holder">
							   			<ul id="address">
  										<?php foreach ($custList->result() as $row){  ?>
  										<li style="display: none;" id="<?php echo $row->id;?>"><?php echo $row->bakery_address;?></li>
  										<?php } ?>
                                        <input type="hidden" name="cust_adds" value="" id="cust_adds">
  									    </ul>
							   		</div>
								</div>	
								<div class="form-group">
									<label class="control-label col-sm-3" for="customer">Buyer's GST</label>
									<div class="col-sm-9">
							   			<ul id="gst">
  										<?php foreach ($custList->result() as $row){  ?>
  										<li style="display: none;" id="<?php echo $row->id;?>"><?php echo $row->bakery_gst;?></li>
  										<?php } ?>                                        
  									    </ul>
                                        <input type="hidden" name="cust_gst" value="" id="cust_gst">
							   		</div>
								</div>  
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Select Region</label>
                                    <div class="col-sm-9">
                                        <select id="region" name="region" class="form-control">
                                            <option value="">--select--</option>
                                            <option value="in">In Maharashtra</option>
                                            <option value="out">Out of Maharashtra</option>
                                        </select>
                                    </div>
                                </div>   
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Total Amount</label>
                                    <div class="col-sm-9">
                                        <select id="amount_with" name="amount_with" class="form-control">
                                            <option value="">--select--</option>
                                            <option value="with">With Tax</option>
                                            <option value="without">Without Tax</option>
                                        </select>
                                    </div>
                                </div>                                                              
							</div>
							<div class="col-sm-1">&nbsp;</div>
							<div class="col-sm-6 leftbox">
								<div class="form-group">
									<label class="control-label col-sm-4">INVOICE NO.</label>
									<div class="col-sm-6">&nbsp;&nbsp;&nbsp;
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
                                        <label><?php echo $invoice_no;?><input type="hidden" name="invoice_no" value="<?php echo $invoice_no; ?>"></label>                           
                                    </div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4">INVOICE DATE</label>
									<div class="col-sm-6">&nbsp;&nbsp;&nbsp;<?php echo date('d/m/Y');?></div>
								</div>	
								<div class="form-group">
									<label class="control-label col-sm-4">DATE OF SUPPLY</label>
									<div class="col-sm-8"><input type="text" name="sup_date" class="form-control">
									</div>
								</div>	
								<div class="form-group">
									<label class="control-label col-sm-4">PLACE OF SUPPLY</label>
									<div class="col-sm-8">
										<input type="text" name="sup_place" class="form-control">
									</div>
								</div>	
								<div class="form-group">
									<label class="control-label col-sm-4">OTHER</label>
									<div class="col-sm-8"><input type="text" name="sup_other" class="form-control">
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
                                      echo '<div class = "alert alert-warning alert-block successMsg">';
                                      echo $this->session->flashdata('fail');
                                      echo '</div>';
                                    }
                                  ?>
                                </div>
                            </div>                             
							<div class="form-group" id="table_without_tax">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>DESCRIPTION</th>
                                            <th>HSN</th>
                                            <th>QNTY @kg</th>
                                            <th>RATE</th>
                                            <th>AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="row_one">
                                            <td>
                                            	<input type="text" name="sr[]" id="sr" class="form-control" size="3" maxlength="7">
                                            </td>
                                            <td>
                                                <select name="items[]" id="items" class="form-control">
                                                    <option value="">--select product--</option>
                                                    <?php foreach ($productList->result() as $row){  ?>
                                                    <option value="<?php echo $row->product_name; ?>"><?php echo $row->product_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td><input type="text" name="hsn[]" class="hsn form-control" size="3" maxlength="7" value="1901"></td>
                                            <td><input type="text" name="qnty[]" class="qnty only_num form-control" size="3" maxlength="7"></td>
                                            <td><input type="text" name="rate[]" class="rate only_num form-control" size="3" maxlength="7"></td>
                                            <td>
                                                <input type="text" name="amount[]" class="amount form-control" style="display: inline; width: 40%" value="" size="3" readonly="readonly">&nbsp;
                                                <button type="button" name="add_more" class="add_more btn btn-success btn-sm"><b>+</b></button>
                                                &nbsp;<button type="button" name="remove" id="remove" class="btn btn-warning btn-sm remove"><b>X</b></button>
                                            </td>
                                        </tr>                                                                              

                                    </tbody>
                                </table>
                            </div>
                            <!--with tax-->
                            <div class="form-group" id="table_with_tax">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>DESCRIPTION</th>
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
                                                <input type="text" name="sr[]" id="sr" class="form-control" size="3" maxlength="7">
                                            </td>
                                            <td>
                                                <select name="items[]" id="items" class="form-control">
                                                    <option value="">--select product--</option>
                                                    <?php foreach ($productList->result() as $row){  ?>
                                                    <option value="<?php echo $row->product_name; ?>"><?php echo $row->product_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td><input type="text" name="hsn[]" class="hsn form-control" size="3" maxlength="7"></td>
                                            <td><input type="text" name="qnty[]" class="qnty form-control" size="3" maxlength="7"></td>
                                            <td><input type="text" name="amount_with_tax[]" class="amount_with_tax only_num form-control" size="3" maxlength="7"></td>                                            
                                            <td><input type="text" name="rate[]" class="rate form-control" size="3" maxlength="7"></td>
                                            <td>
                                                <input type="text" name="amount[]" class="amount form-control" style="display: inline; width: 40%" value="" size="3" readonly="readonly">&nbsp;
                                                <button type="button" name="add_more" id="add_more" class="add_more btn btn-success btn-sm"><b>+</b></button>
                                                &nbsp;<button type="button" name="remove" id="remove" class="btn btn-warning btn-sm remove"><b>X</b></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>                            
                            <!--ends here-->
                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-6">
                                    <b>TRANSPORT CHARGES</b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="trans_charge" class="only_num form-control" id="trans_charge" style="display: inline; width: 30%" value="" size="3">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-6">
                                    <b>OTHER</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="other_charge" class="only_num form-control" style="display: inline; width: 30%" id="other_charge" value="" size="3">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-6">
                                    <b>TOTAL TAXABLE VALUE</b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="total_tax_value" class="form-control" id="total_tax_value" readonly="readonly" style="display: inline; width: 30%" value="" size="3">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-6">
                                    <b>IGST @ 5%</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="igst_charge" id="igst_charge" readonly="readonly" class="form-control" style="display: inline; width: 30%" value="" size="3">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-6">
                                    <b>TOTAL AMOUNT</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="total_amount" id="total_amount" class="total form-control" readonly="readonly" style="display: inline; width: 30%" value="" size="3">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-6">
                                    <b>ROUND OFF TOTAL</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="total_round" readonly="readonly" id="total_round" class="form-control" style="display: inline; width: 30%" value="" size="3">
                                </div>
                            </div>                                                        
                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <b>AMOUNT IN WORDS:</b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="total_word" class="form-control" id="total_word" style="display: inline; width: 50%" value="" size="3">
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
                                <button type="button" name="reload" id="reload" class="btn btn-primary">Reset</button>                    
                              </div>
                            </div>


						</form>
					</div>
				</div>

			 	<div class="panel-footer">
			 	 	<p class="text-right">for Labbaik Bakery </p>
				</div>
    		</div>
    	</div>    		
	</div>
</div>

<!--add model-->


</div><!--close main div-->
</body>
</html>
