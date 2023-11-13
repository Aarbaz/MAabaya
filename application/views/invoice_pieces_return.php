<html>
<head>
  <title>invoice pdf</title>
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

  <table class="hdr" style="width: 100%">
    <tr><td colspan="4">&nbsp;</td></tr>
    <tr>
      <td width="20%"> CUSTOMER NAME </td>
      <td width="35%"> <?php echo $customer; ?></td>
      <td width="20%"> INVOICE NO.</td>
      <td width="25%"> <?php echo $return_invoice_no; ?> </td>
      
    </tr>
  </table>

  <div><br /></div>

<table border="1" cellspacing="0" cellpadding="3" width="100%">
  <tr style="background-color: #e1e1e9">
    <th style="width: 10%">Sr. No</th>
    <th style="width: 40%">PARTICULARS</th>
    <!-- <th style="width: 10%">Design No</th> -->
    <th style="width: 10%">QNTY</th>

  </tr>
  <?php
    $mat = explode(',', $design_no);
    $qnty = explode(',', $qnty);
    $items = array('mat'=> $mat,'qnty'=>$qnty);
    $len = count($items['mat']);

    $items2 = array();
    for ($i=0; $i < $len; $i++)
    {
      $newArray = array();

      $newArray[] = $items['mat'][$i];
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
        </tr>
      <?php
        $j++; }
    } ?>
</table>
<div class="row" style="display:flex;">
</div>

</body>
</html>
