<!DOCTYPE html>
<html>
<head>
  <title>Invoice insider</title>
  <style type="text/css">
  table#listing_tabl th, table#listing_tabl td {border-style: solid; border-width: 1px; border-collapse: separate;}
  table#listing_tabl tr td {text-align: center; height: 30px;}

  </style>
</head>
<body>
<div class="box">
  <div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      
      <div class="challan-div">   
        <div class="row">
          <img src="<?php echo base_url('assets/images/labbaik-bill-logo.jpg');?>" class="img-responsive">
        </div>       
        <div class="form-group">
          <hr style="height: 1px; border-top: 1px solid black">
          <h3 style="text-align: center;">TAX INVOICE</h3>
        </div>

        <div class="form-group">
          <div style="float: left; width: 45%; background-color: #e1e1e9; padding: 10px">
            <p>TO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $customer_id; ?> </p>
            <p>ADDS. &nbsp;&nbsp; <?php echo $customer_address; ?></p>
            <p>BUYER'S GST &nbsp;&nbsp;&nbsp; <?php echo $buyer_gst; ?> </p>
          </div>
          <div style="float: right; width: 45%; background-color: #e1e1e9; padding: 10px">
            <p> INVOICE NO. &nbsp;&nbsp;&nbsp; <?php echo $invoice_no; ?> </p>
            <p> INVOICE DATE &nbsp;&nbsp;&nbsp; <?php echo date('d/m/y'); ?> </p>
            <p> DATE OF SUPPLY &nbsp;&nbsp;&nbsp; <?php echo $date_of_supply; ?> </p>
            <p> PLACE OF SUPPLY &nbsp;&nbsp;&nbsp; <?php echo $place_of_supply; ?> </p>
            <p> OTHER &nbsp;&nbsp;&nbsp; <?php echo $other_notes; ?> </p>
          </div>
        </div>

        <div class="form-group">
          <div>&nbsp;<br /></div>
        </div>
        <div class="form-group">
          <table id="listing_tabl" style="border-style: solid; border-width: 1px" width="100%">
            <thead>
              <tr style="background-color: #e1e1e9">
                <th style="width: 10%">Sr. No</th>
                <th style="width: 40%">PARTICULARS</th>
                <!-- <th style="width: 10%">HSN</th> -->
                <th style="width: 14%">QNTY @kg</th>
                <th style="width: 13%">RATE</th>
                <th style="width: 13%">AMOUNT</th>
              </tr>
            </thead>
            <tbody>             
              <?php
                $mat = explode(',', $material);
                $hsn = explode(',', $hsn);
                $qnty = explode(',', $qnty);
                $rate = explode(',', $rate);
                $amount = explode(',', $amount);                          
                $items = array( 'mat'=> $mat, 'hsn' => $hsn, 'qnty' => $qnty,'rate' => $rate, 'amount' => $amount);              
                $len = count($items['mat']);                        
                
                $items2 = array();
                for ($i=0; $i < $len; $i++)
                { 
                  $newArray = array();
                  $newArray[] = $items['mat'][$i];
                  $newArray[] = $items['hsn'][$i];
                  $newArray[] = $items['qnty'][$i];
                  $newArray[] = $items['rate'][$i];
                  $newArray[] = $items['amount'][$i];    
                  $items2[] = $newArray;             
                }    
                
                $j = 1;
                $all_items = count($items2);
                if($all_items < 5)
                {
                  for($i=0;$i<count($items2);$i++) { ?>             
                    <tr>
                      <td><?php echo $j; ?></td>
                      <td><?php echo $items2[$i][0]; ?></td>
                      <td><?php echo $items2[$i][1]; ?></td>
                      <td><?php echo $items2[$i][2]; ?></td>
                      <td>Rs. <?php echo $items2[$i][3]; ?></td>
                      <td>Rs. <?php echo $items2[$i][4]; ?></td>
                    </tr>    
                  <?php  $j++; } ?>                 
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr> 
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>   
                <?php } else { ?>   
                  <?php for($i=0;$i<count($items2);$i++) { ?>             
                    <tr>
                      <td><?php echo $j; ?></td>
                      <td><?php echo $items2[$i][0]; ?></td>
                      <td><?php echo $items2[$i][1]; ?></td>
                      <td><?php echo $items2[$i][2]; ?></td>
                      <td>Rs. <?php echo $items2[$i][3]; ?></td>
                      <td>Rs. <?php echo $items2[$i][4]; ?></td>
                    </tr>    
                  <?php  $j++; } } ?>  
            </tbody>
          </table>
        </div>
       
        <div class="form-group">
            <div style="width: 40%; float: left;">&nbsp;</div>
            <div style="width: 50%; float: right;">
                <table width="90%">
                    <tr>
                        <td colspan="2">&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;&nbsp;</td>
                    </tr>                    
                    <tr>
                        <th style="text-align: right;">TRANSPORT CHARGES</th>
                        <td>&nbsp;</td>
                        <td><?php echo $transport_charges ? 'Rs. '. $transport_charges : ''; ?></td>
                    </tr>
                    <tr>
                        <th style="text-align: right;">OTHER</th>
                        <td>&nbsp;</td>
                        <td> <?php echo $other_charge ? 'Rs. '. $other_charge : ''; ?> </td>
                    </tr>
                    <tr>
                        <th style="text-align: right;">TOTAL TAXABLE VALUE</th>
                        <td>&nbsp;</td>
                        <td> <?php echo 'Rs. '. $total_taxable_amount; ?> </td>
                    </tr>
                    <tr>
                        <th style="text-align: right;">IGST @ 5%</th>
                        <td>&nbsp;</td>
                        <td> <?php echo 'Rs. '. $igst_5_cent; ?> </td>
                    </tr>
                    <tr>
                        <th style="text-align: right;">TOTAL AMOUNT</th>
                        <td>&nbsp;</td>
                        <td><?php echo 'Rs. '. $total; ?></td>
                    </tr>                                                                                
                    <tr>
                        <th style="text-align: right;">ROUND OFF TOTAL</th>
                        <td>&nbsp;</td>
                        <td><?php echo 'Rs. '. $round_off_total; ?></td>
                    </tr>                     
                </table>                      
            </div>
        </div>
    
        <div class="form-group">
          <div> 
            <br />                
            <b>AMOUNT IN WORDS:</b>&nbsp;&nbsp;&nbsp;&nbsp;Rupees <?php echo $total_in_words; ?> Only
          </div>
        </div>
        <div class="form-group">
          <div>&nbsp;</div>
        </div>
    
        <div class="form-group">
          <div>
            <b>Note:</b> This receipt should be signed by the person having the authority. No complaints will be entertained if the same are received after 24 hours of the delivery.
          </div>
          <div>&nbsp;<br /><br /></div>
          <div>
            <p style="width: 80%; text-align: right;">FOR <b>LABBAIK ENTERPRISES</b></p>
            <p style="width: 80%; text-align: right;"><br />AUTHORISED SIGNATURE</p>
          </div>
          <div class="col-sm-2">&nbsp;</div>
        </div>
           
      </div>
               
    </div>        
  </div>
  </div>

</div><!--close main div-->
</body>
</html>
