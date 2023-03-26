<html>
<head>
  <title>Invoice Pices</title>
  <style type="text/css">
    .center {text-align: center;}
    /*table.hdr tr {padding: 10px;}*/
   /*  table.hdr tr td {background-color: #e1e1e9; padding: 10px} */
    #tax_table tr th.right{ text-align: right; }
    /*table td#adds {text-align: justify; } */
  </style>
</head>
<body>

  <div>
    <img src="<?php echo base_url('assets/images/MAabaya-logo.png');?>">
  </div>

  <table  style="width: 100%">
    <tr><td colspan="4">&nbsp;</td></tr>
    <tr style="border-bottom:1px solid #eee;">
      <td width="20%"> Master Name </td>
      <td width="35%"> <?php echo $customer; ?></td>
      <td width="20%"> INVOICE NO.</td>
      <td width="25%"> <?php echo $invoice_no; ?> </td>
    </tr>
    <tr style="border-bottom: 2px solid #000;">
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
    <th style="width: 10%">Design No</th>
    <th style="width: 10%">QNTY</th>
    <th style="width: 15%">RATE</th>
    <th style="width: 15%">AMOUNT</th>
  </tr>
  <?php
    $mat = explode(',', $product_name);
    // $hsn = explode(',', $hsn);
    $qnty = explode(',', $qnty);
    $rate = "";
    $amount = " ";
    $items = array('mat'=> $mat,'qnty'=>$qnty,'hsn'=>$hsn);
    $len = count($items['mat']);

    $items2 = array();
    for ($i=0; $i < $len; $i++)
    {
      $newArray = array();
      $newArray[] = $items['mat'][$i];
      // $newArray[] = $items['hsn'][$i];
      $newArray[] = $items['qnty'][$i];
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

</table>
<div>

    </div>

</body>
</html>
