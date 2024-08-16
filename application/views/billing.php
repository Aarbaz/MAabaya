<div class="container">
	<h1 class="text-center">INVOICE</h1>
	<?php
	echo form_open('Billing/generate_bill', 'class="form-horizontal" id="invoiceForm"');
	?>
	<!-- <form id="invoiceForm"> -->
	<div class="row">
		<div class="col-lg-6">
			<h2 class="text-left">M.A ABAYA MANUFACTURE</h2>
			<p class="text-left">BEHIND NOOR MASJID HOUSE NO 1640<br>
				NEAR SAGAR PLAZA HOTEL BHIWANDI<br>
				GSTN : 27DGKPA3869J1Z0
			</p>
			<div>
				<p>Consignee,<br>
					<textarea name="Consignee" id="" cols="50" rows="5" class="form-control"></textarea>
			</div>

		</div>
		<div class="col-lg-6">
			<div class="row">
				<div class="form-group col-lg-5 mx-2">
					<div class="">
						<b>Invoice No: </b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="form-control" id="invoiceNumber" name="invoiceNumber" value="15" readonly>
					</div>
				</div>
				<div class="form-group col-lg-5">
					<div class="">
						<b>Dated: </b>&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="date" class="form-control" id="date" name="date" value=""></p>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<b>Buyer's Order No.: </b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="form-control" id="buyersOrderNo" name="buyersOrderNo">
			</div>
			<div class="col-lg-6">
				<b>Dispatched through: </b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="form-control" id="dispatchedThrough" name="dispatchedThrough">
			</div>
			<div class="col-lg-6">
				<b>Destination: </b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="form-control" id="destination" name="destination">
			</div>
		</div>
	</div>

	<div class="row">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Sr. No.</th>
					<th>Description Of Goods</th>
					<th>Quantity</th>
					<th>Rate</th>
					<th>Discount</th>
					<th>Amount</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>1</td>
					<td><input type="text" id="description" name="description" value="All ABAYA" readonly></td>
					<td><input type="number" id="quantity" name="quantity" value="4" oninput="calculateTotal()"></td>
					<td><input type="number" id="rate" name="rate" value="11058" oninput="calculateTotal()"></td>
					<td><input type="number" id="discount" name="discount" value="0" oninput="calculateTotal()"></td>
					<td><input type="text" id="amount" name="amount" readonly></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="totals">
		<div class="form-group">
			<label for="includeGst">Include GST</label>
			<input type="checkbox" class="col-lg-1" id="includeGst" name="includeGst" onchange="calculateTotal()">
		</div>
		<div class="row my-2">

			<div class="col-lg-2">
				<b>TOTAL: </b>&nbsp;&nbsp;&nbsp;&nbsp;
			</div>
			<div class="col-lg-10">
				<input type="text" id="total" name="total" readonly>
			</div>
		</div>
		<div class="row my-2">

			<div class="col-lg-2">
				<!-- <b>CGST 2.5 % : </b>&nbsp;&nbsp;&nbsp;&nbsp; -->
				<b>CGST <input type="number" style="width:25%" value="2.5" id="cgstchnage" onchange="calculateTotal()"> % : </b>
			</div>
			<div class=" col-lg-10">
				<input type="text" id="cgst" name="cgst" readonly>
			</div>
		</div>
		<div class="row my-2">

			<div class="col-lg-2">
				<!-- <b>SGST 2.5 % : </b>&nbsp;&nbsp;&nbsp;&nbsp; -->
				<b>SGST <input type="number" style="width:25%" value="2.5" id="sgstchnage" onchange="calculateTotal()"> % : </b>
			</div>
			<div class="col-lg-10">
				<input type="text" id="sgst" name="sgst" readonly>
			</div>
		</div>
		<div class="row my-2">

			<div class="col-lg-2">
				<!-- <b>IGST 5 % : </b>&nbsp;&nbsp;&nbsp;&nbsp; -->
				<b>IGST <input type="number" style="width:25%" value="5" id="igstchnage" onchange="calculateTotal()"> % : </b>
			</div>
			<div class=" col-lg-10">
				<input type="text" id="igst" name="igst" readonly>
			</div>
		</div>
		<div class="row my-2">

			<div class="col-lg-2">
				<b>Transportation: </b>&nbsp;&nbsp;&nbsp;&nbsp;
			</div>
			<div class="col-lg-10">
				<input type="number" id="transportation" name="transportation" value="0" oninput="calculateTotal()">
			</div>
		</div>
		<div class="row my-2">

			<div class="col-lg-2">
				<b>Total: </b>&nbsp;&nbsp;&nbsp;&nbsp;
			</div>
			<div class="col-lg-10">
				<input type="text" id="grandTotal" name="grandTotal" readonly>
			</div>
		</div>

	</div>

	<div class="row my-2">
		<p>Certified that the particulars given above are true and correct and amount indicated represents the price actually charged and that there is no flow of additional consideration directly or indirectly from the buyer. If there is any objection for goods sold should be raised within 7 days from the date of execution of this invoice else it shall be considered as accepted by you.</p>
	</div>

	<div class="row">
		<div class="col-lg-2">
			<p>Transport ID No: <input type="text" id="transportId" name="transportId"></p>
		</div>
		<div class="col-lg-2">
			<p>L R No: <input type="text" id="lrNo" name="lrNo"></p>
		</div>
		<div class="col-lg-2">
			<p>To: <input type="text" id="to" name="to"></p>
		</div>
		<div class="col-lg-2">
			<p>Transport: <input type="text" id="transport" name="transport"></p>
		</div>
		<div class="col-lg-4">
			<p>For M.A ABAYA MANUFACTURE</p>
			<p>Auth. Signature</p>
		</div>
	</div>
	<div class="row">
		<button type="submit" class="btn btn-block my-2 btn-primary" id="generatePDFBtn">Generate PDF</button>
	</div>
	<!-- <button type="button" onclick="generatePDF()">Generate PDF</button> -->
	<!-- </form> -->
	<?php echo form_close();  ?>
