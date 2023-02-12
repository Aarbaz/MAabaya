<html>
<head>
  <title>invoice pdf</title>
  <style type="text/css">
    .center {text-align: center;}
    /*table.hdr tr {padding: 10px;}*/
    table.hdr tr td {background-color: #e1e1e9; padding: 10px}
    #tax_table tr th.right{ text-align: right; }
    /*table td#adds {text-align: justify; } */
  </style>
</head>
<body>

  <!-- <div>
    <img src="<?php echo base_url('assets/images/labbaik-bill-logo.jpg');?>">
  </div> -->

  <table class="hdr" style="width: 100%">
    <tr><td colspan="4">&nbsp;</td></tr>  
    <tr>
      <td width="20%"> TO </td>
      <td width="35%"> <?php echo $customer; ?></td>
      <td width="20%"> INVOICE NO.</td>
      <td width="25%"> <?php echo $invoice_no; ?> </td>
    </tr>
    <tr>  
      <td> ADDS. </td>
      <td id="adds"> <?php echo $customer_address; ?> </td>
      <td> INVOICE DATE </td>
      <td> <?php echo date('d F, Y'); ?> </td>
    </tr>
   <!--  <tr>
      <td> BUYER'S GST </td>
      <td> <?php echo $gst; ?> </td>
      <td> DATE OF SUPPLY </td>
      <td> <?php if($date_of_supply){ echo date('d F, Y', strtotime($date_of_supply)); }?> </td>
    </tr> -->
    <!-- <tr>    
      <td>&nbsp;</td> <td>&nbsp;</td>
      <td> PLACE OF SUPPLY </td>
      <td id="adds"> <?php echo $place_of_supply; ?> </td>
    </tr> -->
  <!--   <tr>
      <td> &nbsp;</td> <td>&nbsp;</td>
      <td> OTHER </td>
      <td> <?php echo $other_notes; ?>  </td>
    </tr>  -->
    <tr><td colspan="4">&nbsp;</td></tr> 
  </table>

  <div><br /></div>
        
<table border="1" cellspacing="0" cellpadding="3" width="100%">
  <tr style="background-color: #e1e1e9">
    <th style="width: 10%">Sr. No</th>
    <th style="width: 40%">PARTICULARS</th>
    <!-- <th style="width: 10%">HSN</th> -->
    <th style="width: 10%">QNTY</th>
    <th style="width: 15%">RATE</th>
    <th style="width: 15%">AMOUNT</th>
  </tr>
  <?php
    $mat = explode(',', $product_name);
    /* $hsn = explode(',', $hsn); */
    $qnty = explode(',', $qnty);
    $rate = explode(',', $rate);
    $amount = explode(',', $amount);                          
    $items = array('mat'=> $mat,'qnty'=>$qnty,'rate' => $rate, 'amount' => $amount); 
    $len = count($items['mat']);                        
    
    $items2 = array();
    for ($i=0; $i < $len; $i++)
    { 
      $newArray = array();
      $newArray[] = $items['mat'][$i];
      //$newArray[] = $items['hsn'][$i];
      $newArray[] = $items['qnty'][$i];
      $newArray[] = $items['rate'][$i];
      $newArray[] = $items['amount'][$i];    
      $items2[] = $newArray;             
    }    

    $j = 1;
    $all_items = count($items2);
    
    $items2 = array_filter(array_map('array_filter', $items2));
    if($all_items > 0)
    {
      for($i=0;$i<20;$i++){?>             
        <tr>
          <td><?php echo $j; ?></td>
          <td><?php echo isset($items2[$i][0])?$items2[$i][0]:''; ?></td>
          <td><?php echo isset($items2[$i][1])?$items2[$i][1]:''; ?></td>
          <td><?php echo isset($items2[$i][2]) ? $items2[$i][2] : '' ; ?></td>
          <td><?php echo isset($items2[$i][3]) ? 'Rs. '.$items2[$i][3] : '' ; ?></td>
          <!-- <td><?php echo $items2[$i][4] ? 'Rs. '.$items2[$i][4] : '' ; ?></td> -->
        </tr>    
      <?php
        $j++; }
    } ?>                                                         
</table>
        
<table width="100%" id="tax_table">
  <tr>
    <td colspan="3">&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;&nbsp;</td>
  </tr>                    
  <tr>
    <th class="right">TRANSPORT CHARGES</th>
    <td>&nbsp;</td>
    <td>&nbsp;<?php echo $transport_charges ? 'Rs. '. $transport_charges : ''; ?></td>
  </tr>
  <tr>
    <th class="right">OTHER</th>
    <td>&nbsp;</td>
    <td> <?php echo $other_charge ? 'Rs. '. $other_charge : ''; ?> </td>
  </tr>
  <tr>
    <th class="right">TOTAL TAXABLE VALUE</th>
    <td>&nbsp;</td>
    <td> <?php echo 'Rs. '. $total_taxable_amount; ?> </td>
  </tr>

  <?php if($cgst_2_5_cent) {?>
  <tr>
    <th class="right">CGST @ <?php echo $cgst_per.'%'; ?></th>
    <td>&nbsp;</td>
    <td> <?php echo 'Rs. '. $cgst_2_5_cent; ?> </td>
  </tr>
  <tr>
    <th class="right">SGST @ <?php echo $sgst_per.'%'; ?></th>
    <td>&nbsp;</td>
    <td> <?php echo 'Rs. '. $sgst_2_5_cent; ?> </td>
  </tr>
  <?php } ?>
  <?php if($igst_5_cent) { ?>
  <tr>
    <th class="right">IGST @ <?php echo $igst_per.'%'; ?></th>
    <td>&nbsp;</td>
    <td> <?php echo 'Rs. '. $igst_5_cent; ?> </td>
  </tr> 
  <?php } ?>                                                         
  <tr>
    <th class="right">TOTAL AMOUNT</th>
    <td>&nbsp;</td>
    <td> <?php echo 'Rs. '. $total; ?></td>
  </tr>                                                                                
  <tr>
    <th  class="right">ROUND OFF TOTAL</th>
    <td>&nbsp;</td>
    <td> <?php echo 'Rs. '. $round_off_total; ?></td>
  </tr>                     
</table>         

<div>&nbsp;</div>
<div>             
  <b>AMOUNT IN WORDS:</b>&nbsp;&nbsp;&nbsp;&nbsp;Rupees <?php echo $total_in_words; ?> Only
</div>
<div>&nbsp;<br /></div>
<table width="100%" border="0">
  <tr>
    <td width="50%">
      <b>Note:</b> This receipt should be signed by the person having the authority. No complaints will be entertained if the same are received after 24 hours of the delivery.</td>
    <td width="50%">
      <p style="text-align: right;">FOR <b>M.A Abaya Manufacturer </b></p>
      <p>&nbsp;</p>
      <p style="text-align: right;"><br />AUTHORISED SIGNATURE</p>
    </td>
  </tr>
</table>

</body>
</html>