<style>
  input[type="date"]:not(.has-value):before{
  color: #ccc;
  content: attr(placeholder);
  margin-right: 10px;

}
input[type="date"]{
  display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #000;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
}
</style>
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
            <a class="btn btn-primary btn-sm" href="<?php echo base_url('/index.php/Making/');?>">Go back to Making list</a>
          </p> <hr />
          <?php
            echo form_open('Making/add_new', 'class="form-horizontal" id="add_product_form"');
          ?>
          <div class="form-group">
            <div class="col-sm-5">
              <input type="text" class="form-control" name="master_name" placeholder="Master Name" value="<?php echo set_value('master_name'); ?>">
            </div>
            <div class="col-sm-6"> <?php echo form_error('master_name', '<p class="text-danger">', '</p>'); ?></div>
          </div>
          <div class="form-group">
            <div class="col-sm-5">
              <input type="text" class="form-control" name="material_name" placeholder="Material Name" value="<?php echo set_value('material_name'); ?>">
            </div>
            <div class="col-sm-6"> <?php echo form_error('material_name', '<p class="text-danger">', '</p>'); ?></div>
          </div>

            <!-- <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="weight" name="weight" placeholder="Amount" value="<?php echo set_value('weight'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('weight', '<p class="text-danger">', '</p>'); ?></div>
            </div> -->
            <!-- <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="design_number" name="p_design_number" placeholder="Design Number" value="<?php echo set_value('design_number'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('design_number', '<p class="text-danger">', '</p>'); ?></div>
            </div> -->
            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="stock_q" name="stock_q" placeholder="Stock/Quantity" value="<?php echo set_value('price'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('stock_q', '<p class="text-danger">', '</p>'); ?></div>
            </div>
            <!-- <input type="radio" value="1" name="pcs">
            Pcs
            <input type="radio" value="0" name="pcs">
            Meter -->
            <!-- <div class="form-group">
              <div class="col-sm-5">
                <input type="date" class="form-control" id="prod_exp" name="product_exp" placeholder="Expiry Date" value="<?php echo set_value('prod_exp'); ?>" onchange="this.className=(this.value!=''?'has-value':'')">
              </div>
              <div class="col-sm-6"> <?php echo form_error('prod_exp', '<p class="text-danger">', '</p>'); ?></div>
            </div> -->

            <!-- <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="price" name="p_price" placeholder="Price" value="<?php echo set_value('price'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('price', '<p class="text-danger">', '</p>'); ?></div>
            </div>
            <div class="form-group">
              <div class="col-sm-5">
                <input type="text" class="form-control" id="price_total" name="price_total" placeholder="Total Amount" value="<?php echo set_value('price_total'); ?>" readonly="readonly">
              </div>
              <div class="col-sm-6"> <?php echo form_error('price_total', '<p class="text-danger">', '</p>'); ?></div>
            </div> -->

          <div class="form-group">
            <div class="col-sm-5">
              <?php echo form_submit('add_making','Add Material','class="btn btn-success"'); ?>
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
  $(document).ready(function(){
    $('#stock_q, #price').on('change', function(){
      var qnty = $('#stock_q').val();
      var rate = $('#price').val();
      var the_amount = (qnty*rate).toFixed(2);
      $('#price_total').val(the_amount);
    });
  });
</script>
