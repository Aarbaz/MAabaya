<html>
<head>
  <title>Material History</title>
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
<body style="border:1px solid #000;">

  <div>
    <img src="<?php echo base_url('assets/images/MAabaya-logo.png');?>">
  </div>

  <table  style="width: 100%">
    <tr><td colspan="4">&nbsp;</td></tr>
    <tr style="border-bottom:1px solid #eee;">
      <td width="20%"> Material Name </td>
      <!-- <td width="35%"> <?php echo $encoded_material_name; ?></td> -->
    </tr>
    <tr style="border-bottom: 2px solid #000;">
      <td> INVOICE DATE </td>
      <td> <?php echo $date_range; ?> </td>
    </tr>
    <tr><td colspan="4">&nbsp;</td></tr>
  </table>

  <div><br /></div>

<table border="1" cellspacing="0" cellpadding="3" width="100%">
  <tr style="background-color: #e1e1e9">
    <th style="width: 5%">Sr. No</th>
    <th style="width: 10%">Type</th>
    <th style="width: 10%">Invoice No</th>
    <th style="width: 15%">Date</th>
    <th style="width: 10%">From</th>
    <th style="width: 15%">To</th>
    <th style="width: 15%">Fabric</th>
    <th style="width: 10%">In/Out</th>
    <th style="width: 15%">Balance</th>
  </tr>
        

    <tbody>
        <?php 
          $i = 1;
          foreach ($data_pdf as $row): 
            $jsonData = $row->json_data  ;
            $dataArray = json_decode($jsonData, true);
            $invoiceNo = '-';
            $person_id = '-';
            $to = '-';
            $sign = '';
            
            // get name of customer
            $user_id=$row->user_id;
            $this->db->select('*');
            $this->db->from('customers');
            $this->db->where('id',$user_id);
            $query = $this->db->get();
            $result = $query->row();
            $person_name = $result->name;

            //get material name
            $material_id = $row->material_id;
            $this->db->select('*');
            $this->db->from('material');
            $this->db->where('id',$material_id);
            $query = $this->db->get();
            $result = $query->row();
            $material_name = $result->material_name;

            if ($row->entry_from == 1) {
              $type = "Purchaser";
              if (isset($dataArray) && isset($dataArray['purchaser_no'])) {
                # code...
                $invoiceNo = $dataArray['purchaser_no'] ? $dataArray['purchaser_no'] : '-';
                $person_id = $dataArray['purchaser_owner_id'] ? $dataArray['purchaser_owner_id'] : '-';
                $this->db->select('*');
                $this->db->from('customers');
                $this->db->where('id',$person_id);
                $query = $this->db->get();
                $result = $query->row();
                $person_name = $result->name;
                $to = "Shop";
              }
            }elseif($row->entry_from == 2) {
              $type = "Maker";
              $sign = '-';
              if (isset($dataArray['maker_no'])) {
                # code...
                $invoiceNo = $dataArray['maker_no'] ? $dataArray['maker_no'] : '-';
                $person_id = $dataArray['making_owner_id'] ? $dataArray['making_owner_id'] : '-';
                $this->db->select('*');
                $this->db->from('customers');
                $this->db->where('id',$person_id);
                $query = $this->db->get();
                $result = $query->row();
                $to = $result->name;
                $person_name = "Shop";
              }
            }elseif ($row->entry_from == 3 ) {
              $type = "Pices";
            }
          ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $type; ?></td>
            <td><?php echo $invoiceNo; ?></td>
            <td><?php echo $row->created_at; ?></td>
            <td><?php echo $person_name; ?></td>
            <td><?php echo $to; ?></td>
            <td><?php echo $material_name; ?></td>
            <td><?php echo $row->in_out_qnty; ?></td>
            <td><?php echo $row->stock_quantity; ?></td>
        </tr>
        <?php $i++; endforeach;?>
    </tbody>
</table>
<div>

    </div>

</body>
</html>
