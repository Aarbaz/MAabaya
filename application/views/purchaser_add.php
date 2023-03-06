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
          <h4><?php echo ucwords($username).', ';?><small><?php echo  date('d F, Y');?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Purchaser/logout');?>">Log Out</a></span></h4>
        </div>

        <div class="panel-body">
          <p data-placement="top" data-toggle="tooltip">
            <a class="btn btn-primary btn-sm" href="<?php echo base_url('/index.php/purchaser_add/');?>">Go back to Material list</a>
          </p> <hr />
          <?php
            echo form_open('Purchaser/add_new', 'class="form-horizontal" id="add_purchaser_form"');
          ?>
          <div class="form-group">
            <div class="col-sm-5">

          <label>Owner Name</label>
          <input type="text" class="form-control" name="owner_name" placeholder="Owner Name" value="<?php echo set_value('owner_name'); ?>">

        </div>
        <p>
          <span class="btn btn-primary btn-sm" onclick="add_new_purchaser()">Add New</span>
        </p>
        <div class="col-sm-6"> <?php echo form_error('owner_name', '<p class="text-danger">', '</p>'); ?></div>
      </div>
      <div class="form-group" id="table_without_tax">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Material Name</th>
                                <th>Meters</th>
                                <th>Per Peter Price</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="row_one">

                                <td class="">
                                  <input type="text" class="form-control" name="material_name[]" placeholder="Material Name" value="<?php echo set_value('material_name'); ?>">
                                </td>
                                <td>
                                  <input type="text" class="form-control qnty" id="stock_q" name="stock_q[]" placeholder="Meters"  value="<?php echo set_value('price'); ?>">
                                </td>
                                <td>
                                  <input type="text" class="form-control rate" id="price" name="p_price[]" placeholder="Price"  value="<?php echo set_value('price'); ?>">

                                </td>
                                <td>
                                  <input type="text" class="form-control amount" id="price_total" name="price_total[]" placeholder="Total Amount" value="<?php echo set_value('price_total'); ?>" readonly="readonly">

                                </td>
                                <td>
                                    <button type="button" name="add_more" id="add_more" class="add_more btn btn-success btn-sm" fdprocessedid="1s22ut"><b>+</b></button>
                                    &nbsp;<button type="button" name="remove" id="remove" class="btn btn-warning btn-sm remove" fdprocessedid="vik1a"><b>X</b></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <!-- <div class="form-group">
              <div class="col-sm-5">
                <label>Material Name</label>
                <input type="text" class="form-control" name="material_name" placeholder="Material Name" value="<?php echo set_value('material_name'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('material_name', '<p class="text-danger">', '</p>'); ?></div>
            </div>

            <div class="form-group">
              <div class="col-sm-5">
                <label>Quantity</label>
                <input type="text" class="form-control" id="stock_q" name="stock_q" placeholder="Stock/Quantity" value="<?php echo set_value('price'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('stock_q', '<p class="text-danger">', '</p>'); ?></div>
            </div>


            <div class="form-group">
              <div class="col-sm-5">
                <label>Price</label>

                <input type="text" class="form-control" id="price" name="p_price" placeholder="Price" value="<?php echo set_value('price'); ?>">
              </div>
              <div class="col-sm-6"> <?php echo form_error('price', '<p class="text-danger">', '</p>'); ?></div>
            </div>
            <div class="form-group">
              <div class="col-sm-5">
                <label>Total Amount</label>

                <input type="text" class="form-control" id="price_total" name="price_total" placeholder="Total Amount" value="<?php echo set_value('price_total'); ?>" readonly="readonly">
              </div>
              <div class="col-sm-6"> <?php echo form_error('price_total', '<p class="text-danger">', '</p>'); ?></div>
            </div> -->

          <div class="form-group">
            <div class="col-sm-5">
              <?php echo form_submit('add_purchaser','Add Material','class="btn btn-success"'); ?>
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

<div class="modal fade" id="add_design" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="add_design_form" method="post" action="<?php echo site_url('/Design/add_new');?>">
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
<script src="<?php echo base_url('assets/js/to_words.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript">

function add_new_purchaser(){
    $('#add_design').modal('show');
}

// add new row
$(document).on('click', '.add_more', function(){
    $(this).closest('tr').clone(true).find(':input:not(".hsn")').val('').end().insertAfter($(this).closest('tr'));
});
//Remove table row
$(document).on('click', '.remove', function(){
  var $tr = $(this).closest('tr');
  if ($tr.index() != '0') {
    $tr.remove();
  }
});

  $(document).ready(function(){
    // $('#stock_q, #price').on('change', function(){
    //   var qnty = $('#stock_q').val();
    //   var rate = $('#price').val();
    //   var the_amount = (qnty*rate).toFixed(2);
    //   $('#price_total').val(the_amount);
    // });

    $('.qnty, .rate').on('change', function(){
        var ro  = $(this).closest('tr');
        var qnty = ro.find('.qnty').val();
        var rate = ro.find('.rate').val();

        if(qnty && rate)
        {
            // var the_amount = (qnty*rate).toFixed(2);
            var the_amount = (qnty*rate);
            ro.find('.amount').val(the_amount);
        }
    });


  });
</script>
