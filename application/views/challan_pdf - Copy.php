<div>
  <img src="<?php echo base_url('assets/images/labbaik_challan.jpg');?>">
  <h4 style="text-align: center;">DELIVERY CHALLAN</h4>
</div>
<table>
  <tr >
    <td width ="75%">DATE:&nbsp;&nbsp;&nbsp;<?php echo date('d F, Y');?></td>
    <td width = "25%">Challan No.&nbsp;&nbsp;&nbsp;<?php echo $challan_no;?></td>
  </tr>
  <tr><td colspan="2">&nbsp;</td></tr>
  <tr>
    <td width= "100%;">M/S.&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $customer;?> </td>
  </tr>
  <tr><td colspan="2">&nbsp;</td></tr>
  <tr>
    <td rowspan="2" width= "100%;">ADDS.&nbsp;&nbsp;&nbsp;<?php echo $customer_address; ?></td>
  </tr>
</table>

<div><br /></div>

        
<table border="1" cellspacing="0" cellpadding="3" width="100%">
  <tr>
    <th width= "10%">Sr. No</th>
    <th width= "45%">PARTICULARS</th>
    <th width= "15%">QNTY @kg</th>
    <th width= "15%">RATE</th>
    <th width= "15%">AMOUNT</th>
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
  if($all_items < 5)
  {
    for($i=0; $i < $all_items; $i++) {?>             
      <tr>
        <td><?php echo $j; ?></td>
        <td><?php echo $items2[$i][0]; ?></td>
        <td><?php echo $items2[$i][1]; ?></td>
        <td>Rs. <?php echo $items2[$i][2]; ?></td>
        <td>Rs. <?php echo $items2[$i][3]; ?></td>
      </tr>    
    <?php  $j++; } ?>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>   
    <?php } else { ?>                                         
    <?php for($i=0; $i < $all_items; $i++) { ?>             
    <tr>
      <td><?php echo $j; ?></td>
      <td><?php echo $items2[$i][0]; ?></td>
      <td><?php echo $items2[$i][1]; ?></td>
      <td>Rs. <?php echo $items2[$i][2]; ?></td>
      <td>Rs. <?php echo $items2[$i][3]; ?></td>
    </tr>    
  <?php  $j++; } }?>
</table>
        
       
<div>
  <p style="text-align: right;"><b>TOTAL</b>&nbsp;&nbsp;&nbsp;&nbsp;Rs. <?php echo $total; ?></p>
</div>
        
<div>                 
  <b>AMOUNT IN WORDS:</b>&nbsp;&nbsp;&nbsp;&nbsp;Rupees <?php echo $total_in_words; ?> Only
  <br />
</div>
          
<div>
  <b>Note:</b> This receipt should be signed by the person having the authority. No complaints will be entertained if the same are received after 24 hours of the delivery.
</div>

<div>&nbsp;<br /><br /></div>
<div>
  <p style="width: 70%; text-align: right;">FOR <b>LABBAIK ENTERPRISES</b></p>
  <p style="width: 70%; text-align: right;"><br />AUTHORISED SIGNATURE</p>
</div>
