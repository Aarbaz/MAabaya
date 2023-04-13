<html>
<head>
  <title>Invoice Pices</title>
  <style type="text/css">
    .center {text-align: center;}
    /*table.hdr tr {padding: 10px;}*/
   /*  table.hdr tr td {background-color: #e1e1e9; padding: 10px} */
    #tax_table tr th.right{ text-align: right; }
    /*table td#adds {text-align: justify; } */
    .bold{
      font-weight:bold;
    }
  </style>
</head>
<body>

  <div>
    <img src="<?php echo base_url('assets/images/MAabaya-logo.png');?>">
  </div>

  <table  style="width: 100%">
    <tr><td colspan="4">&nbsp;</td></tr>
    <tr style="border-bottom:1px solid #eee;">
      <td width="20%"> Master Name </td>
      <td width="35%"> <?php echo $customer; ?></td>
      <td width="20%"> INVOICE NO.</td>
      <td width="25%"> <?php echo $invoice_no; ?> </td>
    </tr>
    <tr style="border-bottom: 2px solid #000;">
      <!-- <td> ADDS. </td>
      <td id="adds"> <?php echo $customer_address; ?> </td> -->
      <td> INVOICE DATE </td>
      <td> <?php echo date('d F, Y'); ?> </td>
    </tr>
   <!--  <tr>
      <td> BUYER'S GST </td>
      <td> <?php echo $gst; ?> </td>
      <td> DATE OF SUPPLY </td>
      <td> <?php if($date_of_supply){ echo date('d F, Y', strtotime($date_of_supply)); }?> </td>
    </tr> -->
    <!-- <tr>
      <td>&nbsp;</td> <td>&nbsp;</td>
      <td> PLACE OF SUPPLY </td>
      <td id="adds"> <?php echo $place_of_supply; ?> </td>
    </tr> -->
  <!--   <tr>
      <td> &nbsp;</td> <td>&nbsp;</td>
      <td> OTHER </td>
      <td> <?php echo $other_notes; ?>  </td>
    </tr>  -->
    <tr><td colspan="4">&nbsp;</td></tr>
  </table>

  <div><br /></div>

<table border="1" cellspacing="0" cellpadding="3" width="100%">
  <tr style="background-color: #e1e1e9">
    <th style="width: 10%">Sr. No</th>
    <th style="width: 45%">PARTICULARS</th>
    <th style="width: 10%">Pcs</th>
    <!-- <th style="width: 10%">QNTY</th> -->
    <th style="width: 15%">RATE</th>
    <th style="width: 15%">AMOUNT</th>
  </tr>
  <?php
    /* $mat = explode(',', $product_name);
    // $hsn = explode(',', $hsn);
    $qnty = explode(',', $qnty);
    $rate = "";
    $amount = " ";
    $items = array('mat'=> $mat,'qnty'=>$qnty,'hsn'=>$hsn);
    $len = count($items['mat']);


    print_r( $items );
    $items2 = array();
    for ($i=0; $i < $len; $i++)
    {
      $newArray = array();
      $newArray[] = $items['mat'][$i];
      // $newArray[] = $items['hsn'][$i];
      $newArray[] = $items['qnty'][$i];
      $items2[] = $newArray;
    }

    $j = 1;
    $all_items = count($items2);

    $items2 = array_filter(array_map('array_filter', $items2)); */
   /*  if($all_items > 0)
    { */
      //print_r($json_data['data_json']);
      $array_data = json_decode($json_data['data_json'], true);
      //for($i=0;$i<$all_items;$i++){?>
        
        <?php $j=1;
        $grandPiece_total = 0;
        $grandAmount_total = 0;
            foreach ($array_data as $data) {
             
              $design_number = $data['design_number'][0];
              $this->db->select('id,design_num');
              $this->db->from('designs');
              $this->db->where('id',$design_number);
              $query = $this->db->get();
              $design_data= $query->row();

              $materials_ids = $data['materials_ids'];
              $total_materials = $data['total_material'];
              $total_piece = $data['total_piece'][0];
              $grandPiece_total += $total_piece;
              $labour_charge = $data['labour_charge'][0];
              
              
              echo "<tr>";
              echo "<td>$j</td>";
              echo "<td> $design_data->design_num<br>";
              $jk=0;
              $tot_am=0;
              foreach ($materials_ids as $material_id) {
                $this->db->select('*');
                $this->db->from('material');
                $this->db->where('id', $material_id);

                $query = $this->db->get();
                $results = $query->result();

              //  print_r($results);

              //   print_r($total_materials);
              foreach ($results  as $key => $value) {
                # code...
            /*     echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
               echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>"; */
                $tot_am=floatval($tot_am) + floatval($total_materials[$jk]);
                echo "<table>";
                echo "<tr>";
                echo " <td>";
                echo $value->material_name;
                echo " </td>";
                echo " <td>";
                echo $total_materials[$jk];
                echo " </td>";
                echo "</tr>";
                echo "</table>";
               $jk++;
                


              
              }
              
                // foreach ($results as $material) {
                //   echo $material->material_name ."<br>";
                // }
              
              }
             
              /* echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; */
              
                echo "Total";
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp ;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo number_format((float)$tot_am, 2, '.', '')."</td>";
              // echo " </td>";
              echo "<td>$total_piece</td>";
              
              echo "<td>$labour_charge</td>";
              $tot_amount = $total_piece * $labour_charge;
              $grandAmount_total += $tot_amount;
              echo "<td>$tot_amount</td>";
              /* echo "<td>";
              foreach ($total_materials as $total_material) {
                echo $total_material;
              }
              echo "</td>"; */
              echo "</tr>";
              $j++;
            }
          ?>
         
         
         <tr>
           <td>&nbsp;</td>
           <th class="right">TOTAL</th>
           <td> <?php echo $grandPiece_total; ?></td>
           <td>&nbsp;</td>
           <td> <?php echo 'Rs. '. $grandAmount_total; ?></td>
  </tr> 
      <?php
        //$j++; }
    //} ?>
</table>


<div>

    </div>

</body>
</html>
