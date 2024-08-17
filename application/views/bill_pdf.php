        <h1 class="text-center" style="text-align:center; margin:0px;">Invoice</h1>

<div class="">
    <div class="row" style="display:flex; border: 1px solid; display:none;">
        <div class="col-lg-4" style="border: 1px solid; width: 40%;">
            <h1 class="text-center font-bold" style="text-align:center">M.A ABAYA</h1>
            <p>BEHIND NOOR MASJID HOUSE NO 1640 <br>
                NEAR SAGAR PLAZA HOTEL BHIWANDI</p>
            <p class="font-bold">
                GSTN : 27DGKPA3869J1Z0
            </p>
        </div>
        <div class="col-lg-4 text-center" style="border: 1px solid; border-left: 0; display: flex; align-items: center; justify-content: space-around; width: 25%;">
            <span>Invoice.No:- </span>
            <span><?php echo $invoiceNumber; ?> </span>
        </div>
    </div>
</div>

<div class="row">
    <table border="1" cellspacing="0" cellpadding="3" width="100%">
        <tr>
            <td rowspan="3" colspan="2">
                <h1 class="text-center"><?php echo $myShop_name?></h1>
                <p>
                    <?php echo $myShop_add?>
                </p>
                <p class="font-bold">
                    GSTN : <?php echo $myShop_gst?>
                </p>
            </td>
            <td colspan="2">Invoice.No:- <?php echo $invoiceNumber?></td>
            <td > Dated :- <?php echo $date ?></td>
        </tr>
        <tr>
            <td colspan="2"> Delivery Note.</td>
            <td colspan="2">Terms of Payment: <b></b></td>
        </tr>
        <tr>
            <td colspan="2"> Supplier's Ref.</td>
            <td colspan="2"> Other Reference{s}</td>
        </tr>
        <tr>
            <td rowspan="3" colspan="2">
                <p>Consignee</p>
                <!-- <h1 class="text-center">M/S KGN ABAYA STORE</h1> -->
                <h2>
                   <?php echo $consignee_name?>
                </h2>
                <p>
                   <?php echo $consignee_add?>
                </p>

                <p class="font-bold">
                    GSTN : <?php echo $consignee_gst?>
                </p>
            </td>
            <td colspan="2">Buyer's Order No. :</td>
            <td colspan="2"> Dated :- <?php echo  $consignee_date?></td>
        </tr>
        <tr>
            <td colspan="2"> Dispatch Document No.</td>
            <td colspan="2">Dated</td>
        </tr>
        <tr>
            <td colspan="2"> Dispatched through:</td>
            <td colspan="2"> Destination: </td>
        </tr>
    </table>
</div>

<table border="1" cellspacing="0" cellpadding="3" width="100%">
    <tr>
        <th width="10%">Sr. No</th>
        <th width="45%">Description Of Good</th>
        <th width="15%">QNTY</th>
        <th width="10%">RATE</th>
        <th width="10%">Discount</th>
        <th width="10%">AMOUNT</th>
    </tr>
    <tr>
        <td  rowspan="7">1</td>
        <td  rowspan="7"><?php echo $description ?></td>
        <td  rowspan="7"><?php echo $quantity ?></td>
        <td><?php echo $rate ?></td>
        <td><?php echo $discount ?></td>
        <td><?php echo $amount ?></td>
    </tr>
    <tr style="height:50px;">
        <td colspan="3" rowspan="2"></td>
    </tr>
    <tr>
        
        <td >Total</td>
        <td></td>
        <td><?php echo $total ?></td>
    </tr>
    <tr>
        
        <td >CGST</td>
        
        <td><?php echo $cgstchnage ?> %</td>
        <td><?php echo $cgst ?></td>
    </tr>
    <tr>
        
        <td >SGST</td>
        <td><?php echo $sgstchnage ?> %</td>
        <td><?php echo $sgst ?></td>
    </tr>
    <tr>
        
        <td >IGST</td>
        <td><?php echo $igstchnage ?> %</td>
        <td><?php echo $igst ?></td>
    </tr>
    <tr>
        
        <td colspan="2">Transportation</td>
        <td><?php echo $transportation?></td>
    </tr>
    <tr>
        <td></td>
        <td>Total</td>
        <td colspan="3"></td>
        <td><?php echo $grandTotal?></td>
    </tr>
</table>

<table border="1" cellspacing="0" cellpadding="3" width="100%">
    <tr>
        <td width="100%">
            <b>Declaration:</b> Certified that the particulars given above are true and correct and amount indicated represents the price actually charges 
            and that there is no flow of additional consideration directly or indirectly from the buyer. If there is any objection for goods 
            sold should be raised within 7 days from the date of execution of this invoice eals it shall be considered as accepted by you.
        </td>
    </tr>
    <tr>
        <td style="display:flex;">
            <div style="width: 50%;">
                <p style="margin:0px;"><span>Transport ID No :-</span> <?php echo $transport_id ?></p>
                <p style="margin:0px;"><span>L R No</span><?php echo $LR_number ?></p>
                <p style="margin:0px;"><span>To:-</span><?php echo $to_person ?></p>
                <p style="margin:0px;"><span>Transport:-</span><?php echo $Transport ?></p>
            </div>
            <div style="width: 50%; text-align: right;">
                <h4><b>For M.A ABAYA MANUFACTURE</b></h4>
                <p></p>
                <p></p>
                <p></p>
                <h4 style="margin-top: 75px;"><b>Auth. Signature</b></h4>
            </div>
        </td>
    </tr>
</table>