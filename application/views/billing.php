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
                        M/S KGN ABAYA STORE<br>
                        Vill. Boxivita, P.O. Khunia, Chopra, Uttar Dinajpur, West Bengal, 733207<br>
                        GST NO. 19FFHPR2596H1ZQ</p>
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
                                <input type="text" class="form-control" id="date" name="date" value="09.07.2024" readonly></p>
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
                            <b>CGST 2.5 % : </b>&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                        <div class="col-lg-10">
                                <input type="text" id="cgst" name="cgst" readonly>
                        </div>
                    </div>
                    <div class="row my-2">

                        <div class="col-lg-2">
                            <b>SGST 2.5 % : </b>&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                        <div class="col-lg-10">
                                <input type="text" id="sgst" name="sgst" readonly>
                        </div>
                    </div>
                    <div class="row my-2">

                        <div class="col-lg-2">
                            <b>IGST 5 % : </b>&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                        <div class="col-lg-10">
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
        $(document).ready(function() {
            // $('#generatePDFBtn').on('click', function() {
            //     // Serialize form data
            //     var formData = $('#invoiceForm').serializeArray();
                
            //     // Send AJAX request
            //     $.ajax({
            //         url: 'generate_pdf.php',
            //         type: 'POST',
            //         data: formData,
            //         dataType: 'json', // Expect JSON response from server
            //         success: function(response) {
            //             // Handle success, typically you might redirect or show a success message
            //             console.log('PDF generated successfully:', response);
            //             alert('PDF generated successfully. Check your downloads.');
            //         },
            //         error: function(xhr, status, error) {
            //             // Handle error
            //             console.error('Error:', error);
            //             alert('Error generating PDF. Please try again.');
            //         }
            //     });
            // });
        });
    </script>