<div class="container-fluid" id="bg-color"><br /></div>

<div class="container-fluid">
  <div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				  <h4><?php echo ucwords($username).', ';?><small><?php echo  date('d F, Y');?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Customer/logout');?>">Log Out</a></span></h4>
				</div>

        <div class="panel-body">
          <!-- <p>
            <a class="btn btn-primary btn-sm" href="<?php echo base_url('/index.php/Customer/add_new');?>">Add New Customer</a>
          </p><br /> -->
          <?php
            if( $this->session->flashdata('success') )
            { echo '<div class="alert alert-success show_hide" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p class="text-center"><strong>Success!</strong> '.$this->session->flashdata('success').'</p></div>'; }
          ?>

          <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
    				<thead>
  						<tr>
  							<th>Sr No</th>
  							<th>Customer Name</th>
  							<!-- <th>Phone No</th> -->
                <th>Type</th>
                <!-- <th class="hide">Last Amount</th>     -->
                <th>Action</th>
  						</tr>
					  </thead>


					  <tbody>
              <?php
              if(isset($customer)){
                $i = 1;
                foreach ($customer->result() as $row){  ?>
  						  <tr>
                  <td><?php echo $i; ?></td>
    							<td><?php echo $row->name; ?></td>

                  <!-- <td><?php echo $row->owner_phone; ?></td> -->
                  <td>

                    <!-- <?php echo $row->role.'<br />'; ?> -->
                    <?php
                      if ($row->role == '0') {
                        $name = 'Purchaser';
                        // code...
                      }
                      elseif ($row->role == '1' ) {
                        $name = 'Maker';

                      }
                      else {
                        // code...
                      }
                    echo $name;

                    ?>



                  </td>

    							<td>
                    <a class="btn btn-primary btn-xs" title="Click to edit" href="<?php echo base_url('/index.php/Customer/edit/').$row->id;?>"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;

                  <button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" title="Click to delete" onclick="delete_customer(<?php echo $row->id;?>)" ><span class="glyphicon glyphicon-trash"></span></button></td>
                </tr>
              <?php $i++; }
              } ?>
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
      <form id="delete_form" method="post" action="<?php echo site_url('/Customer/deleteCustomer');?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title custom_align" id="Heading">Delete this Customer</h4>
      </div>

      <div class="modal-body">
        <div class="alert alert-danger">
          <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Record?
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
<!--footer section-->
<div class="container" style="height: 100px;">&nbsp;<br /></div>

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

});

function delete_customer(row_id)
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
