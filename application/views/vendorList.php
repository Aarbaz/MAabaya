<div class="container-fluid" id="bg-color"><br /></div>

<div class="container-fluid">
  <div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				  <h4><?php echo ucwords($username).', ';?><small><?php echo  date('d F, Y');?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Vendor/logout');?>">Log Out</a></span></h4>
				</div>
				
        <div class="panel-body">      
          <p>
            <a class="btn btn-primary btn-sm" href="<?php echo base_url('/index.php/Vendor/add_new');?>">Add New Vendor</a>
          </p><br />
          <?php
            if( $this->session->flashdata('success') ){
              echo '<div class="alert alert-success show_hide" role="alert"><p class="text-center">'.$this->session->flashdata('success').'</p></div>';
            }
          ?>          
          <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
    				<thead>
  						<tr>
  							<th>Name</th>
  							<th>Phone</th>
  							<th>GSTIN</th>
  							<th>Address 1</th>
                <th>Address 2</th>
                <th>Balance</th>
                <th>Action</th>                
  						</tr>
					  </thead>					       
					  <tbody>
            <?php 
            foreach ($vendors->result() as $row){
              $adds2 =  $row->area. ','.  $row->city;
              $adds2 =  trim($adds2,',');
            ?>
						  <tr>
  							<td> <?php echo $row->vendor_name; ?> </td>
  							<td> <?php echo $row->phone1; ?> </td>
                <td> <?php echo $row->gst; ?> </td>             
                <td> <?php echo $row->address; ?> </td>             
  							<td> <?php echo $adds2; ?> </td>
                <td> <?php echo $row->debit_balance; ?> </td>
  						  <td>
                  <a class="btn btn-primary btn-xs" title="Click to edit" href="<?php echo base_url('/index.php/Vendor/edit/').$row->vendor_id;?>"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;
                  <button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" title="Click to delete" onclick="delete_vendor(<?php echo $row->vendor_id;?>)" ><span class="glyphicon glyphicon-trash"></span></button>
                </td>             
						  </tr>
            <?php } ?>  
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
      <form id="delete_form" method="post" action="<?php echo site_url('/Vendor/delete_vendor');?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title custom_align" id="Heading">Delete Vendor</h4>
      </div>
      
      <div class="modal-body">
        <div class="alert alert-danger">
          <input type="hidden" name="row_id" value="" id="row_id">
          <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Record?
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
<div class="container" style="height: 100px;">&nbsp;<br /></div>
<div class="container-fluid footer">
    <div class="row">
      <div class="col-sm-1">&nbsp;</div>
      <div class="col-sm-5">Made with <span style="color: #e25555;"><i class="glyphicon glyphicon-heart"></i></span> By Shareef Ansari</div>   
      <div class="col-sm-1">&nbsp;</div>   
      <div class="col-sm-5">
        <p><i class="glyphicon glyphicon-envelope"></i> ashareefeng@gmail.com &nbsp;&nbsp;&nbsp;<i class="glyphicon glyphicon-earphone"></i>  90295 79146</p>
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

});

function delete_vendor(row_id)
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
