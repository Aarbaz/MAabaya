<html>
<head>
  <title>Balance PDF</title>
  <style type="text/css">
    .center {text-align: center;}
    /*table.hdr tr {padding: 10px;}*/
    /* table.hdr tr td {background-color: #e1e1e9; padding: 10px} */
    #tax_table tr th.right{ text-align: right; }
    /*table td#adds {text-align: justify; } */
  </style>
</head>
<body>

  <div>
    <img src="<?php echo base_url('assets/images/MAabaya-logo.png');?>">
  </div>

  <table class="hdr" style="width: 100%">
    <tr><td colspan="4">&nbsp;</td></tr>
    <tr>
      <td width="20%">Name </td>
      <td width="35%">
        <b><?php echo $customer; ?></b>
      </td>
      <td width="20%"> Balance No.</td>
      <td width="25%">
      <b>  <?php echo $invoice; ?></b>
      </td>
    </tr>
    <tr>
      <td>
        <!-- ADDS.  -->
      </td>
      <td id="adds">
        <!-- <?php echo $customer_address; ?> -->
      </td>
      <td> Balance Date </td>
      <td> <b><?php echo date('d F, Y'); ?></b> </td>
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
    <th style="width: 25%"><b>Sr. No</b></th>
    <!-- <th style="width: 10%">Design No</th> -->
    <th style="width: 25%"><b>Amount</b></th>
    <th style="width: 25%"><b>Paid</b></th>
    <th style="width: 25%"><b>Balance</b></th>



  </tr>
  <?php
    // $mat = explode(',', $product_name);
    // $hsn = explode(',', $hsn);
    $hsn = '12';
    $qnty = explode(',', $bill_amount);
    $rate = explode(',', $paid_amount);
    $amount = explode(',', $last_amount);
    // $items = array('mat'=> $mat,'qnty'=>$qnty,'hsn'=>$hsn,'rate' => $rate, 'amount' => $amount);
    $items = array('qnty'=>$qnty,'rate' => $rate, 'amount' => $amount);
    $len = count($items['qnty']);

    $items2 = array();
    for ($i=0; $i < $len; $i++)
    {
      $newArray = array();
      // $newArray[] = $items['hsn'][$i];
      $newArray[] = $items['qnty'][$i];
      $newArray[] = $items['rate'][$i];
      $newArray[] = $items['amount'][$i];
      $items2[] = $newArray;
    }
    $Final_total_amt = $items['amount'];
    $Final_total_amt = array_sum($Final_total_amt);

    $Final_total_qty = $items['qnty'];
    $Final_total_qty = array_sum($Final_total_qty);

    $j = 1;
    $all_items = count($items2);

    $items2 = array_filter(array_map('array_filter', $items2));
    if($all_items > 0)
    {
      for($i=0;$i<1;$i++){?>
        <tr>
          <td><?php echo $j; ?></td>
          <td><?php echo isset($items2[$i][0])?$items2[$i][0]:''; ?></td>
          <!-- <td>-</td> -->
          <td><?php echo isset($items2[$i][1])?$items2[$i][1]:''; ?></td>
          <td><?php echo isset($items2[$i][2]) ? $items2[$i][2] : '' ; ?></td>
          <!-- <td><?php echo isset($items2[$i][4]) ? $items2[$i][4] : '' ; ?></td> -->
          <!-- <td><?php echo $items2[$i][4] ? 'Rs. '.$items2[$i][4] : '' ; ?></td> -->
        </tr>
      <?php
      // $new_var = array_sum($items2[$i][3]);
      // echo   $new_var;
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
  <!-- <tr>
    <th class="right">TRANSPORT CHARGES</th>
    <td>&nbsp;</td>
    <td>&nbsp;<?php echo $transport_charges ? 'Rs. '. $transport_charges : ''; ?></td>
  </tr>
  <tr>
    <th class="right">OTHER</th>
    <td>&nbsp;</td>
    <td> <?php echo $other_charge ? 'Rs. '. $other_charge : ''; ?> </td>
  </tr> -->
  <!-- <tr>
    <th class="right">TOTAL TAXABLE VALUE</th>
    <td>&nbsp;</td>
    <td> <?php echo 'Rs. '. $total_taxable_amount; ?> </td>
  </tr> -->

  <!-- <?php if($cgst_2_5_cent) {?>
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
  <?php } ?> -->
  <!-- <tr>
    <th ><b>Total Quantity</b></th>
    <td>&nbsp;</td>
    <td > <?php echo  $Final_total_qty . ' Meters. '?></td>
  </tr>
  <br>
  <tr>
    <th class="right"><b>Total Amount</b></th>
    <td>&nbsp;</td>
    <td> <?php echo $Final_total_amt .' Rs.' ?></td>
  </tr> -->


  <!-- <tr>
    <th  class="right">ROUND OFF TOTAL</th>
    <td>&nbsp;</td>
    <td> <?php echo 'Rs. '. $round_off_total; ?></td>
  </tr> -->
</table>

<div>

    </div>

</body>
</html>
