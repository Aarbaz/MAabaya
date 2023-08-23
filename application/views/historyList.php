<div class="container-fluid" id="bg-color"><br /></div>

<div class="container-fluid">
<div class="card">
  <ul id="pices-tabs" class="nav nav-tabs" role="tablist" >
    <li class="nav-item">
        <a class="nav-link active" href="#balance" role="tab" data-toggle="tab">Balance</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#material" role="tab" data-toggle="tab">Material</a>
    </li>
  </ul>

    <!-- Tab panes -->
    <div class="tab-content">
      <div role="tabpanel" class="tab-pane fade in active" id="balance">
        <div class="row">
          <div class="col-sm-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4><?php echo ucwords($username).', ';?><small><?php echo  date('d F, Y');?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Balance/logout');?>">Log Out</a></span></h4>
              </div>
              <?php
              // print_r($history->result());
               ?>
              <div class="panel-body">
                <p>
                  <form id="download_ledger" class="form-inline"  enctype="multipart/form-data" method="POST" action="<?php echo base_url('/index.php/Pices/downloadBalance');?>">
                    <div class="form-group">
                      <label for="customerName">Customer: </label>
                      <select name="customerName" id="customerName" class="form-control">
                        <option value="">-- select --</option>
                        <?php foreach ($custList->result() as $row){
                          echo '<option value="'.$row->id.'" '.set_select('customerName',$row->id).'>'.$row->name.'</option>';
                        } ?>
                      </select>
                    </div>
      
                    <div class="form-group">
                      <label>From:                 </label>
                      <select class="form-control" name="frm_mth" id="frm_mth">
                        <option value="">--Month--</option>
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                      </select>
                    </div>
      
                    <div class="form-group">
                      <select class="form-control" name="frm_yr" id="frm_yr">
                        <option value="">--Year--</option>
                        <?php
                        $y = date('Y');
                        $dif = $y-5;
                        for($i = $y; $i >= $dif; $i--)
                        {
                          echo '<option value="'.$i.'">'.$i.'</option>';
                        }
                        ?>
                      </select>
                    </div>
      
                    <div class="form-group">
                      <label>To:                 </label>
                      <select class="form-control" name="to_mth" id="to_mth">
                        <option value="">--Month--</option>
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                      </select>
                    </div>
      
                    <div class="form-group">
                      <select class="form-control" name="to_yr" id="to_yr">
                        <option value="">--Year--</option>
                        <?php
                        $y = date('Y');
                        $dif = $y-5;
                        for($i = $y; $i >= $dif; $i--)
                        {
                          echo '<option value="'.$i.'">'.$i.'</option>';
                        }
                        ?>
                      </select>
                    </div>
      
                    <div class="form-group">
                      <button type="submit" class="btn btn-success">Download Balance</button>
                    </div>
                   <!--  <div class="form-group">
                      &nbsp;&nbsp;<a class="btn btn-primary btn-sm" href="<?php echo base_url('/index.php/Balance/ledger');?>">Add New</a>
                    </div> -->
                  </form>
                  <div id="pdfViewer">
      
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
                      <th>Invoice</th>
                      <!-- <th>Challan</th> -->
                      <th>Customer</th>
                      <th>From</th>
                      <th>Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
      
                  <tbody>
                  <?php $i = 1; 
       
                  foreach ($ledger_list->result() as $row){ 
                    $entryFrom = $row->entry_from;
                    if ($entryFrom == 1) {
                      $filePath = 'purchaser';
                    }else if ($entryFrom == 2) {
                      $filePath = 'maker';
                    }else if ($entryFrom == 3) {
                      $filePath = 'pices_invoice';
                    }else{
                      $filePath = '';
                    }
                    ?>
                    <tr>
                      
                      <!-- $entryFrom = $hrow->entry_from; -->
      
                      <td><?php echo $i; ?></td>
                      <td><?php echo $row->invoice; ?></td>
                      <!-- <td><?php echo $row->challan; ?></td> -->
                      <td><?php echo $row->name; ?></td>
                      <td><?php echo $filePath; ?></td>
                      <td><?php echo $row->dated; ?></td>
                      <td> <a class="btn btn-primary btn-xs" title="Click to download" href="<?php echo base_url('/index.php/History/downloadSinglePdf/').$filePath.'/'.rawurlencode($row->customer_id).'/'.$row->invoice ?>">
                      <i class="glyphicon glyphicon-download"></i></a>
                    </td>
                    </tr>
                  <?php $i++; }  ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div role="tabpanel" class="tab-pane fade" id="material">
      <div class="row">
          <div class="col-sm-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4><?php echo ucwords($username).', ';?><small><?php echo  date('d F, Y');?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Balance/logout');?>">Log Out</a></span></h4>
              </div>
              <?php
              // print_r($history->result());
               ?>
              <div class="panel-body">
                <p>
                  <form id="download_ledger" class="form-inline"  enctype="multipart/form-data" method="POST" action="<?php echo base_url('/index.php/History/get_history_by_material_id');?>">
                  
                    <div class="form-group">
                    <select name="material_id" id="material_id" class="required items form-control">
                          <option value="">--select Product--</option>
                          <?php
                          foreach ($materialList->result() as $row) {
                              $mat = explode(',', $row->material_name);
                              $cnt = count($mat);
                              for ($i = 0; $i < $cnt; $i++) {
                                  echo '<option label="" data-material-id="' . $row->id . '" value="' . $row->id . '" ' . set_select("material_id", $mat[$i]) . '>' . $mat[$i] . '</option>';
                              }
                          } ?>
                          <input type="hidden" name="" id="material_ids" value="">
                          <input type="hidden" name="all_material_ids[]" id="all_material_ids" value="">
                    </select>
                    </div>
                    <div class="form-group">
                      <label>From Date:                 </label>
                      <input type="date" id="from_date" name="from_date" class="form-control"/>
                    </div>
                    <div class="form-group">
                      <label>To Date:                 </label>
                      <input type="date" id="to_date" name="to_date" class="form-control"/>
                    </div>
      
                    <div class="form-group">
                      <button type="submit" class="btn btn-success">Download History</button>
                    </div>
                   <!--  <div class="form-group">
                      &nbsp;&nbsp;<a class="btn btn-primary btn-sm" href="<?php echo base_url('/index.php/Balance/ledger');?>">Add New</a>
                    </div> -->
                  </form>
                  <div id="pdfViewer">
      
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
                      <th>Invoice</th>
                      <!-- <th>Challan</th> -->
                      <th>Customer</th>
                      <th>From</th>
                      <th>Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
      
                  <tbody>
                  <?php $i = 1; 
       
                  foreach ($ledger_list->result() as $row){ 
                    $entryFrom = $row->entry_from;
                    if ($entryFrom == 1) {
                      $filePath = 'purchaser';
                    }else if ($entryFrom == 2) {
                      $filePath = 'maker';
                    }else if ($entryFrom == 3) {
                      $filePath = 'pices_invoice';
                    }else{
                      $filePath = '';
                    }
                    ?>
                    <tr>
                      
                      <!-- $entryFrom = $hrow->entry_from; -->
      
                      <td><?php echo $i; ?></td>
                      <td><?php echo $row->invoice; ?></td>
                      <!-- <td><?php echo $row->challan; ?></td> -->
                      <td><?php echo $row->name; ?></td>
                      <td><?php echo $filePath; ?></td>
                      <td><?php echo $row->dated; ?></td>
                      <td> <a class="btn btn-primary btn-xs" title="Click to download" href="<?php echo base_url('/index.php/History/downloadSinglePdf/').$filePath.'/'.rawurlencode($row->customer_id).'/'.$row->invoice ?>">
                      <i class="glyphicon glyphicon-download"></i></a>
                    </td>
                    </tr>
                  <?php $i++; }  ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

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


  $("#download_ledger2").submit(function(e){
      e.preventDefault();
      var formUrl = "";
      $.ajax({
        url: formUrl,
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
            console.log(resp);
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
