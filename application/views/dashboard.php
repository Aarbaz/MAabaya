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

			   			<div class="col-sm-3">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <b>Total Purchaser</b>
                  </div>
                  <div class="panel-body">
                    <h2 class="text-center text-primary"><?php echo $pur_customer; ?> </h2>
                  </div>
                  </div>
			   			</div>
              <div class="col-sm-3">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <b>Total Maker</b>
                  </div>
                  <div class="panel-body">
                    <h2 class="text-center text-primary"><?php echo $mak_customer; ?> </h2>
                  </div>
                  </div>
			   			</div>
              <div class="col-sm-3">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <b>Seller Customer</b>
                  </div>
                  <div class="panel-body">
                    <h2 class="text-center text-primary"><?php echo $sel_customer; ?> </h2>
                  </div>
                  </div>
			   			</div>
              <!-- <div class="col-sm-2">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <b>Total Customers</b>
                  </div>
                  <div class="panel-body">
                    <h2 class="text-center text-primary"><?php echo $customer_count; ?> </h2>
                  </div>
                  </div>
               </div> -->
			   			<div class="col-sm-3">
							<div class="panel panel-default">
								<div class="panel-heading">
									<b>Total Material</b>
								</div>
								<div class="panel-body">
									<h2 class="text-center text-primary"><?php echo $all_material; ?> </h2>
								</div>
			   				</div>
			   			</div>

			   		</div>

            <div class="row">
			   			<div class="col-sm-12">
    						<div class="panel panel-default">
								<div class="panel-heading"><b>Purchaser wise Total Order Value</b></div>
								
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
