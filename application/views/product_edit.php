<div class="container-fluid" id="bg-color"><br /></div>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4><?php echo ucwords($username).', ';?><small><?php echo  date('d F, Y');?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Product/logout');?>">Log Out</a></span></h4>
        </div>

        <div class="panel-body">
          <p data-placement="top" data-toggle="tooltip">
            <a class="btn btn-info btn-sm" href="<?php echo base_url('/index.php/Product/');?>">Go back to Material list</a>
          </p>
          <?php
          // print_r($prod);
            $url = 'Product/edit/'.$prod->id;
            echo form_open($url, 'class="form-horizontal" id="add_product_form"');
          ?>
          <div class="form-group">
            <div class="col-sm-5">
          <!-- <select name="purchaserID" id="purchaserID" class="form-control">
            <option value="" selected="selected">--select purchaser--</option>
            <?php foreach ($purList->result() as $row){
              // 'if($row->id  == $prod->purchase_id ){echo.'"selected"'.}.'
              // print_r($row->id  == $prod->purchaser_id);
                echo '<option    value="'.$row->id.'" '.set_select('purchaserID',$row->id).'>'.$row->bakery_name.'</option>';
            } ?>
          </select> -->
          <label>Owner Name</label>
          <input type="text" class="form-control" name="owner_name" placeholder="Owner Name" value="<?php echo set_value('owner_name', $prod->owner_name); ?>">

        </div>
        <div class="col-sm-6"> <?php echo form_error('owner_name', '<p class="text-danger">', '</p>'); ?></div>
      </div>
            <div class="form-group">
              <div class="col-sm-5">
          <label>Material Name</label>

                <input type="text" class="form-control" name="material_name" placeholder="material Name" value="<?php echo set_value('material_name', $prod->product_name); ?>">
                <input type="hidden" name="prod_id" value="<?php echo $prod->id; ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('prod_name', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <!-- <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="p_design_number" name="p_design_number" placeholder="Price" value="<?php echo set_value('design_number', $prod->design_number); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('p_design_number', '<p class="text-danger">', '</p>'); ?></div>
            </div> -->
            <div class="form-group">
              <div class="col-sm-5">
          <label>Stock/Quantity</label>

                <input type="text" class="form-control" id="stock_q" name="stock_q" placeholder="Stock" value="<?php echo set_value('stock', $prod->stock);?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('stock_q', '<p class="text-danger">', '</p>'); ?></div>
            </div>
            <div class="form-group">
              <div class="col-sm-5">
          <label>Price</label>

                <input type="text" class="form-control" id="p_price" name="p_price" placeholder="Price" value="<?php echo set_value('price', $prod->price); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('p_price', '<p class="text-danger">', '</p>'); ?></div>
            </div>


            <!-- <input type="radio" value="1" onclick="changeRadio1()" name="pcs" <?php if ($prod->pcs == '1') {?>
              checked <?php
            } ?>>
            Pcs
            <input type="radio" value="0" onclick=" changeRadio2()" name="pcs" <?php if ($prod->pcs == '0') {?>
              checked <?php
            } ?>>
            Meter -->
            <!-- <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="product_exp" name="product_exp" placeholder="Expiry Date" value="<?php echo set_value('prod_exp', $prod->prod_exp); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('product_exp', '<p class="text-danger">', '</p>'); ?></div>
            </div> -->

            <div class="form-group">
              <div class="col-sm-5">
          <label>Total Amount</label>

                <input type="text" class="form-control" id="price_total" name="price_total" placeholder="Total Amount" value="<?php echo set_value('price_total', $prod->total_amount); ?>" readonly="readonly">
              </div>
              <div class="col-sm-6"> <?php echo form_error('price_total', '<p class="text-danger">', '</p>'); ?></div>
            </div>

          <div class="form-group">
            <div class="col-sm-5">
              <?php echo form_submit('edit_product','Edit & Save','class="btn btn-success"'); ?>
            </div>
             <div class="col-sm-6">
              <?php
                if( $this->session->flashdata('failed') )
                { echo '<p class="text-danger">'.$this->session->flashdata('failed').'</p>'; }
              ?>
             </div>
          </div>
      <?php echo form_close();  ?>
        </div>
      </div>
    </div>
  </div>
</div>



</div><!--close main div-->
<script type="text/javascript">

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


  $(document).ready(function(){



    $('#stock_q, #p_price').on('change', function(){
      var qnty = $('#stock_q').val();
      var rate = $('#p_price').val();
      var the_amount = (qnty*rate).toFixed(2);
      $('#price_total').val(the_amount);
    });
  });
</script>
