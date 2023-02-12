<div style="width: 100%">
  <img src="<?php echo base_url('assets/images/labbaik_challan.jpg');?>">
  <h4 style="text-align: center;">DELIVERY CHALLAN</h4>
</div>
<table>
  <tr >
    <td width ="75%">Date:&nbsp;&nbsp;&nbsp;<?php echo date('d F, Y');?></td>
    <td width = "25%">Challan No.&nbsp;&nbsp;&nbsp;<?php echo $challan_no;?></td>
  </tr>
  <tr>
    <td colspan="2">M/S.&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $customer;?> </td>
  </tr>
  <tr>
    <td colspan="2">Adds.&nbsp;&nbsp;&nbsp;<?php echo $customer_address; ?></td>
  </tr>
</table>
<p>&nbsp;</p>
        
<table border="1" cellspacing="0" cellpadding="3" width="100%">
  <tr>
    <th width="10%">Sr. No</th>
    <th width="45%">PARTICULARS</th>
    <th width="15%">QNTY @kg</th>
    <th width="15%">RATE</th>
    <th width="15%">AMOUNT</th>
  </tr>
  <?php
  $mat = explode(',', $material);
  $qnty = explode(',', $qnty);
  $rate = explode(',', $rate);
  $amount = explode(',', $amount);   

  $items = array( 'mat'=> $mat, 'qnty' => $qnty,'rate' => $rate, 'amount' => $amount);              
  $len = count($items['mat']);                        

  $items2 = array();
  for ($i=0; $i < $len; $i++)
  { 
    $newArray = array();
    $newArray[] = $items['mat'][$i];
    $newArray[] = $items['qnty'][$i];
    $newArray[] = $items['rate'][$i];
    $newArray[] = $items['amount'][$i];    
    $items2[] = $newArray;             
  }    

  $j = 1;
  $all_items = count($items2);
  if($all_items > 0)
  {
    for($i=0; $i < $all_items; $i++) {?>             
      <tr>
        <td><?php echo $j; ?></td>
        <td><?php echo $items2[$i][0]; ?></td>
        <td><?php echo $items2[$i][1]; ?></td>
        <td>Rs. <?php echo $items2[$i][2]; ?></td>
        <td>Rs. <?php echo $items2[$i][3]; ?></td>
      </tr>    
    <?php  $j++; } }?>
</table>

<p style="text-align: right;"><b>TOTAL</b>&nbsp;&nbsp;&nbsp;&nbsp;Rs. <?php echo $total; ?></p>
        
<div>                 
  <b>AMOUNT IN WORDS:</b>&nbsp;&nbsp;&nbsp;&nbsp;Rupees <?php echo $total_in_words; ?> Only
</div>
<div>&nbsp;<br /></div>     
<table width="100%" border="0">
  <tr>
    <td width="50%">
      <b>Note:</b> This receipt should be signed by the person having the authority. No complaints will be entertained if the same are received after 24 hours of the delivery.</td>
    <td width="50%">
      <p style="text-align: right;">FOR <b>LABBAIK ENTERPRISES</b></p>
      <p>&nbsp;</p>
      <p style="text-align: right;"><br />AUTHORISED SIGNATURE</p>
    </td>
  </tr>
</table>

