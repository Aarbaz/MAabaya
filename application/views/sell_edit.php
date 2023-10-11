<div class="container-fluid" id="bg-color"><br /></div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>
                        <?php echo ucwords($username) . ', '; ?><small>
                            <?php echo date('d F, Y'); ?>
                        </small><span class="text-sm pull-right"><a href="<?php echo site_url('Invoice/logout'); ?>">Log
                                Out</a></span>
                    </h4>
                </div>

                <div class="panel-body">
                    <div class="challan-div">
                        <!-- <form id="insider_invoice_form" name="insider_invoice_form" class="form-horizontal"
                            action="<?php echo site_url('Invoice/edit_sell_data'); ?>" method="post"> -->
                            <?php
                            // print_r($sell_stock->result()[0]->sr_no);
                            $url = 'Invoice/edit_sell_data/' . $sell_stock->result()[0]->sr_no;
                            echo form_open($url, 'class="form-horizontal" id="insider_invoice_form" name="insider_invoice_form"');
                            ?>
                            <div class="form-group">
                                <h3 class="text-center">TAX INVOICE</h3>
                            </div>
                            <div class="col-sm-5 leftbox">
                                <div class="form-group ">
                                    <label class="control-label col-sm-3">To</label>
                                    <div class="col-sm-6">
                                        <select name="customerName" id="customerName" class="form-control">
                                            <option value="" selected="selected">--select customer--</option>
                                            <?php
                                            // print_r($sell_stock->result());
                                            // First, loop through the first array
                                            $customerIds = [];
                                            foreach ($sell_stock->result() as $rows) {
                                                $customerIds[] = $rows->customer_id;
                                            }

                                            // Then, loop through the second array and generate the options with comparisons
                                            foreach ($custList->result() as $row) {
                                                $selected = (in_array($row->id, $customerIds)) ? 'selected' : ''; // Check if the ID exists in the first array
                                                echo '<option value="' . $row->id . '" ' . set_select('customerName', $row->id) . ' ' . $selected . '>' . $row->name . '</option>';
                                            }
                                            ?>

                                            <input type="hidden" name="cust_adds"
                                                value="<?php echo set_value('cust_adds'); ?>" id="cust_adds">
                                            <input type="hidden" name="cust_name"
                                                value="<?php echo set_value('cust_name'); ?>" id="cust_name">
                                            <!-- <option value="other">Other</option> -->
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <a class="btn btn-default" role="button"
                                            href="<?php echo base_url('/index.php/Customer/add_new'); ?>">Add
                                            Customer</a>
                                    </div>
                                </div>
                                <div class="form-group hide">
                                    <label class="control-label col-sm-3">Customer Name</label>
                                    <div class="col-sm-9" id="addds_holder">
                                        <input type="text" name="customerName1" id="customerName" class="form-control"
                                            value="<?php echo set_value('customerName'); ?>">
                                        <!-- <input type="hidden" name="cust_adds" value="<?php echo set_value('cust_adds'); ?>" id="cust_adds">
                                        <input type="hidden" name="cust_name" value="<?php echo set_value('cust_name'); ?>" id="cust_name">  -->
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-3">Address</label>
                                    <div class="col-sm-9" id="addds_holder">
                                        <?php
                                        // print_r($sell_stock->result());
                                        ?>
                                        <input type="text" name="cust_adds_txt" id="cust_adds_txt" class="form-control"
                                            value="<?php echo isset($sell_stock) && is_array($sell_stock->result()) && isset($sell_stock->result()[0]->customer_address) ? $sell_stock->result()[0]->customer_address : ''; ?>">
                                        <input type="hidden" name="cust_adds"
                                            value="<?php echo set_value('cust_adds'); ?>" id="cust_adds">
                                        <input type="hidden" name="cust_name1"
                                            value="<?php echo set_value('cust_name'); ?>" id="cust_name1">
                                    </div>
                                </div>
                                <div class="form-group hide">
                                    <label class="control-label col-sm-3">Buyer's GST</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="cust_gst_txt" id="cust_gst_txt" class="form-control "
                                            value="<?php echo set_value('cust_gst_txt'); ?>" readonly="readonly">
                                        <input type="hidden" name="cust_gst"
                                            value="<?php echo set_value('cust_gst'); ?>" id="cust_gst">
                                    </div>
                                </div>
                                <div class="form-group hide">
                                    <label class="control-label col-sm-3">Select Region</label>
                                    <div class="col-sm-9">
                                        <select id="region" name="region" class="form-control">
                                            <option value="">--select--</option>
                                            <option value="in" <?php echo set_select('region', 'in'); ?>>In Maharashtra
                                            </option>
                                            <option value="out" <?php echo set_select('region', 'out'); ?>>Out of
                                                Maharashtra</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group hide">
                                    <label class="control-label col-sm-3">Invoice Type</label>
                                    <div class="col-sm-9">
                                        <select id="amount_with" name="amount_with" class="form-control">
                                            <option value="">--select--</option>
                                            <option value="with" <?php echo set_select('amount_with', 'with'); ?>>Amount
                                                With GST</option>
                                            <option value="without" <?php echo set_select('amount_with', 'without'); ?>>
                                                Amount Without GST</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-1">&nbsp;</div>
                            <div class="col-sm-6 leftbox inv">
                                <div class="form-group">
                                    <label class="control-label col-sm-4">INVOICE NO.</label>
                                    <div class="col-sm-8">&nbsp;&nbsp;

                                        <b>
                                            <?php echo isset($sell_stock) && is_array($sell_stock->result()) && isset($sell_stock->result()[0]->invoice_no) ? $sell_stock->result()[0]->invoice_no : ''; ?>
                                        </b>
                                        <input type="hidden" name="invoice_no"
                                            value="<?php echo isset($sell_stock) && is_array($sell_stock->result()) && isset($sell_stock->result()[0]->invoice_no) ? $sell_stock->result()[0]->invoice_no : ''; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">INVOICE DATE</label>
                                    <!-- <div class="col-sm-8">&nbsp;&nbsp;&nbsp;<?php echo date('d/m/Y'); ?></div> -->
                                    <div class="col-sm-4"><input type="date" id="bill_date" name="bill_date"
                                            value="<?php echo $sell_stock->result()[0]->invoice_date; ?>" /></div>
                                </div>
                                <div class="form-group hide">
                                    <label class="control-label col-sm-4">DATE OF SUPPLY</label>
                                    <div class="col-sm-8">&nbsp;<input type="date" name="sup_date"
                                            value="<?php echo set_value('sup_date'); ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group hide">
                                    <label class="control-label col-sm-4">PLACE OF SUPPLY</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="sup_place"
                                            value="<?php echo set_value('sup_place'); ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group hide">
                                    <label class="control-label col-sm-4">OTHER</label>
                                    <div class="col-sm-8"><input type="text" name="sup_other"
                                            value="<?php echo set_value('sup_other'); ?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">&nbsp;</div>
                            <div class="form-group"><br />
                                <div class="col-sm-8 col-sm-offset-2">
                                    <?php echo validation_errors('<p class="text text-danger">', '</p>'); ?>
                                </div>
                            </div>

                            <div class="form-group"><br />
                                <div class="col-sm-8 col-sm-offset-2">
                                    <?php
                                    if ($this->session->flashdata('pass')) {
                                        echo '<div class="alert alert-success alert-block successMsg"> ';
                                        echo $this->session->flashdata('pass');
                                        echo '</div>';
                                    } else if ($this->session->flashdata('fail')) {
                                        echo '<div class = "alert alert-warning successMsg">';
                                        echo $this->session->flashdata('fail');
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                           
                            <!--table withouot tax-->
                            <div class="form-group" id="table_without_tax">
                                <table class="table table-bordere<th>PRODUCT</th>d">
                                    <thead>
                                        <tr>
                                            <th>DESIGN NO</th>
                                            <!--  -->
                                            <th> STOCK</th>
                                            <th>QNTY</th>
                                            <th>AMOUNT</th>
                                            <th>TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody id="rows-list-quantity">
                                        <?php
                                        // print_r($sell_stock->result());
                                        
                                        // Loop through the data
                                        foreach ($sell_stock->result() as $item) {

                                            $hsnArray = explode(',', $item->hsn);
                                            $qntyArray = explode(',', $item->qnty);
                                            $rateArray = explode(',', $item->rate);
                                            $amountArray = explode(',', $item->amount);
                                            $cnt = count($hsnArray);
                                            // print_r($cnt);

                                            // Iterate through each HSN and its corresponding quantity and rate
                                            for ($i = 0; $i < $cnt; $i++) {
                                                $hsn = trim($hsnArray[$i]);
                                                $qnty = trim($qntyArray[$i]);
                                                $rate = trim($rateArray[$i]);
                                                $amount = trim($amountArray[$i]);
                                                ?>
                                                <tr class="row_one">
                                                    <td class="">
                                                        <select name="hsn[]" id="hsn"
                                                            class="form-control my-select check_design_stock">
                                                            <option value="">--select design no--</option>
                                                            <?php foreach ($designs->result() as $row) {
                                                                $selected = set_select("hsn[]", $row->design_num);
                                                                $data_id = $row->id;
                                                                if ($hsn) {
                                                                    $selecteds = ($hsn == $row->design_num) ? 'selected' : '';
                                                                }
                                                                echo '<option ' . $selecteds . ' data-id="' . $row->id . '" value="' . $row->design_num . '" ' . set_select("hsn[]", $row->design_num) . '>' . $row->design_num . '</option>';

                                                            } ?>
                                                        </select>
                                                        <input type="hidden" name="selected_ids[]" id="selected_ids" value="">
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $this->db->select('*');
                                                        $this->db->from('designs');
                                                        $this->db->where('design_num', $hsn);
                                                        $query = $this->db->get();
                                                        $design_number_result = $query->result();
                                                        $design_id = $design_number_result[0]->id;
                                                        $get_pstock = $this->Stock_model->get_allstock($design_id);
                                                        ?>
                                                        <input type="text" class="form-control stock_quantity"
                                                            id="stock_quantity" name="stock_quantity[]"
                                                            placeholder="Stock/Quantity"
                                                            value="<?php echo $get_pstock->stock_qty; ?>" readonly>
                                                        <?php
                                                        ?>
                                                    </td>
                                                    <td class="hide">
                                                        <select name="items[]" id="items" class="form-control">
                                                            <option value="">--select product--</option>
                                                            <?php foreach ($productList->result() as $row) {
                                                                echo '<option label="" value="' . $row->product_name . '" ' . set_select("items[]", $row->product_name) . '>' . $row->product_name . '</option>';
                                                            } ?>
                                                        </select>
                                                    </td>

                                                    <td><input type="text" name="qnty[]" class="qnty form-control" size="3"
                                                            maxlength="7" value="<?php echo $qnty; ?>"></td>
                                                    <td><input type="text" name="rate[]" class="rate form-control" size="3"
                                                            maxlength="7" value="<?php echo $rate; ?>"></td>
                                                    <td>
                                                        <input type="text" name="amount[]" class="amount form-control"
                                                            style=" width: 40%; display: inline;" value="<?php echo $amount; ?>"
                                                            size="3" readonly="readonly">&nbsp;
                                                        <button type="button" name="add_more" id="add_more"
                                                            class="add_more btn btn-success btn-sm"><b>+</b></button>
                                                        &nbsp;<button type="button" name="remove" id="remove"
                                                            class="btn btn-warning btn-sm remove"><b>X</b></button>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                            <!--ends here-->
                            <?php
                            $inv_no = isset($sell_stock) && is_array($sell_stock->result()) && isset($sell_stock->result()[0]->invoice_no) ? $sell_stock->result()[0]->invoice_no : '';
                            //   print_r($inv_no);
                            $get_ledger_invoice = $this->Balance_model->get_bal_user_bill($inv_no);
                            //   print_r($get_ledger_invoice);
                            
                            ?>
                            <div class="form-group">
                                <div class="col-sm-2 col-sm-offset-6">
                                    <b>TOTAL AMOUNT</b>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="total_amount" id="total_amount" readonly="readonly"
                                        class="total form-control" style="display: inline; width: 50%"
                                        value="<?php echo $get_ledger_invoice->bill_amount; ?>" size="3">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2 col-sm-offset-6">
                                    <b>ROUND OFF TOTAL</b>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="total_round" id="" readonly="readonly"
                                        class="total_round form-control" style="display: inline; width: 50%"
                                        value="" size="3">
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="col-sm-2 col-sm-offset-6">
                                    <b>PAID AMOUNT</b>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="paid_amount" class="form-control only_num paid_amount"
                                        id="paid_amount" style="display: inline; width: 50%"
                                        value="<?php echo $get_ledger_invoice->paid_amount; ?>" size="3">
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="col-sm-2 col-sm-offset-6">
                                    <b>BALANCE AMOUNT</b>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="balance_amount"
                                        class="form-control only_num balance_amount" id="balance_amount"
                                        style="display: inline; width: 50%"
                                        value="<?php echo $get_ledger_invoice->last_amount; ?>" size="3">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <b>AMOUNT IN WORDS:</b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="total_word"
                                        id="total_word" class="form-control" style="display: inline; width: 50%"
                                        value="" size="3">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">&nbsp;</div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-1">
                                    <b>This receipt should be signed by the person having the authority. No complaints
                                        will be entertained if the same are received after 24 hours of the delivery.</b>
                                </div>
                                <div class="col-sm-3">
                                    <p class="text-right">FOR <b>M.A Abaya Manufacturer</b></p>
                                    <p class="text-right"><br />AUTHORISED SIGNATURE</p>
                                </div>
                                <div class="col-sm-2">&nbsp;</div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <button type="submit" name="add_challan" class="btn btn-primary submit-btn">SAVE &
                                        PRINT</button>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <button type="reset" name="reload" class="btn btn-primary">Reset</button>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>

                <div class="panel-footer">
                    <p class="text-right">for M.A Abaya Manufacturer</p>
                </div>
            </div>
        </div>
    </div>
</div>
</div><!--close main div-->
<script src="<?php echo base_url('assets/js/to_words.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript">

    $(document).ready(function () {
        var total = $(".total").val();
        
        total_round = Math.round(total);
        $(".total_round").val(total_round);
        
        var total_words = NumToWord(total_round);
        $("#total_word").val(total_words);


        // $('#table_with_tax').hide();
        // setTimeout(function () {
        //     $('.successMsg, .err_db').fadeIn().fadeOut().fadeIn().fadeOut('slow');
        // }, 3000);
        // $('#items, .qnty, .amount_with_tax, .rate, .amount, #trans_charge, #other_charge, #total_tax_value, #cgst_charge, #sgst_charge, #igst_charge, #total_amount, #total_round, #total_word').val('');
        // $('#table_with_tax').hide();
        // $('#table_without_tax').show();
        //set product rate on change
        $("select[name^='items']").on('change', function () {
            var prod_name = $(this).children("option:selected").attr("label");
            var ro = $(this).closest("tr");
            if (prod_name != '') {
                ro.find('.rate').val(prod_name);
            }
        });

        $('.balance_amount').on('focus', function () {
            // debugger;
            var total_amount = $('#total_amount').val();
            var total_round = $('#total_round').val();
            var paid_amount = $('#paid_amount').val();
            var the_amount = (total_amount - paid_amount).toFixed(2);
            var the_amounts = (total_amount - paid_amount);
            $(this).val(the_amount);
            // var total_words = NumToWord(total_round);
            // $('#total_word').val(total_words);

        });
        //show AMOUNT by qnty*rate
        $('.amount').on('focus', function () {
            // debugger;
            var ro = $(this).parents('tr');
            var qnty = ro.find('.qnty').val();
            var rate = ro.find('.rate').val();
            var the_amount = (qnty * rate).toFixed(2);
            $(this).val(the_amount);
        });
        $('.qnty, .rate').on('change', function () {
            var ro = $(this).closest('tr');
            var qnty = ro.find('.qnty').val();
            var rate = ro.find('.rate').val();

            if (qnty && rate) {
                var the_amount = (qnty * rate).toFixed(2);
                ro.find('.amount').val(the_amount);
            }
        });
        // add new row
        $(document).on('click', '.add_more', function () {
            $(this).closest('tr').clone(true).find(':input:not(".hsn")').val('').end().insertAfter($(this).closest('tr'));
        });
        //Remove table row
        $(document).on('click', '.remove', function () {
            var $tr = $(this).closest('tr');
            if ($tr.index() != '0') {
                $tr.remove();
            }
        });

        $(document).on('click', '.hsn', function () {
            $(this).val('1901');
        });

        $('#insider_invoice_form').validate({
            rules: {
                customerName: "required",
                'hsn[]': {
                    required: true,
                },
                'qnty[]': {
                    required: true,
                },
                'rate[]': {
                    required: true,
                },
                'amount[]': {
                    required: true,
                },
                total_amount: "required",
                total_round: "required",
                paid_amount: "required",
                balance_amount: "required",
                total_word: "required",
            }
        });

        //show IGSC OR CGST and SGST hilton
        $('#region').click(function () {
            var reg = $(this).val();
            if (reg == 'in') {
                $('.in_state').fadeIn('slow');
                $('.out_state').fadeOut('slow');
            }
            else {
                $('.out_state').fadeIn('slow');
                $('.in_state').fadeOut('slow');
            }
        });
        //add only NUMBER and .
        $('.only_num').on('keyup', function () {
            var val = $(this).val();
            if (isNaN(val)) {
                val = val.replace(/[^0-9\.]/g, '');
                if (val.split('.').length > 2)
                    val = val.replace(/\.+$/, "");
            }
            $(this).val(val);
        });

        //show customer address on customer name change event
        var cust_list = <?php echo json_encode($custList->result()); ?>;
        $("#customerName").on('change', function () {
            var cust_id = $("#customerName option:selected").val();

            for (var key = 0; key < cust_list.length; key++) {
                if (cust_list[key].id == cust_id) {
                    $("#cust_adds_txt").val(cust_list[key].address);
                    ;
                }
            }
        });

        $('#total_amount').on('focus', function () {
            total = 0;
            $('.amount').each(function () {
                if ($(this).val() !== '') {
                    var amt = $(this).val();
                    total += parseFloat(amt);
                }
            });
            var crnt_val = parseFloat(total);
            $(this).val(crnt_val.toFixed(2));

            $('.total_round').val(Math.round(crnt_val));
            //total in words
            var round_amount = $('.total_round').val();
            $('#paid_amount ').val('');
            $('#balance_amount ').val('');
            if (round_amount != null) {
                var total_words = NumToWord(round_amount);
                $("#total_word").val(total_words);
            }

        });
    })

</script>
<script type="text/javascript">
    $(function () {
        $("#ddlModels").change(function () {
            if ($(this).val() == 'other') {
                $("#txtOther").removeAttr("disabled");
                $("#txtOther").focus();
            } else {
                $("#txtOther").attr("disabled", "disabled");
            }
        });
    });
    $('.submit-btn').click(function () {
        var selected_ids = [];
        $('tr.row_one').each(function () {
            //var selectedValue = $(this).find('select').val();
            var otherAttribute = $(this).find('select option:selected').attr('data-id');
            console.log(' Other attribute value: ' + otherAttribute);
            selected_ids.push(otherAttribute);
            console.log(selected_ids);
        });
        $('#selected_ids').val(selected_ids.join(','));

    });


    var list = $("#rows-list-quantity");
    $(list).on('change', ".check_design_stock", function () {
        var row = $(this).closest('tr');
        var design_id = $(this).find(':selected').data('id');
        var baseURL = "<?php echo base_url(); ?>";
        $.ajax({
            type: 'post',
            url: '<?= base_url() ?>index.php/Invoice/StockById',
            data: { design_id: design_id },
        }).then(function (res) {
            var res = $.parseJSON(res);
            if (res || res != null) {
                row.find(".stock_quantity").val(res.stock_qty);
            } else {
                row.find(".stock_quantity").val(" ");
            }
        }, function () {
            alert("Sorry cannot get the stock details!");
        });
    });

</script>