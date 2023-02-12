<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AquaHey:: Brand List</title>
        <meta name="description" content="AquaHey add new brand">
        <meta name="author" content="Shareef Ansari">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
        <!--<link href="" rel="stylesheet">-->
        <script src="<?php echo base_url('assets/js/jquery-3.3.1.min.js'); ?>"></script>
    
    <style type="text/css">
    body {
        margin: 0; padding: 0;
    }
    #bg-image {
        width: 100%; height: 100%;
        margin : 0;
        padding : 0;
        top: 0; left: 0;
        position: absolute;
        background-color: #69bcf4; background: linear-gradient(#69bcf4, #30cc8b);
    }
    .box {
        width: 60%;
        margin: 0 auto;
        box-shadow: 0px 8px 20px 0px rgba(0, 0, 0, 0.15);
        background: #fff;
        border-radius: 10px;
        margin-top : 20%;
    }
    .box .box-body {
        padding: 20px;
    }
    
    
     .navbar-brand {
        height: 80px;
    }
    .navbar-nav > li > a {
        padding-top: 30px;
        padding-bottom: 30px;
    }
    .navbar-toggle {
      padding: 10px;
      margin: 25px 15px 25px 0;
    }
    .fixed-footer{
        width: 100%;
        position: fixed;        
        background: #333;
        padding: 10px 0;
        color: #fff;
    }
    .fixed-footer{
        bottom: 0;
    } 
    </style>
</head><body>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="">AqueHay</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="active"><a href="">Brand</a></li>
                    <li><a href="">Product</a></li>
                    <li><a href="">User Guide</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>  <!-- Begin page content -->
    <div class="container">
      <div class="page-header"><br /></div>
    </div>
<!--add brand form section-->
<div class="container">
    <div class="row">
        <div class="col-sm-2"><br /></div>
        <div class="col-sm-8">
            <h5 class="tex-center text-primary">Please enter brand name and brand banner...</h5>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5>Add new brand</h5>
                    <?php echo date('Y-m-d H:i:s'); ?>
                </div>
                <div class="panel-body">
                    <form id="brandForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="bname">Brand Name:</label>
                            <input type="text" class="form-control" id="bname" name="bname">
                        </div>
                        <div class="form-group">
                            <label for="bname">Brand Name:</label>
                            <input type="text" class="form-control" id="sname" name="sname">
                        </div>
                        <div class="form-group">
                            <label for="blogo">Brand Banner:</label>
                            <input type="file" class="form-control" id="userfile" name="userfile">
                        </div>
                        <div class="form-group">
                            <button type="submit" id="sbtn" class="btn btn-primary">Submit</button>
                            &nbsp;&nbsp;&nbsp;<span id="form_status"></span>
                        </div>
                        <div id="resp_status"></div>
                    </form> 
                </div>
            </div>
        </div>
        <div class="col-sm-2"><br /></div>
    </div>
</div>
<!--end brand add-->
    <div class="fixed-footer">
        <footer class="footer">
            <div class="container">
                <p class="text-muted">Place sticky footer content here.</p>
            </div>
        </footer>
    </div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#brandForm').submit(function(e){
            e.preventDefault();
            var isValid = true;
            var name_reg = /^[A-Za-z\s]+$/;
            var bname = $('#bname').val();
            if(bname == '') // || !(bname.match(name_reg)) )
            {
              alert('Please select a brand');
              isValid = false;    return;
            }
            if($('#userfile').val()== '')
            {
                alert('select logo');
                isValid = false; return;
            }

            if(isValid)
            {
                $.ajax({
                    url:'<?php echo base_url();?>index.php/Customer/addBrand',
                    type:"post",
                    datatype : "json",
                    data:new FormData(this), //this is formData
                    processData:false,
                    contentType:false,
                    cache:false,
                    async:false,
                    beforeSend: function () {
                        $('#sbtn').attr('disabled', true);
                        $('#form_status').html('<i class="text-success">Uploading... Please wait</i>');
                    }, 
                    success:function(msg){
                        $('#sbtn').attr('disabled', false);
                        $('#form_status').html('');
                        console.log(msg);
                        //return;
                        var resp = JSON.parse(msg);
                        if( resp.status == 'failed')
                        {
                            $('#resp_status').html('');
                            $.each(resp.result, function(name, item) {
                                item = item.replace(/<.*?>/gm, '');
                                $('#resp_status').append('<p class="text-danger text-center">'+item+'</p>');      
                            });                                          
                        }
                        else if(resp.status == 'passed')
                        {
                            $('#resp_status').html('');
                            $('#resp_status').append('<p class="text-success text-center">Brand added successfully!</p>').fadeOut(5000);  
                            $('#brandForm input').not(':submit').val('');             
                        }
                    }
                });
            }
        }); //form submit
    })    
</script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script> -->
</body>
</html>