<div class="container-fluid" id="bg-color"><br /></div>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4>
            <?php echo ucwords($username) . ', '; ?><small>
              <?php echo date('d F, Y'); ?>
            </small><span class="text-sm pull-right"><a href="<?php echo site_url('Product/logout'); ?>">Log
                Out</a></span>
          </h4>
        </div>

        <div class="panel-body">
          <p data-placement="top" data-toggle="tooltip">
            <a class="btn btn-info btn-sm" href="<?php echo base_url('/index.php/Making/'); ?>">Go back to Material
              list</a>
          </p>
          <?php
          $url = 'Making/edit_making/' . $prod->id;
          echo form_open($url, 'class="form-horizontal" id="add_product_form"');
          ?>
          <!-- <div class="form-group">
             <div class="col-sm-5">
                <label>Purchaser Name</label>
                <input type="text" class="form-control" name="master_name" placeholder="Master Name" value="<?php echo set_value('master_name'); ?>">
                <select name="master_id" id="master_id" class="form-control">
                   <option value="" selected="selected">--select master--</option>
                     <?php
                     foreach ($PurchaserList->result() as $row) {
                       echo '<option value="' . $row->id . '" ' . set_select('ownerName', $row->name) . '>' . $row->name . '</option>';
                       ?>
                             <option value="<?php echo $row->id ?>" <?php echo ($prod->purchaser_owner_id == $row->id) ? 'selected' : '' ?>><?php echo $row->name ?></option>
                             <?php
                     } ?>

                </select>
             </div>
             <div class="col-sm-6"> <?php echo form_error('master_name', '<p class="text-danger">', '</p>'); ?></div>
          </div> -->
          <div class="form-group">
            <div class="col-sm-5">

              <label>Master Name</label>

              <select name="master_name" id="master_name" class="form-control">
                <option value="" selected="selected">--select master--</option>
                <?php
                foreach ($custList->result() as $row) { ?>
                  <option value="<?php echo $row->id ?>" <?php echo ($prod->making_owner_id == $row->id) ? 'selected' : '' ?>><?php echo $row->name ?></option>
                  <?php
                } ?>
              </select>
            </div>
            <div class="col-sm-4">

            </div>
            <div class="col-sm-2">
              <label>Bill Date</label>
              <input type="date" id="bill_date" name="bill_date" value="<?php echo $prod->create_date ?>" />

            </div>
            <div class="col-sm-6">
              <?php echo form_error('owner_name', '<p class="text-danger">', '</p>'); ?>
            </div>
          </div>
          <input type="hidden" name="prod_id" value="<?php echo $prod->id; ?>">
          <input type="hidden" name="maker_no" value="<?php echo $prod->maker_no ?>">

          <div class="form-group" id="table_without_tax">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Material Name</th>
                  <th>Available Stock</th>
                  <th>Quantity</th>
                </tr>
              </thead>
              <?php
              $mat = explode(',', $prod->material_id);
              $stk = explode(',', $prod->stock);
              $cnt = count($mat);

              for ($i = 0; $i < $cnt; $i++) {
                ?>
                <tbody id="rows-list">
                  <tr class="row_one">

                    <td class="">
                      <select name="material_name[]" id="material_name" class="form-control check_stock">
                        <option value="" selected="selected">--select material--</option>
                        <?php

                        foreach ($matList->result() as $row) {
                          ?>

                          <option value="<?php echo $row->id ?>" <?php echo ($mat[$i] == $row->id) ? 'selected' : '' ?>><?php echo $row->material_name ?></option>
                          <?php

                        } ?>
                      </select>

                      <div class="col-sm-6">
                        <?php echo form_error('material_name', '<p class="text-danger">', '</p>'); ?>
                      </div>
                    </td>
                    <td>
                      <?php
                      $get_pstock = $this->Stock_model->get_material_stock($mat[$i]);
                      if (!$get_pstock || !isset($get_pstock->stock_qty)) {
                        $pstock_quantity = " ";
                      } else {
                        $pstock_quantity = $get_pstock->stock_qty;
                      }
                      ?>
                      <input type="text" class="form-control stock_in" id="stock_in" name="stock_in[]"
                        placeholder="Stock/Quantity" value="<?php echo $get_pstock ?>" readonly>
                    </td>
                    <td>
                      <input type="hidden" class="form-control" id="stock_qhidden" name="stock_qhidden[]"
                        value="<?php echo $stk[$i]; ?>">
                      <input type="text" class="form-control" id="stock_q" name="stock_q[]" placeholder="Quantity"
                        value="<?php echo $stk[$i]; ?>">
                      <div class="col-sm-6">
                        <?php echo form_error('stock_q', '<p class="text-danger">', '</p>'); ?>
                      </div>
                    </td>
                    <td>
                      <button type="button" name="add_more" id="add_more" class="add_more btn btn-success btn-sm"
                        fdprocessedid="1s22ut"><b>+</b></button>
                      &nbsp;<button type="button" name="remove" id="remove" class="btn btn-warning btn-sm"
                        fdprocessedid="vik1a"><b>X</b></button>
                    </td>
                  </tr>
                </tbody>
                <?php

              }
              ?>
            </table>
          </div>



          <div class="form-group">
            <div class="col-sm-5">
              <?php echo form_submit('edit_making', 'Edit & Save', 'class="btn btn-success"'); ?>
            </div>
            <div class="col-sm-6">
              <?php
              if ($this->session->flashdata('failed')) {
                echo '<p class="text-danger">' . $this->session->flashdata('failed') . '</p>';
              }
              ?>
            </div>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>



