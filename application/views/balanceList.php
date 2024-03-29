<style>
.debited{
  color: red;
}
.credited{
  color: green;
}
</style>
<div class="container-fluid" id="bg-color"><br /></div>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4><?php echo ucwords($username).', ';?><small><?php echo  date('d F, Y');?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Balance/logout');?>">Log Out</a></span></h4>
        </div>

        <div class="panel-body">
          <p>

            <div class="form-group">
              &nbsp;&nbsp;<a class="btn btn-primary btn-sm" href="<?php echo base_url('/index.php/Balance/ledger');?>">Add New</a>
            </div>
          </p><br />
          <div><p id="result_box" class="text-center"></p></div>
          <?php
            if( $this->session->flashdata('success') )
            { echo '<div class="alert alert-success show_hide" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p class="text-center"><strong>Success!</strong> '.$this->session->flashdata('success').'</p></div>'; }
          ?>
          <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th>Sr No</th>
                <th>Customer</th>
                <th>Bill Total</th>
                <th>Last Paid Amount</th>
                <th>Balance</th>
                <th>Date</th>
                <!-- <th>Action</th> -->
              </tr>
            </thead>

            <tbody>
            <?php
             $i = 1;   foreach ($custList->result() as $bal){

              $balance_list = $this->Customer_model->get_customer_byName($bal->name);

              foreach ($balance_list->result() as $balUser){

              $balance = $this->Balance_model->get_bal_user($balUser->id);

             foreach ($balance->result() as $row){  
              
              ?>
              <tr>
                <td><?php echo $i ?></td>
                <td><?php echo $bal->name; ?></td>
                <td><?php echo $row->total_bill; ?></td>
                <td><?php echo $row->paid_bill; ?></td>
                <td class="<?php if($row->bill_type == 'debited'){ echo "debited"; } else{ echo "credited";}?>"><?php echo $row->balance_bill; ?></td>
                <td><?php echo $row->updated_on; ?></td>
                <!-- <td>
                  <button class="btn btn-primary btn-xs" data-title="edit" data-toggle="modal" title="Click to edit" onclick="show_material(<?php echo $row->id;?>)"><i class="glyphicon glyphicon-pencil"></i></button>&nbsp;
                  <button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" title="Click to delete" onclick="delete_material(<?php echo $row->id;?>)" ><span class="glyphicon glyphicon-trash"></span></button>
                </td> -->
              </tr>
            <?php  } } $i++;}?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!--edit modal box-->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" class="form-horizontal" id="edit_form" action="<?php echo site_url('/Balance/ledgerBalance');?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title custom_align" id="Heading">Balance Details:</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="control-label col-sm-4">Product Name</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="e_matname">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-4">HSN</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="e_hsn">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-4">Batch No</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="e_batch">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-4">quantity</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="e_qnty">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-4">Rate</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="e_rate">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-4">Invoice</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="e_invoice">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-4">Challan</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="e_challan">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-4">Customer</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="e_vendor">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-4">Last Amount</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="e_lastBal">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-4">Bill Amount</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="e_bill">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-4">Paid Amount</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="e_paid">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-4">New Amount</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="e_newAmount">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-4">Payment Mode</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="e_mode">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-4">Transaction No</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="e_trans">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-4">Cheque No</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="e_cheque">
          </div>
        </div>
      </div>

      <div class="modal-footer ">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> No</button>
      </div>
    </form>
    </div>
  </div>
</div>
<!--ends edit modal-->
<!--delete-->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="delete_form" method="post" action="<?php echo site_url('/Material/deleteMaterial');?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title custom_align" id="Heading">Delete this Material</h4>
      </div>

      <div class="modal-body">
        <div class="alert alert-danger">
          <input type="hidden" name="row_id" value="" id="row_id">
          <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete?
        </div>
        <p class="statusMsgDel text-center">  </p>
      </div>

      <div class="modal-footer ">
        <button type="button" class="btn btn-success" id="yes" ><span class="glyphicon glyphicon-ok-sign"></span> Yes</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> No</button>
      </div>
    </form>
    </div>
    <!-- /.modal-content -->
  </div>
