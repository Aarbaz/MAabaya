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
            <span class="btn btn-primary btn-sm" onclick="add_new_design()">Add New</span>
          </p><br />
          <?php
            if( $this->session->flashdata('success') )
            { echo '<div class="alert alert-success show_hide" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p class="text-center"><strong>Success!</strong> '.$this->session->flashdata('success').'</p></div>'; }
          ?>

          <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
    				<thead>
  						<tr>
                            <th>Sr No</th>
                            <th>Material Name</th>
                            <th>Action</th>
  						</tr>
					  </thead>

					  <tbody>
            <?php
            if(isset($materials)){
              $i = 1;
              foreach ($materials->result() as $row){  ?>
						  <tr>
                <td><?php  echo  $i;?></td>

                <td><?php echo $row->material_name; ?></td>
                <td>
                 <button class="btn btn-primary btn-xs editBtn" title="Click to edit" id="<?php echo $row->id;?>" ><i class="glyphicon glyphicon-pencil"></i></button>&nbsp;
                  <button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" title="Click to delete" onclick="delete_design(<?php echo $row->id;?>)" ><span class="glyphicon glyphicon-trash"></span></button>
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
      <form id="delete_form" method="post" action="<?php echo site_url('/Material/deleteMaterial');?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title custom_align" id="Heading">Delete this Material</h4>
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
<div class="modal fade" id="add_design" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="add_design_form" method="post" action="<?php echo site_url('/Material/edit_material');?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title custom_align" id="Heading">Add Material</h4>
      </div>

      <div class="modal-body">
            <div class="col-sm-12">
            <div class="form-group">
									<label class="control-label col-sm-3">Material Name</label>
									<div class="col-sm-9" id="design_holder">
                                        <input type="text" name="material_name" id="material_name" class="form-control" value="">
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
       url: '<?php echo site_url(); ?>/Material/fetch_by_id/'+id,
       data: {},
       async: false,
       //dataType: 'json',
       success: function(data){
           console.log(data);
           var data = JSON.parse(data);
          $("input[name=id]").val(data.id);
          $("input[name=material_name]").val(data.material_name);
       },
       error: function(){
           alert('Could not displaying data');
       },
   });
});
</script>