</div><!--close main div-->
<script type="text/javascript">
  // add new row
  $(document).on('click', '.add_more', function () {
    $(this).closest('tr').clone(true).find(':input:not(".hsn")').val('').end().insertAfter($(this).closest('tr'));
  });
  //Remove table row
  $(document).on('click', '#remove', function () {
    // debugger;
    var $tr = $(this).closest('tr');
    if ($tr.index() != '0') {
      $tr.remove();
    }
    else {
      $tr.remove();
    }
  });
  function changeRadio1() {
    var isChecked = $('input[name=pcs]').is(':checked');
    if (isChecked) {
      $('input[name=meter]').prop('checked', false);
    } else {
      $('input[name=pcs]').prop('checked', false);
    }
  }

  function changeRadio2() {
    var isChecked2 = $('input[name=meter]').is(':checked');
    if (isChecked2) {
      $('input[name=pcs]').prop('checked', false);
    } else {
      $('input[name=meter]').prop('checked', false);
    }
  }

  //  function checkQuantity(event) {
  //           console.log(event);
  //             var row = $(event).closest('tr');
  //              var material_id = $(event).val();
  //             // var material_id = $('#material_name').val();
  //             // $(".stock_in").val(" ");
  //               row.find(".stock_in").val(" ");

  //             var baseURL= "<?php echo base_url(); ?>";
  //             $.ajax({
  //                 type: 'post',
  //                 url: '<?= base_url() ?>index.php/Making/quantityById',
  //                 data: {material_id: material_id},
  //             }).then(function (res) {
  //               console.log(res);
  //               var res = $.parseJSON(res);
  //                 if (res || res != null) {
  //                   row.find(".stock_in").val(res.quantity);
  //                   // $(".stock_in").val(res.quantity);
  //                 }else{
  //                   $(".stock_in").val(" ");
  //                 }
  //             }, function () {
  //                 alert("Sorry cannot get the product details!");
  //             });
  //           }
  $(document).ready(function () {

    var list = $("#rows-list");
    // $(list).on('change', ".check_stock", function () {
    $('.check_stock').on('change', function () {
      var row = $(this).closest('tr');
      var material_id = $(this).val();
      row.find(".stock_in").val(" ");
      var baseURL = "<?php echo base_url(); ?>";
      $.ajax({
        type: 'post',
        url: '<?= base_url() ?>index.php/Making/quantityById',
        data: { material_id: material_id },
      }).then(function (res) {
        console.log(res);
        var res = $.parseJSON(res);
        if (res || res != null) {
          row.find(".stock_in").val(res.quantity);
        } else {
          row.find(".stock_in").val(0);
        }
      }, function () {
        alert("Sorry cannot get the product details!");
      });
    });




    $('#stock_q, #p_price').on('change', function () {
      var qnty = $('#stock_q').val();
      var rate = $('#p_price').val();
      var the_amount = (qnty * rate).toFixed(2);
      $('#price_total').val(the_amount);
    });
  });
</script>