</div>
<!--ends delete-->

<!--footer section-->
<!-- <div class="container" style="height: 100px;">&nbsp;<br /></div>
<div class="container-fluid footer">
    <div class="row">
      <div class="col-sm-1">&nbsp;</div>
      <div class="col-sm-5">Made with <span style="color: #e25555;"><i class="glyphicon glyphicon-heart"></i></span> By Shareef Ansari</div>
      <div class="col-sm-1">&nbsp;</div>
      <div class="col-sm-5">
        <p><i class="glyphicon glyphicon-envelope"></i> ashareefeng@gmail.com &nbsp;&nbsp;&nbsp;<i class="glyphicon glyphicon-earphone"></i>  90295 79146</p>
      </div>
    </div>
</div> -->
<!--END footer section-->

</div><!--close main div-->

<script type="text/javascript">
var mytable;
$(document).ready(function(){
  mytable = $('#datatable').dataTable({"pageLength": 25});
  $("[data-toggle=tooltip]").tooltip();

  setTimeout(function() {
    $(".show_hide").alert('close');
  }, 4000);


  $("#download_ledger").submit(function(e){
      e.preventDefault();
      $.ajax({
        url: $(this).attr('action'),
        type: "POST",
        dataType: "json",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache:false,
        async:false,
        success:function(resp){
          if( resp.status == 'failed')
          {
            $('#result_box').empty();
            $('#result_box').html('<span class="text-danger">'+resp.result+'</span>');
          }
          else if(resp.status == 'passed')
          {
            $('#result_box').empty();
            $("#download_ledger")[0].reset();
              var $a = $("<a>");
              $a.attr("href",resp.result);
              $("body").append($a);
              $a.attr("download", resp.filename);
              $a[0].click();
              $a.remove();
          }
        }
      });
  });


});

// show details of each material
function show_material(material_id)
{
  var mat_id = material_id;
  $.ajax({
    type:'POST',
    url: $("#edit_form").attr("action"),
    data:{mat_id:mat_id},
    dataType: "json",
    success:function(msg){
      console.log(msg);
      //return;
      if( msg.status =='passed' )
      {
        $('#e_matname').val(msg.result.product_name);
        $('#e_hsn').val(msg.result.hsn);
        $('#e_batch').val(msg.result.batch_no);
        $('#e_qnty').val(msg.result.quantity);
        $('#e_rate').val(msg.result.rate);
        $('#e_invoice').val(msg.result.invoice);
        $('#e_challan').val(msg.result.challan);
        $('#e_vendor').val(msg.result.bakery_name);
        $('#e_lastBal').val(msg.result.last_amount);
        $('#e_bill').val(msg.result.bill_amount);
        $('#e_paid').val(msg.result.paid_amount);
        $('#e_newAmount').val(msg.result.new_amount);
        $('#e_mode').val(msg.result.payment_mode);
        $('#e_trans').val(msg.result.transaction_no);
        $('#e_cheque').val(msg.result.cheque_no);
        $('#edit').modal('show');
      }
      else
      {
        $('.statusMsgDel').html('<span style="color:red;">Some problem occurred, please try again.</span>');
      }
    }
  });
}

function delete_material(row_id)
{
  var row_id = row_id;
  $('#delete').modal('show');

  $("#yes").click(function(){
    $.ajax({
      type:'POST',
      url: $("#delete_form").attr("action"),
      data:'row_id='+row_id,
      dataType: "json",
      beforeSend: function () {
        $('.btn-default').attr("disabled","disabled");
        $('.modal-body').css('opacity', '.5');
      },
      success:function(msg){
        if( msg.status =='passed')
        {
          $('.statusMsgDel').empty();
          $('.statusMsgDel').html('<span class="text-success">'+msg.result+'</span>');
          setTimeout(function(){
            $('#delete').modal('hide');
            location.reload();
          }, 4000);
        }
        else
        {
          $('.statusMsgDel').html('<span style="color:red;">Some problem occurred, please try again.</span>');
        }
        $('.btn-default').removeAttr("disabled");
        $('.modal-body').css('opacity', '');
      }
    });
  })
}

</script>
