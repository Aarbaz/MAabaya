<div>
	<img src="<?php echo base_url('assets/images/labbaik-bill-logo.jpg');?>" class="img-responsive">
	<h4 style="text-align: center;">TAX INVOICE</h4>
</div>

<table>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td style="background-color: #e1e1e9; padding: 10px" width ="50%">
		TO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $customer_id; ?>
		<br />ADDS. &nbsp;&nbsp;&nbsp; <?php echo $customer_address; ?><br />
		 BUYER'S GST &nbsp;&nbsp;&nbsp; <?php echo $buyer_gst; ?>
		</td>
		<td style="background-color: #e1e1e9; padding: 10px" width = "50%">
		INVOICE NO. &nbsp;&nbsp;&nbsp; <?php echo $invoice_no; ?> <br />
		INVOICE DATE &nbsp;&nbsp;&nbsp; <?php echo date('d/m/y'); ?> <br />
		DATE OF SUPPLY &nbsp;&nbsp;&nbsp; <?php echo $date_of_supply; ?> <br />
		PLACE OF SUPPLY &nbsp;&nbsp;&nbsp; <?php echo $place_of_supply; ?> <br />
		OTHER &nbsp;&nbsp;&nbsp; <?php echo $other_notes; ?>
		</td>
	</tr>  
</table>

<div>&nbsp;<br /></div>

<table border="1" cellspacing="0" cellpadding="3" width="100%">         
	<tr style="background-color: #e1e1e9">
		<th style="width: 10%">Sr. No</th>
		<th style="width: 40%">PARTICULARS</th>
		<th style="width: 10%">HSN</th>
		<th style="width: 10%">QNTY</th>
		<th style="width: 15%">RATE</th>
		<th style="width: 15%">AMOUNT</th>
	</tr>
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
		$items2 = array_filter(array_map('array_filter', $items2));
		if($all_items < 5)
		{
            for($i=0;$i<count($items2);$i++)
			{
            ?>             
            <tr>
				<td><?php echo $j; ?></td>
				<td><?php echo $items2[$i][0]; ?></td>
				<td><?php echo $items2[$i][1]; ?></td>
				<td><?php echo $items2[$i][2] ? $items2[$i][2]. ' kg' : '' ; ?></td>
				<td><?php echo $items2[$i][3] ? 'Rs. '.$items2[$i][3] : '' ; ?></td>
				<td><?php echo $items2[$i][4] ? 'Rs. '.$items2[$i][4] : '' ; ?></td>
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
                <td><?php echo $items2[$i][2] ? $items2[$i][2]. 'kg' : '' ; ?></td>
                <td><?php echo $items2[$i][3] ? 'Rs. '.$items2[$i][3] : '' ; ?></td>
                <td><?php echo $items2[$i][4] ? 'Rs. '.$items2[$i][4] : '' ; ?></td>
            </tr>    
            <?php  $j++; } } ?>                                        
</table>
             
			 
<table width="100%">
	<tr>
        <td colspan="3">&nbsp;&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;&nbsp;</td>
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
    <?php if($cgst_2_5_cent) {?>
    <tr>
        <th style="text-align: right;">CGST @ 2.5%</th>
        <td>&nbsp;</td>
        <td> <?php echo 'Rs. '. $cgst_2_5_cent; ?> </td>
    </tr>
    <tr>
		<th style="text-align: right;">SGST @ 2.5%</th>
		<td>&nbsp;</td>
		<td> <?php echo 'Rs. '. $sgst_2_5_cent; ?> </td>
    </tr>
     <?php } ?>
	<?php if($igst_5_cent) { ?>
	<tr>
		<th style="text-align: right;">IGST @ 5%</th>
		<td>&nbsp;</td>
		<td> <?php echo 'Rs. '. $igst_5_cent; ?> </td>
	</tr> 
	<?php } ?>                                                         
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
<div>&nbsp;</div>
<div>             
    <b>AMOUNT IN WORDS:</b>&nbsp;&nbsp;&nbsp;&nbsp;Rupees <?php echo $total_in_words; ?> Only
</div>
<div>&nbsp;</div>
<div>
    <b>Note:</b> This receipt should be signed by the person having the authority. No complaints will be entertained if the same are received after 24 hours of the delivery.
</div>
<div>&nbsp;</div>
<div>
    <p style="width: 80%; text-align: right;">FOR <b>LABBAIK ENTERPRISES</b></p>
	<p style="width: 80%; text-align: right;"><br />AUTHORISED SIGNATURE</p>
</div>