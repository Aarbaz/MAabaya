<div class="container-fluid" id="bg-color"><br /></div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4><?php echo ucwords($username) . ', '; ?><small><?php echo date('d F, Y'); ?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Invoice/logout'); ?>">Log Out</a></span>
                    </h4>
                </div>

                <div class="panel-body">
                    <!-- <p>
                        <span class="btn btn-primary btn-sm" onclick="add_new_design()">Add New</span>
                    </p><br /> -->
                    <div class="challan-div">
                        <form id="pices_add_form" name="pices_add_form" class="form-horizontal pices_add_form" action="<?php echo site_url('Pices/create'); ?>" method="post">
                            <div class="form-group">
                                <h3 class="text-center">Pices Recived</h3>
                            </div>
                             <div class="col-sm-5 "><!--leftbox -->
                                <div class="form-group ">
                                    <label class="control-label col-sm-3">Master Name</label>
                                    <div class="col-sm-9">
                                          <!-- <select name="customerName" id="customerName" class="form-control">
                                              <option value="" selected="selected">--select customer--</option>
                                            <?php foreach ($custList->result() as $row) {
                                                echo '<option value="' . $row->id . '" ' . set_select('customerName', $row->id) . '>' . $row->material_name . '</option>';
                                            } ?>

                                            <input type="hidden" name="cust_adds" value="<?php echo set_value('cust_adds'); ?>" id="cust_adds">
                                        <input type="hidden" name="cust_name" value="<?php echo set_value('cust_name'); ?>" id="cust_name">
                                            <option value="other">Other</option>
                                          </select>  -->

                                          <select name="customerName" id="customerName" class="form-control">
                                            <option value="" selected="selected">--select master--</option>
                                            <?php
                                            // print_r($custList);
                                            foreach ($custList->result() as $row) {
                                                echo '<option value="' . $row->id . '" ' . set_select('ownerName', $row->name) . '>' . $row->name . '</option>';
                                            } ?>
                                            </select>
                                       </div>
                                    <div class="col-sm-2 hide">
                                    <a class="btn btn-default" role="button"  href="<?php echo base_url('/index.php/Customer/add_new'); ?>">Add Customer</a>
                                    </div>
                                </div>

                                <div class="form-group hide">
                                    <label class="control-label col-sm-3">Address</label>
                                    <div class="col-sm-9" id="addds_holder">
                                        <input type="text" name="cust_adds_txt" id="cust_adds_txt" class="form-control" value="<?php echo set_value('cust_adds_txt'); ?>">
                                        <input type="hidden" name="cust_adds" value="<?php echo set_value('cust_adds'); ?>" id="cust_adds">
                                        <input type="hidden" name="cust_name1" value="<?php echo set_value('cust_name'); ?>" id="cust_name1">
                                    </div>
                                </div>
                                <div class="form-group hide">
                                    <label class="control-label col-sm-3">Buyer's GST</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="cust_gst_txt" id="cust_gst_txt" class="form-control " value="<?php echo set_value('cust_gst_txt'); ?>" readonly="readonly">
                                        <input type="hidden" name="cust_gst" value="<?php echo set_value('cust_gst'); ?>" id="cust_gst">
                                       </div>
                                </div>
                                <div class="form-group hide">
                                    <label class="control-label col-sm-3">Select Region</label>
                                    <div class="col-sm-9">
                                        <select id="region" name="region" class="form-control">
                                            <option value="">--select--</option>
                                            <option value="in" <?php echo set_select('region', 'in'); ?>>In Maharashtra</option>
                                            <option value="out" <?php echo set_select('region', 'out'); ?>>Out of Maharashtra</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group hide">
                                    <label class="control-label col-sm-3">Invoice Type</label>
                                    <div class="col-sm-9">
                                        <select id="amount_with" name="amount_with" class="form-control">
                                            <option value="">--select--</option>
                                            <option value="with" <?php echo set_select('amount_with', 'with'); ?>>Amount With GST</option>
                                            <option value="without" <?php echo set_select('amount_with', 'without'); ?>>Amount Without GST</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-1">&nbsp;</div>
                            <div class="col-sm-6 leftbox inv hide">
                                <div class="form-group">
                                    <label class="control-label col-sm-4">INVOICE NO.</label>
                                    <div class="col-sm-8">&nbsp;&nbsp;
                                        <?php

                                        $invoice_no = '';

                                        if (!empty($last_invoice->invoice_no)) {
                                            $db_invoice = $last_invoice->invoice_no;
                                            $num_part = substr($db_invoice, 3);
                                            $add_one = intval($num_part) + 1;

                                            if (strlen($add_one) < 3) {
                                                $ch_no = sprintf("%03u", $add_one);
                                                $invoice_no = 'PIC' . $ch_no;
                                            } else {
                                                $invoice_no = 'PIC' . $add_one;
                                            }
                                        } else {
                                            $invoice_no = 'PIC001';
                                        }

                                        ?>
                                        <?php echo '<b>' . $invoice_no . '</b>'; ?>
                                        <input type="hidden" name="invoice_no" value="<?php echo $invoice_no; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">INVOICE DATE</label>
                                    <div class="col-sm-8">&nbsp;&nbsp;&nbsp;<?php echo date('d/m/Y'); ?></div>
                                </div>
                                <div class="form-group hide">
                                    <label class="control-label col-sm-4">DATE OF SUPPLY</label>
                                    <div class="col-sm-8">&nbsp;<input type="date" name="sup_date" value="<?php echo set_value('sup_date'); ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group hide">
                                    <label class="control-label col-sm-4">PLACE OF SUPPLY</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="sup_place" value="<?php echo set_value('sup_place'); ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group hide">
                                    <label class="control-label col-sm-4">OTHER</label>
                                    <div class="col-sm-8"><input type="text" name="sup_other" value="<?php echo set_value('sup_other'); ?>" class="form-control">
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
                                <div class="container" id="table-container">
                                <input type="hidden" name="steps" value="0" id="steps">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <!-- <th>Design No</th> -->
                                            <th>Material Name</th>
                                            <th>Pices</th>
                                            <th>Average</th>   
                                            <th>Total Material Used</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $t = 0 ?>
                                    <div class="row select-row">
                                    <div class="col-lg-3">
                                        <label class="control-label col-sm-5 text-left" style="    text-align: left;">Select Design</label>
                                            <div class="col-sm-7">
                                                <select name="hsn_<?php echo $t ?>[]" id="hsn"  class="form-control my-select design required">
                                                    <option value="">--select design no--</option>
                                                    <?php foreach ($designs->result() as $row) {
                                                        $selected = set_select("hsn[]", $row->design_num);
                                                        $data_id = $row->id;
                                                        echo '<option label="" data-id="' . $row->id . '" value="' . $row->id . '" ' . set_select("hsn[]", $row->id) . '>' . $row->design_num . '</option>';

                                                    } ?>
                                                </select>
                                                <input type="hidden" name='selected_ids_<?php echo $t ?>[]' id="selected_ids" value="">
                                            </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="control-label col-sm-4 text-left" style="    text-align: left;">Total Pices</label>
                                        <div class="col-sm-7" id="">
                                            <input type="text" name='total_piece_<?php echo $t ?>[]' id="total_piece" class="form-control ttl_pice required" value="<?php echo set_value('total_piece'); ?>">

                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="control-label col-sm-4 text-left" style="    text-align: left;">Karigari (PP)</label>
                                        <div class="col-sm-7" id="">
                                            <input type="text" name='karigari_<?php echo $t ?>[]' id="karigari" class="karigari form-control required" value="<?php echo set_value('karigari'); ?>">

                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="control-label col-sm-5 text-left" style="    text-align: left;">Total Karigari</label>
                                        <div class="col-sm-6" id="">
                                            <input type="text" name='total_karigari_<?php echo $t ?>[]' class="total_karigari form-control required" value="<?php echo set_value('total_karigari'); ?>" readonly>

                                        </div>
                                    </div>
                                   
                                    </div>
                                        <tr class="row_one">
                                            <!-- <td>

                                            </td> -->
                                            <td class="material_ids"><!-- <input type="text" name="hsn[]" class="hsn form-control" size="3" maxlength="7" value=""> -->
                                            <select name="items_<?php echo $t ?>[]" id="items" class="required items form-control">
                                                    <option value="">--select Product--</option>
                                                    <?php
                                                    foreach ($materialList->result() as $row) {

                                                        //$mat = explode(',', $row->material_name);
                                                        $mat = explode(',', $row->material_name);
                                                        $cnt = count($mat);
                                                        for ($i = 0; $i < $cnt; $i++) {
                                                            //print_r( $row);
                                                            echo '<option label="" data-material-id="' . $row->id . '" value="' . $row->id . '" ' . set_select("items[]", $mat[$i]) . '>' . $mat[$i] . '</option>';
                                                        }
                                                    } ?>
                                                    <input type="hidden" name="material_ids_<?php echo $t ?>[]" id="material_ids" value="">
                                                    <input type="hidden" name="all_material_ids[]" id="all_material_ids" value="">
                                                </select>
                                        </td>
                                        <td><input type="text" name="qnty[]" class="qnty form-control" size="3" maxlength="7"></td>                                            
                                            <td><input type="text" name="rate[]" class="rate form-control" size="3" maxlength="7"></td>
                                                
                                            <td class="total_used_materials">
                                                <input type="text" name="total_material_<?php echo $t ?>[]" class="amount form-control required" style=" width: 40%; display: inline;" value="" size="3">&nbsp;
                                                <input type="hidden" name="total_material_used[]" id="total_material_used" value="">
                                                <button type="button" name="add_more" id="add_more" class="add_more btn btn-success btn-sm"><b>+</b></button>
                                                &nbsp;<button type="button" name="remove" id="remove" class="btn btn-warning btn-sm remove"><b>X</b></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <div class="col-sm-2 col-sm-offset-6">
                                    <b>TOTAL AMOUNT</b>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="total_amount" id="total_amount" readonly="readonly" class="total form-control" style="display: inline; width: 50%" value="" size="3">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2 col-sm-offset-6">
                                    <b>ROUND OFF TOTAL</b>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="total_round" id="total_round" readonly="readonly" class="form-control" style="display: inline; width: 50%" value="" size="3">
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="col-sm-2 col-sm-offset-6">
                                    <b>PAID AMOUNT</b>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="paid_amount" class="form-control only_num paid_amount" id="paid_amount" style="display: inline; width: 50%" value="" size="3">
                                </div>
                            </div>
                                                        <div class="form-group ">
                                                                <div class="col-sm-2 col-sm-offset-6">
                                                                        <b>BALANCE AMOUNT</b>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                        <input type="text" name="balance_amount" class="form-control only_num balance_amount" id="balance_amount" style="display: inline; width: 50%" value="" size="3">
                                                                </div>
                                                        </div>
                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <b>AMOUNT IN WORDS:</b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="total_word" id="total_word" class="form-control" style="display: inline; width: 50%" value="" size="3">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">&nbsp;</div>
                            </div>

                            <div class="form-group">
                              <div class="col-sm-6 col-sm-offset-3">
                                <button type="submit" name="add_challan" class="btn btn-primary submit-btn">SAVE & PRINT</button>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="reset" name="reload" class="btn btn-primary">Reset</button>
                                <button type="button" name="" class="btn btn-primary" id="duplicate-table-btn">Add New</button>
                                <button type="button" name="" class="btn btn-primary" id="remove-div">Remove</button>
                              </div>
                            </div>


                        </form>
                    </div>
                </div>


                <div class="modal fade" id="add_design" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <form id="add_design_form" method="post" action="<?php echo site_url('/Design/add_new'); ?>">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title custom_align" id="Heading">Add Design</h4>
                      </div>

                      <div class="modal-body">
                            <div class="col-sm-12">
                            <div class="form-group">
                                                    <label class="control-label col-sm-3">Design Number</label>
                                                    <div class="col-sm-9" id="design_holder">
                                                        <input type="text" name="design_number" id="design_number" class="form-control" value="">
                                                        <input type="hidden" name="id" value="">
                                                    </div>
                                                </div>
                            </div>
                        <p class="statusMsgDel text-center"></p>
                      </div>

                      <div class="modal-footer " style="margin-top: 50px;">
                        <div class="col-sm-12">
                        <button type="submit" class="btn btn-success" id="insert_update" ><span class="glyphicon glyphicon-ok-sign"></span>Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>Close</button>
                        </div>
                      </div>
                    </form>
                    </div>
                    <!-- /.modal-content -->
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?php echo base_url('assets/js/to_words.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript">


function delete_design(id)
{
    var id = id;
    $('#delete').modal('show');

    $("#yes").click(function(){
      $.ajax({
            type:'POST',
            url: $("#delete_form").attr("action"),
            data:'id='+id,
            dataType: "json",
            beforeSend: function () {
                $('.btn-default').attr("disabled","disabled");
                $('.modal-body').css('opacity', '.5');
            },
            success:function(msg){
              if( msg.status =='passed' )
              {
                $('.statusMsgDel').empty();
                $('.statusMsgDel').html('<span class="text-success">'+msg.result+'</span>');
                setTimeout(function(){
                  $('#delete').modal('hide');
                  location.reload();
                }, 2000);
              }
              else
              {
                $('.statusMsgDel').empty();
                $('.statusMsgDel').html('<span class="text-danger">'+msg.result+'</span>');
              }
              $('.btn-default').removeAttr("disabled");
              $('.modal-body').css('opacity', '');
            }
        });
    })

}
function add_new_design(){
    $('#add_design').modal('show');
}
$(document).on('click', '.editBtn', function(){
   var id = $(this).attr('id');
   $('#add_design').modal('show');

   $.ajax({
       type: 'ajax',
       method: 'post',
       url: '<?php echo site_url(); ?>/Design/fetch_by_id/'+id,
       data: {},
       async: false,
       //dataType: 'json',
       success: function(data){
           console.log(data);
           var data = JSON.parse(data);
          $("input[name=id]").val(data.id);
          $("input[name=design_number]").val(data.design_num);
       },
       error: function(){
           alert('Could not displaying data');
       },
   });
});

$(document).ready(function(){
    
    
    $('.total_karigari').on('focus', function(){
            var ro  = $(this).closest('.select-row');
            var total_piece = parseFloat(ro.find('#total_piece').val());
            var karigari  = parseFloat(ro.find('.karigari').val());
            var the_amount = (total_piece * karigari).toFixed(2);
            $(this).val(the_amount);            
        });
        
    //$('#table_with_tax, #table_without_tax').hide();
    $('#table_with_tax').hide();
    setTimeout(function(){
        $('.successMsg, .err_db').fadeIn().fadeOut().fadeIn().fadeOut('slow');
    }, 3000);
    $('#items, .qnty, .amount_with_tax, .rate, .amount, #trans_charge, #other_charge, #total_tax_value, #cgst_charge, #sgst_charge, #igst_charge, #total_amount, #total_round, #total_word').val('');
                $('#table_with_tax').hide();
                $('#table_without_tax').show();
                //set product rate on change
                $("select[name^='items']").on('change', function(){
                    var prod_name = $(this).children("option:selected").attr("label");
                    var ro = $(this).closest("tr");
                    if(prod_name != '')
                    {
                        ro.find('.rate').val(prod_name);
                       // ro.find('.qnty').val(1);
                    }
                });

                //show AMOUNT by qnty*rate
                $('.amount').on('focus', function(){
                    var ro  = $(this).parents('tr');
                    var qnty = ro.find('.qnty').val();
                    var rate = ro.find('.rate').val();
                    var the_amount = (qnty*rate).toFixed(2);
                    $(this).val(the_amount);
                });
                $('.qnty, .rate').on('change', function(){
                    var ro  = $(this).closest('tr');
                    var qnty = ro.find('.qnty').val();
                    var rate = ro.find('.rate').val();

                    if(qnty && rate)
                    {
                        var the_amount = (qnty*rate).toFixed(2);
                        ro.find('.amount').val(the_amount);
                    }
                });
    // add new row
    $(document).on('click', '.add_more', function(){
        var prevOption = $('#table_without_tax tbody tr:last').find('.my-select').val();
        var newRow = $(this).closest('tr').clone(true).find('.my-select').val(prevOption).end().insertAfter($(this).closest('tr'));
        $(newRow).find(':input:not(".my-select")').val('');

       // $(this).find('.my-select').val(prevOption);
    });
    //Remove table row
    $(document).on('click', '.remove', function(){
      var $tr = $(this).closest('tr');
      if ($tr.index() != '0') {
        $tr.remove();
      }
    });

    $(document).on('click', '.hsn', function(){
        $(this).val('1901');
    });

    $('#pices_add_form').validate({
        rules: {
            customerName: "required",
            region: "required",
            amount_with: "required",
            total_word: "required",
            paid_amount: "required",
            total_round: "required",
            total_amount: "required",
            'rate[]': {
                required: true,
                // Add more rules for quantity if needed
            },
            'hsn[]': {
                required: true,
                // Add more rules for quantity if needed
            },
            'items[]': {
                required: true,
                // Add more rules for quantity if needed
            },
        },
        errorPlacement: function (error, element) {
        error.appendTo(element.parent()); // For example, show error messages next to the input fields
        }
    });

    $(document).on('blur', '.items', function () {
        $(this).valid(); // Trigger validation for the hsn field when it loses focus
    });

    //show IGSC OR CGST and SGST hilton
    $('#region').click(function(){
        var reg = $(this).val();
        if(reg == 'in')
        {
            $('.in_state').fadeIn('slow');
            $('.out_state').fadeOut('slow');
        }
        else
        {
            $('.out_state').fadeIn('slow');
            $('.in_state').fadeOut('slow');
        }
    });
    //add only NUMBER and .
    $('.only_num').on('keyup', function(){
        var val = $(this).val();
        if(isNaN(val))
        {
            val = val.replace(/[^0-9\.]/g,'');
            if(val.split('.').length>2)
            val = val.replace(/\.+$/,"");
        }
        $(this).val(val);
    });

    //show customer address on customer name change event
    var cust_list = <?php echo json_encode($custList->result()); ?>;
    $("#customerName").on('change', function(){
        // alert();
        var cust_id = $("#customerName option:selected").val();

        for (var key = 0; key < cust_list.length; key++)
        {
            if( cust_list[key].id == cust_id )
            {
                $("#cust_adds_txt").val(cust_list[key].bakery_area+', '+cust_list[key].bakery_city);
                $("#cust_adds").val(cust_list[key].bakery_area+', '+cust_list[key].bakery_city);
                $("#cust_name").val(cust_list[key].bakery_name);
                $("#cust_gst_txt").val(cust_list[key].bakery_gst);
                $("#cust_gst").val(cust_list[key].bakery_gst);
            }
        }
    });


    //show form for amount with TAX or without TAX and validation logic
    $('#amount_with').change(function(){
        var a_w_t = $(this).val();
        if(a_w_t != '')
        {
            if(a_w_t == 'with')
            {
               // $('#pices_add_form')[0].reset();
                $('#items, .qnty, .rate, .amount, #trans_charge, #other_charge, #total_tax_value, #cgst_charge, #sgst_charge, #igst_charge, #total_amount, #total_round, #total_word').val('');
                $('#table_with_tax').show();
                $('#table_without_tax').hide();
                //show AMOUNT by qnty*rate
                $('.amount, .rate').on('focus', function(){
                    var ro  = $(this).parents('tr');
                    var tot_amount = ro.find("input[name*= 'amount_with_tax']").val();
                    if(tot_amount !== undefined)
                    {
                        var qnty = ro.find(".qnty").val() ? ro.find(".qnty").val() : 1;
                        var amount = ((tot_amount*100)/105).toFixed(2);
                        var rate = (amount/qnty).toFixed(2);
                        ro.find('.amount').val(unit_price);
                        ro.find('.rate').val(price);
                    }
                }); 
                $('.amount').on('focus', function(){
                    var ro  = $(this).parents('tr');
                    var qnty = ro.find('.qnty').val();
                    var rate = ro.find('.rate').val();
                    var the_amount = (qnty*rate).toFixed(2);
                    $(this).val(the_amount);
                });

            }
            else
            {
               // $('#pices_add_form')[0].reset();
               $('#items, .qnty, .amount_with_tax, .rate, .amount, #trans_charge, #other_charge, #total_tax_value, #cgst_charge, #sgst_charge, #igst_charge, #total_amount, #total_round, #total_word').val('');
                $('#table_with_tax').hide();
                $('#table_without_tax').show();
                //set product rate on change
                $("select[name^='items']").on('change', function(){
                    var prod_name = $(this).children("option:selected").attr("label");
                    var ro = $(this).closest("tr");
                    if(prod_name != '')
                    {
                        ro.find('.rate').val(prod_name);
                       // ro.find('.qnty').val(1);
                    }
                });

                //show AMOUNT by qnty*rate
                $('.amount').on('focus', function(){
                    var ro  = $(this).parents('tr');
                    var qnty = ro.find('.qnty').val();
                    var rate = ro.find('.rate').val();
                    var the_amount = (qnty*rate).toFixed(2);
                    $(this).val(the_amount);
                });
                $('.qnty, .rate').on('change', function(){
                    var ro  = $(this).closest('tr');
                    var qnty = ro.find('.qnty').val();
                    var rate = ro.find('.rate').val();

                    if(qnty && rate)
                    {
                        var the_amount = (qnty*rate).toFixed(2);
                        ro.find('.amount').val(the_amount);
                    }
                });
            }   //end else brace

            //total taxable value before gst
            var total = 0, total_in_word = 0;
            //total Amount before GST
            $('#total_tax_value').on('focus', function(){
                var crnt_val = parseFloat($(this).val());
                total = 0;
               /*  $('.amount').each(function(){
                    if( $(this).val() !== '' )
                    {
                        var amt = $(this).val();
                        total += parseFloat(amt);
                    }
                }); */

                var other_charge = $('#other_charge').val() != '' ? $('#other_charge').val() : 0;
                var trans_charge = $('#trans_charge').val() != '' ? $('#trans_charge').val() : 0;
                total +=  parseFloat(other_charge) + parseFloat(trans_charge);
                if(total != crnt_val)
                {
                    $(this).val(total.toFixed(2));
                }
            });

            // add CGST and SGST 2.5% and igst 5%
            $('#cgst_charge').on('focus', function(){
                var cgst = $('#cgst_per').val();
                if(cgst != '')
                {
                    cgst = parseFloat(cgst);
                    var tax = parseFloat( (total*cgst)/100 );
                    $(this).val(tax.toFixed(2));
                }
            });

            $('#sgst_charge').on('focus', function(){
                var sgst = $('#sgst_per').val();
                if(sgst != '')
                {
                    sgst = parseFloat(sgst);
                    var tax = parseFloat( (total*sgst)/100 );
                    $(this).val(tax.toFixed(2));
                }
            });

            $('#igst_charge').on('focus', function(){
                var igst = $('#igst_per').val();
                if(igst != '')
                {
                    igst = parseFloat(igst);
                    var tax = parseFloat( (total*igst)/100 );
                    $(this).val(tax.toFixed(2));
                }
            });

                //total with TAX


            $('#total_round').on('focus', function(){
                var rupee = Math.round( $('#total_amount').val() );
                $(this).val(rupee);
            });

            // amount in WORDS
            $("#total_word").on('focus', function(){
                total_in_word = $('#total_round').val();
                if(total_in_word != null)
                {
                    var total_words = NumToWord(total_in_word);
                    $(this).val(total_words);
                }
            });

        }   //end main if
    });
    
    $('#total_amount').on('focus', function(){

        console.log("in total_amount");
        total = 0;
        $('.total_karigari').each(function(){
            if( $(this).val() !== '' )
            {
                var amt = $(this).val();
                total += parseFloat(amt);
            }
            console.log(total);
        });
        var crnt_val = parseFloat(total);

        var other_charge = 0;
        var trans_charge = 0;
        total +=  parseFloat(other_charge) + parseFloat(trans_charge);
        if(total != crnt_val)
        {
            $(this).val(total.toFixed(2));
        }

        var cgst = $('#cgst_charge').val() != '' ? parseFloat($('#cgst_charge').val()) : 0;
        var sgst = $('#sgst_charge').val() != '' ? parseFloat($('#sgst_charge').val()) : 0;
        var igst = $('#igst_charge').val() != '' ? parseFloat($('#igst_charge').val()) : 0;

        /* var total_with_tax = parseFloat($('#total_tax_value').val()) + cgst + sgst + igst ; */
        var total_with_tax = parseFloat(total) + 0 + 0 + 0 ;
        total_with_tax      = total_with_tax.toFixed(2);
        $(this).val(total_with_tax);
        //total round amount
        $('#total_round').val(Math.round(total_with_tax));
        //total in words
        var round_amount = $('#total_round').val();
        if( round_amount!= null)
        {
            var total_words = NumToWord(round_amount);
            $("#total_word").val(total_words);
        }

    });
    $('.balance_amount').on('focus', function(){
                                        var total_amount = $('#total_amount').val();
                                        var paid_amount = $('#paid_amount').val();
                    var the_amount = (total_amount-paid_amount).toFixed(2);
                                        console.log(the_amount);
                    $(this).val(the_amount);
                });
/* --- */
$('.submit-btn').click(function() {
    //$('form.pices_add_form').on('submit', function(event) {
        $('#pices_add_form').validate();
        //event.preventDefault();
        var selected_ids = [];
      var material_ids = [];
      var total_material_used = [];
        $(".my-select").each(function() {
            var selectedOption = $(this).find(":selected");
            var otherAttribute = selectedOption.attr('data-id');
            selected_ids.push(otherAttribute);
            });
        $('#selected_ids').val(selected_ids.join(','));
      $('tr td.material_ids').each(function() {
            //var selectedValue = $(this).find('select').val();
            var otherAttribute2 = $(this).find('select option:selected').attr('data-material-id');
            /* if (!otherAttribute2 || otherAttribute2=="undefined") {
                alert("Select Material");
                return;
            } */
            console.log(' Other attribute value: ' + otherAttribute2);
            material_ids.push(otherAttribute2);
            console.log(material_ids);
        });
        
        // $('#all_material_ids').val(material_ids.join(','));

      $('tr td.total_used_materials').each(function() {
            //var selectedValue = $(this).find('select').val();
            var otherAttribute3 = $(this).find(".amount").val();
            /* if (!otherAttribute3 || otherAttribute3=="undefined") {
                alert("Select Material");
                return;
            } */
            console.log(' Other attribute value: ' + otherAttribute3);
            total_material_used.push(otherAttribute3);
            console.log(total_material_used);
        });
        // $('#total_material_used').val(total_material_used.join(','));
        function calculateTotalQuantities(material_ids, quantity) {
        materialQuantities = {};

        for (let i = 0; i < material_ids.length; i++) {
            var id = material_ids[i];
            var qty = parseFloat(quantity[i]);

            if (materialQuantities[id]) {
            materialQuantities[id] += qty;
            } else {
            materialQuantities[id] = qty;
            }
        }

        return materialQuantities;
        }

        var totalQuantities = calculateTotalQuantities(material_ids, total_material_used);

        console.log(totalQuantities);

        material_ids = Object.keys(materialQuantities).map(Number);

        console.log(material_ids); // Output: [1, 22, 33]
        $('#all_material_ids').val(material_ids.join(','));

        total_material_used = Object.values(materialQuantities);
                $('#total_material_used').val(total_material_used.join(','));

        console.log(total_material_used); // Output: [40, 25, 30]
    });
    
    var count = 0;
    $('#duplicate-table-btn').click(function() {
        var step_no = $("#steps").val();
        
        //console.log(step_no);
        count++;
        $("#steps").val(count);
        var htmlStructure = "";
        <?php $t++

            ?>
        htmlStructure += ` <hr class="divide_border">
        <div class="new-div select-row">
        <div class="error-msg"></div>
         <div class="col-lg-3 " >
                                        <label class="control-label col-sm-5 text-left" style="    text-align: left;">Select Design</label>
                                            <div class="col-sm-7   ">
                                                <select name="hsn_`+count+`[]" id="hsn"  class="form-control my-select design required">
                                                    <option value="">--select design no--</option>
                                                    <?php foreach ($designs->result() as $row) {
                                                        $selected = set_select("hsn[]", $row->design_num);
                                                        $data_id = $row->id;
                                                        echo '<option label="" data-id="' . $row->id . '" value="' . $row->id . '" ' . set_select("hsn[]", $row->design_num) . '>' . $row->design_num . '</option>';

                                                    } ?>
                                                </select>
                                                <input type="hidden" name="selected_ids_`+count+`[]" id="selected_ids" value="">
                                            </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="control-label col-sm-4 text-left" style="    text-align: left;">Total Pices</label>
                                        <div class="col-sm-7" id="addds_holder">
                                            <input type="text" name="total_piece_`+count+`[]" id="total_piece" class="form-control" value="<?php echo set_value('total_piece'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="control-label col-sm-4 text-left" style="    text-align: left;">Karigari</label>
                                        <div class="col-sm-7" id="">
                                            <input type="text" name='karigari_`+count+`[]' id="karigari" class="karigari form-control required" value="<?php echo set_value('karigari'); ?>">

                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="control-label col-sm-5 text-left" style="    text-align: left;">Total Karigari</label>
                                        <div class="col-sm-6" id="">
                                            <input type="text" name='total_karigari_<?php echo $t ?>[]' class="total_karigari form-control required" value="<?php echo set_value('total_karigari'); ?>" readonly>

                                        </div>
                                    </div>
                                    <div class="form-group" id=""> <table class="table table-bordered"><thead><tr>
                                    <th>Material Name</th>
                                            <th>Pices</th>
                                            <th>Average</th>   
                                            <th>Total Material Used</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="row_one">
                        
                                            <td class="material_ids">
                                            <select name="items_`+count+`[]" id="items" class=" items form-control required">
                                                    <option value="">--select Product--</option>
                                                    <?php
                                                    foreach ($materialList->result() as $row) {

                                                        $mat = explode(',', $row->material_name);
                                                        $cnt = count($mat);
                                                        for ($i = 0; $i < $cnt; $i++) {

                                                            echo '<option label="" data-material-id="' . $row->id . '" value="' . $row->id . '" ' . set_select("items[]", $mat[$i]) . '>' . $mat[$i] . '</option>';
                                                        }
                                                    } ?>
                                                    <input type="hidden" name="material_ids_`+count+`[]" id="material_ids" value="">
                                                    <input type="hidden" name="all_material_ids[]" id="all_material_ids" value="">
                                                </select>
                                        </td>
                                        <td><input type="text" name="qnty[]" class="qnty form-control required" size="3" maxlength="7"></td>                                            
                                            <td><input type="text" name="rate[]" class="rate form-control required" size="3" maxlength="7"></td>
                                                
                                            <td class="total_used_materials">
                                                <input type="text" name="total_material_`+count+`[]" class="amount form-control required" style=" width: 40%; display: inline;" value="" size="3">&nbsp;
                                                <button type="button" name="add_more" id="add_more" class="add_more btn btn-success btn-sm"><b>+</b></button>
                                                &nbsp;<button type="button" name="remove" id="remove" class="btn btn-warning btn-sm remove"><b>X</b></button>
                                            </td>
                                        </tr>
 
                            </div>
                            </div> `;
        $('#table-container').append(htmlStructure);
        $('#pices_add_form').validate();
        $('.total_karigari').on('focus', function(){
            var ro  = $(this).closest('.select-row');
            var total_piece = parseFloat(ro.find('#total_piece').val());
            var karigari  = parseFloat(ro.find('.karigari').val());
            var the_amount = (total_piece * karigari).toFixed(2);
            $(this).val(the_amount);            
        });
        $('.amount').on('focus', function(){
                    var ro  = $(this).parents('tr');
                    var qnty = ro.find('.qnty').val();
                    var rate = ro.find('.rate').val();
                    var the_amount = (qnty*rate).toFixed(2);
                    $(this).val(the_amount);
                });
                
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
        $('.qnty').each(function() {
                $(this).rules("add", 
                    {
                        required: true
                    })
        });
        $('.items').each(function() {
                $(this).rules("add", 
                    {
                        required: true
                    })
        });
        $('select.items').each(function () {
            $(this).rules('add', {
                required: true
            });
        });
    });
    /* $('.total').on('focus', function(){ 
        var crnt_val = parseFloat($(this).val());
        var total = 0;
        $('.amount').each(function(){            
            if( $(this).val() != '' )
            {
                var amt = $(this).val();
                total += parseFloat(amt);                
            }   
        });
        if(total != crnt_val)
        {
          tot_num = total;
          $(this).val(Math.round(total));
          if(tot_num != null)
          {      
            var total_wrd = NumToWord(Math.round(tot_num));
            $("#total_word").val(total_wrd);
          }

        }
    }); */  
    $('#remove-div').click(function() {
        $('.new-div:last').remove();
    });
    
</script>
