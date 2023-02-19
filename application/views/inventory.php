<div class="container-fluid" id="bg-color">
</div>

<div class="container-fluid">
    <div class="row">
    	<div class="col-sm-12"><br /></div>
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				   	<h4>Welcome to M.A Abaya <span class=" pull-right"><a href="<?php echo base_url('/index.php/dashboard/logout');?>" class="">Log Out</a></span></h4>
				</div>
			    <div class="panel-body">
			   		<div class="row">
					   <div class="col-sm-4">
               <div class="panel panel-default">
                 <div class="panel-heading">
                   <b>Total Customers</b>
                 </div>
                 <div class="panel-body">
                   <h2 class="text-center text-primary"><?php echo $customer_count; ?> </h2>
                 </div>
                 </div>
							</div>
			   			<div class="col-sm-4">


                <div class="panel panel-default">
                  <div class="panel-heading">
                    <b>Total Purchaser</b>
                  </div>
                  <div class="panel-body">
                    <h2 class="text-center text-primary"><?php echo $purchaser_count; ?> </h2>
                  </div>
                  </div>

			   			</div>
			   			<div class="col-sm-4">
							<div class="panel panel-default">
								<div class="panel-heading">
									<b>Total Products</b>
								</div>
								<div class="panel-body">
									<h2 class="text-center text-primary"><?php echo $product_count; ?> </h2>
								</div>
			   				</div>
			   			</div>
						   <!-- <div class="col-sm-3">
							</div> -->
			   			<!-- <div class="col-sm-3">
							<div class="panel panel-default">
								<div class="panel-heading">
									<b>Total Materials</b>
								</div>
								<div class="panel-body">
									<h2 class="text-center text-primary"><?php echo $material_count; ?> </h2>
								</div>
			   				</div>
			   			</div> -->
			   			<!-- <div class="col-sm-3">
							<div class="panel panel-default">
								<div class="panel-heading">
									<b>Total Vendors</b>
								</div>
								<div class="panel-body">
									<h2 class="text-center text-primary"><?php echo $vendor_count; ?> </h2>
								</div>
			   				</div>
			   			</div>			   						   						   			 -->
			   		</div>
			   		<!-- <div class="row">
			   			<div class="col-sm-12">
    						<div class="panel panel-default">
								<div class="panel-heading"><b>Customer wise Total Order Value</b></div>
								<div class="panel-body">
									<table id="order_sum" class="table table-bordered">
										<tr class="header">
											<th>Sr No</th><th>Customer Name</th><th>Total Order (In Rs.)</th>
											<th>Paid (In Rs.)</th><th>Balance (In Rs.)</th><th>Last Aamount (In Rs.)</th>
										</tr>
										<?php
										if(count($order_sum) > 0)
										{
											$i = 1;
											foreach ($order_sum as $order)
											{
												echo '<tr>
														<td>'.$i.'</td>
														<td>'.$order->bakery_name.'</td>
														<td class="total">'.$order->total.'</td>
														<td class="paid">'.$order->paid.'</td>
														<td class="balance">'.$order->balance.'</td>
														<td class="last_amount">'.$order->last_amount.'</td>
													</tr>';
													$i++;
											}
										} ?>

									</table>
								</div>
							</div>
						</div>
    				</div> -->

            <div class="row">
			   			<div class="col-sm-12">
    						<div class="panel panel-default">
								<div class="panel-heading"><b>Purchaser wise Total Order Value</b></div>
								<div class="panel-body">
									<table id="order_sum" class="table table-bordered">
										<tr class="header">
											<th>Sr No</th><th>Purchaser Name</th><th>Total Order (In Rs.)</th>
											<th>Paid (In Rs.)</th><th>Balance (In Rs.)</th><th>Last Aamount (In Rs.)</th>
										</tr>
										<?php
										if(count($order_sum) > 0)
										{
											$i = 1;
											foreach ($order_sum as $order)
											{
												echo '<tr>
														<td>'.$i.'</td>
														<td>'.$order->bakery_name.'</td>
														<td class="total">'.$order->total.'</td>
														<td class="paid">'.$order->paid.'</td>
														<td class="balance">'.$order->balance.'</td>
														<td class="last_amount">'.$order->last_amount.'</td>
													</tr>';
													$i++;
											}
										} ?>

									</table>
								</div>
							</div>
						</div>
    				</div>
			    </div>
			    <div class="panel-footer">
			    	<p class="text-right">For M.A Abaya </p>
			    </div>
    		</div>
    	</div>
	</div>
</div>



</div><!--close main div-->
<script type="text/javascript">
$(document).ready(function(){
	var tbl = $('#order_sum tr').not('.header');
	var total_sum = 0;
	var total_paid = 0;
	var total_bal = 0;
	var total_last = 0;

	$.each(tbl, function(){
		var total = $(this).find('.total').text();
		total =     parseFloat(total);
		total_sum+= total;

		var paid = $(this).find('.paid').text();
		paid =     parseFloat(paid);
		total_paid+= paid;

		var total_balance = $(this).find('.balance').text();
		total_balance =     parseFloat(total_balance);
		total_bal+= total_balance;

		var last_amount = $(this).find('.last_amount').text();
		last_amount =     parseFloat(last_amount);
		total_last+= last_amount;

	})

	var new_tr = '<tr><td></td><td><b>Total</b></td><td><b>'+total_sum+'</b></td><td><b>'+total_paid+'</b></td><td><b>'+total_bal+'</b></td><td><b>'+total_last+'</td></tr>';
	$('#order_sum').append(new_tr);

});
</script>
