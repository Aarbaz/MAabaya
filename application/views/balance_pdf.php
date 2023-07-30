<html>
<head>
  <title>Invoice Pices</title>
  <style type="text/css">
    .center {text-align: center;}
    /*table.hdr tr {padding: 10px;}*/
   /*  table.hdr tr td {background-color: #e1e1e9; padding: 10px} */
    #tax_table tr th.right{ text-align: right; }
    /*table td#adds {text-align: justify; } */
    .bold{
      font-weight:bold;
    }
  </style>
</head>
<body>

  <div>
    <img src="<?php echo base_url('assets/images/MAabaya-logo.png');?>">
  </div>

  <table  style="width: 100%">
    <tr><td colspan="4">&nbsp;</td></tr>
    <tr style="border-bottom:1px solid #eee;">
      <td width="20%"> Master Name: </td>
      <td width="20%"> <?php echo $cust_name?> </td>
    </tr>
    <tr style="border-bottom: 2px solid #000;">
      <td> INVOICE DATE </td>
      <!-- <td> <?php echo date('d F, Y'); ?> </td> -->
      <td> <?php echo $date_range; ?> </td>
    </tr>
  
    <tr><td colspan="4">&nbsp;</td></tr>
  </table>
  <div><br /></div>

<table border="1" cellspacing="0" cellpadding="3" width="100%">
  <tr style="background-color: #e1e1e9">
    <th style="width: 10%">Sr. No</th>
    <th style="width: 15%">INOVICE NO</th>
    <th style="width: 20%">BILL TOTAL</th>
    <th style="width: 20%">LAST AMOUNT</th>
    <th style="width: 15%">BALANCE</th>
    <th style="width: 15%">DATE</th>
  </tr>
  <tbody>
    <?php
    $i = 1;
    foreach ($data_pdf as $row) { 
      // $date_only = date('Y-m-d', strtotime($row['dated']));
      $date_only = date_create_from_format('Y-m-d H:i:s', $row['dated'])->format('d-m-Y');
      ?>
      <tr>
        <td><?php echo $i; ?></td>
        <td><?php echo $row['invoice']; ?></td>
        <td><?php echo $row['bill_amount']; ?></td>
        <td><?php echo $row['paid_amount']; ?></td>
        <td><?php echo $row['last_amount']; ?></td>
        <td><?php echo $date_only; ?></td>
      </tr>
    <?php $i++;}?>
  </tbody>
        
        <?php 

       ?>
</table>


<div>

    </div>

</body>
</html>