</div>




<script>
	function calculateTotal() {
		const quantity = parseFloat(document.getElementById("quantity").value);
		const rate = parseFloat(document.getElementById("rate").value);
		const discount = parseFloat(document.getElementById("discount").value);
		const transportation = parseFloat(
			document.getElementById("transportation").value
		);
		const includeGst = document.getElementById("includeGst").checked;



		let amount = quantity * rate - discount;


		var cgst = 0;
		var sgst = 0;
		var igst = 0;


		const cgstchnage = parseFloat(document.getElementById("cgstchnage").value);

		if (cgstchnage != '0') {
			cgst = cgstchnage;
		}
		const sgstchnage = parseFloat(document.getElementById("sgstchnage").value);

		if (sgstchnage != '0') {
			sgst = sgstchnage;
		}
		const igstchnage = parseFloat(document.getElementById("igstchnage").value);

		if (igstchnage != '0') {
			igst = igstchnage;
		}



		console.log(cgst);
		console.log(sgst);
		console.log(igst);
        var total = 0;
		if (includeGst) {
			// cgst = amount * 0.025;
			// sgst = amount * 0.025;
			// igst = amount * 0.05;
			cgst = (amount * cgst) / 100;
			sgst = (amount * sgst) / 100;
			igst = (amount * igst) / 100;
			
			document.getElementById("cgst").value = cgst.toFixed(2);
			document.getElementById("sgst").value = sgst.toFixed(2);
			document.getElementById("igst").value = igst.toFixed(2);
            total = amount + cgst + sgst + igst + transportation;
		} else {

			document.getElementById("cgst").value = 0;
			document.getElementById("sgst").value = 0;
			document.getElementById("igst").value = 0;

            total = amount + transportation;
		}


		document.getElementById("amount").value = amount.toFixed(2);

		document.getElementById("total").value = amount.toFixed(2);
		document.getElementById("grandTotal").value = total.toFixed(2);
	}
	// $(document).ready(function() {
	// });
</script>
