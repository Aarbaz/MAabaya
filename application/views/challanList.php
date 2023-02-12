<div class="container-fluid" id="bg-color"><br /></div>

<div class="container-fluid">
  <div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				  <h4><?php echo ucwords($username).', ';?><small><?php echo  date('d F, Y');?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Challan/logout');?>">Log Out</a></span></h4>
				</div>
				
        <div class="panel-body">
          <p>
            <a class="btn btn-primary btn-sm" href="<?php echo base_url('/index.php/Challan/create');?>">Create Challan</a>
          </p><br />
          <?php
            if( $this->session->flashdata('no_pdf') )
            {
              echo '<div class="alert alert-warning show_hide" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p class="text-center"><strong>Failed!</strong> '.$this->session->flashdata('no_pdf').'</p></div>';
            } 

            if( $this->session->flashdata('success') )
            {
              echo '<div class="alert alert-success show_hide" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p class="text-center"><strong>Success!</strong> '.$this->session->flashdata('success').'</p></div>';
            } 
             
          ?> 

          <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
    				<thead>
  						<tr>
  							<th>S.r No</th>
  							<th>Customer Name</th>
  							<th>Customer Address</th>
                <th>Challan No</th>
                <th>Amount</th>
                <th>Added Date</th>
                <th>Action</th>
  						</tr>
					  </thead>

					  <tbody>
              <?php
              if(isset($challan_list)){
                $i = 1;
                foreach ($challan_list->result() as $row){  ?>
  						  <tr>
                  <td><?php echo $i; ?></td>
    							<td><?php echo $row->bakery_name; ?></td>
    							<td><?php echo $row->bakery_address.', '.$row->bakery_area.', '.$row->bakery_city; ?></td>
    							<td><?php echo $row->challan_no; ?></td>  						
                  <td><?php echo $row->total; ?></td>
    							<td><?php echo date('d M, Y', strtotime($row->created_on) ); ?></td>
    						  <td>
                    <a class="btn btn-primary btn-xs" title="Click to download" href="<?php echo base_url('/index.php/Challan/download_pdf/'). rawurlencode($row->bakery_name).'/'.$row->challan_no;?>"><i class="glyphicon glyphicon-download"></i></a>&nbsp;

                  <button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" title="Click to delete" onclick="delete_challan(<?php echo $row->sr_no.",'".$row->bakery_name."','".$row->challan_no."'";?>)" ><span class="glyphicon glyphicon-trash"></span></button></td>
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
      <form id="delete_form" method="post" action="<?php echo site_url('/Challan/deleteChallan');?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title custom_align" id="Heading">Delete</h4>
      </div>
      
      <div class="modal-body">
        <div class="alert alert-danger">
          <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete?
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
<div class="container-fluid footer">
  <div class="row">
    <div class="col-sm-1">&nbsp;</div>
    <div class="col-sm-5">Made with <span style="color: #e25555;"><i class="glyphicon glyphicon-heart"></i></span> By Shareef Ansari</div>   
    <div class="col-sm-1">&nbsp;</div>   
    <div class="col-sm-4">
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

function delete_challan(row_id, bakery_name, challan_no)
{
  var row_id = row_id;
  var bakery_name = bakery_name;
  var challan_no = challan_no;
  $("#delete").modal("show");
  $("#yes").click(function(){
    $.ajax({
      type:'POST',
      url: $("#delete_form").attr("action"),
      data:"row_id="+row_id+"&bakery_name="+bakery_name+"&challan_no="+challan_no,
      dataType: "json",
      beforeSend: function () {
        $('.btn-default').attr("disabled","disabled");
        $('.modal-body').css('opacity', '.5');
      },
      success:function(resp){
        if( resp.status == 'passed'){
          $('.statusMsgDel').empty();                    
          $('.statusMsgDel').html('<span style="color:green;">'+resp.result+'</span>');                    
          setTimeout(function(){
            $('#delete').modal('hide');
            location.reload();
          }, 4000);
        }
        else if( resp.status == 'failed')
        {
          $('.statusMsgDel').empty(); 
          $('.statusMsgDel').html('<span style="color:red;">'+resp.result+'</span>');
        }
        $('.btn-default').removeAttr("disabled");
        $('.modal-body').css('opacity', '');               
      }
    });
  })
    
}
</script>


