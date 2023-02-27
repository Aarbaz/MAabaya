<div class="container-fluid" id="bg-color"><br /></div>

<div class="container-fluid">
  <div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
        <div class="panel-heading">
          <h4><?php echo ucwords($username).', ';?><small><?php echo  date('d F, Y');?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Product/logout');?>">Log Out</a></span></h4>
        </div>

        <div class="panel-body">
          <p>
            <a class="btn btn-primary btn-sm" href="<?php echo base_url('/index.php/Product/add_new');?>">Add New Material</a>
          </p><br />
          <?php
            if( $this->session->flashdata('success') )
            { echo '<div class="alert alert-success show_hide" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p class="text-center"><strong>Success!</strong> '.$this->session->flashdata('success').'</p></div>'; }
          ?>

          <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
    				<thead>
  						<tr>
              <th>Sr No</th>
              <th>Owner Name</th>
  							<th>Material Name</th>
                <th>Quantity</th>
                <th>Product Amount</th>
                <!-- <th>Expiry Date</th> -->
                <th>Total</th>
                <th>Action</th>
  						</tr>
					  </thead>

					  <tbody>
            <?php
            if(isset($products)){
              $i = 1;
              foreach ($products->result() as $row){  ?>
						  <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $row->owner_name; ?></td>
  							<td><?php echo $row->product_name; ?></td>
                <td><?php echo $row->stock; ?> Meters</td>
  							<td><?php echo $row->price; ?> Rs</td>
                <!-- <td><?php echo $row->prod_exp; ?></td> -->
  							<td><?php echo $row->total_amount; ?> Rs</td>
                <td>
                  <a class="btn btn-primary btn-xs" title="Click to download" href=""><i class="glyphicon glyphicon-download"></i></a>&nbsp;

                 <a class="btn btn-primary btn-xs" title="Click to edit" href="<?php echo base_url('/index.php/Product/edit/').$row->id;?>"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;
                  <button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" title="Click to delete" onclick="delete_product(<?php echo $row->id;?>)" ><span class="glyphicon glyphicon-trash"></span></button>
                </td>
						  </tr>
            <?php $i++; } } ?>
            </tbody>
          </table>
        </div>
    	</div>
    </div>
	</div>
</div>


<!--delete-->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="delete_form" method="post" action="<?php echo site_url('/Product/deleteProduct');?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title custom_align" id="Heading">Delete this Product</h4>
      </div>

      <div class="modal-body">
        <div class="alert alert-danger">
          <span class="glyphicon glyphicon-warning-sign"></span> Are you sure to delete this Record?
        </div>
        <p class="statusMsgDel text-center"></p>
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

</div><!--close main div-->

<script type="text/javascript">
var mytable;
$(document).ready(function(){
  mytable = $('#datatable').dataTable({"pageLength": 25});
  $("[data-toggle=tooltip]").tooltip();

  setTimeout(function() {
    $(".show_hide").alert('close');
  }, 4000);

});

function delete_product(row_id)
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
              if( msg.status =='passed' )
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
                $('.statusMsgDel').empty();
                $('.statusMsgDel').html('<span class="text-danger">'+msg.result+'</span>');
              }
              $('.btn-default').removeAttr("disabled");
              $('.modal-body').css('opacity', '');
            }
        });
    })

}
</script>
