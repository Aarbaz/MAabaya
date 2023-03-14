<div class="container-fluid" id="bg-color"><br /></div>
<div class="container-fluid">
   <div class="row">
      <div class="col-sm-12">
         <div class="panel panel-default">
            <div class="panel-heading">
               <h4><?php echo ucwords($username).', ';?><small><?php echo  date('d F, Y');?></small><span class="text-sm pull-right"><a href="<?php echo site_url('Making/logout');?>">Log Out</a></span></h4>
            </div>
            <div class="panel-body">
               <p>
                  <a class="btn btn-primary btn-sm" href="<?php echo base_url('/index.php/Making/add_new');?>">Add New Making</a>
               </p>
               <br />
               <?php
                  if( $this->session->flashdata('success') )
                  { echo '<div class="alert alert-success show_hide" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p class="text-center"><strong>Success!</strong> '.$this->session->flashdata('success').'</p></div>'; }
                  ?>
               <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                  <thead>
                     <tr>
                        <th>Sr No</th>
                        <th>Master Name</th>
                        <th>Material/Quantity</th>
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
                        <td><?php
                           $detail = $this->Customer_model->get_customer_byID($row->making_owner_id);
                            echo $detail->name; ?></td>
                        <td>
                           <?php
                              $material_ids = explode(',', $row->material_id);
                              $stk = explode(',', $row->stock);
                              $cntm= count($material_ids);

                              for ($m=0; $m < $cntm; $m++) {

                               foreach ($matList->result() as $col){
                                  if ($material_ids[$m] == $col->id) {
                                    echo $col->material_name.' - '.$stk[$m].' Meters';
                                  }
                               }
                               ?>
                           <br>
                           <?php } ?>
                        </td>
                        <td>
                          <!-- <a class="btn btn-primary btn-xs" title="Click to download" href="<?php echo base_url('/index.php/Invoice/download_pdf/').rawurlencode($row->bakery_name).'/'.$row->invoice_no;?>"><i class="glyphicon glyphicon-download"></i></a>&nbsp; -->
                          <a class="btn btn-primary btn-xs" title="Click to download" href="<?php echo base_url('/index.php/Making/download_pdf/').rawurlencode($row->id).'/'.$row->id;?>"><i class="glyphicon glyphicon-download"></i></a>&nbsp;

                           <!-- <a class="btn btn-primary btn-xs" title="Click to download" href=""><i class="glyphicon glyphicon-download"></i></a>&nbsp; -->
                           <!-- <a class="btn btn-primary btn-xs" title="Click to edit" href="<?php echo base_url('/index.php/Making/edit/').$row->id;?>"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;
                           <button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" title="Click to delete" onclick="delete_making(<?php echo $row->id;?>)" ><span class="glyphicon glyphicon-trash"></span></button> -->
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
         <form id="delete_form" method="post" action="<?php echo site_url('/Making/deleteMaking');?>">
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
               <button type="button" class="btn btn-success" id="yes" ><span class="glyphicon glyphicon-ok-sign"></span> Yes</button>
               <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> No</button>
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

   function delete_making(row_id)
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
