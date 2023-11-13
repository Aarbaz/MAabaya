<html>

<head>
  <title>Material History</title>
  <style type="text/css">
    .center {
      text-align: center;
    }

    /*table.hdr tr {padding: 10px;}*/
    /*  table.hdr tr td {background-color: #e1e1e9; padding: 10px} */
    #tax_table tr th.right {
      text-align: right;
    }

    /*table td#adds {text-align: justify; } */
    .bold {
      font-weight: bold;
    }
  </style>
</head>

<body style="border:1px solid #000;">

  <div>
    <img src="<?php echo base_url('assets/images/MAabaya-logo.png'); ?>">
  </div>

  <table style="width: 100%">
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr style="border-bottom:1px solid #eee;">
      <td width="20%"> Design Number </td>
      <td width="35%">
        <?php
        foreach ($data_pdf as $row) {
          $design_id = $row->material_id;
          $this->db->select('*');
          $this->db->from('designs');
          $this->db->where('id', $design_id);
          $query = $this->db->get();
          $result = $query->row();
          $design_num = $result->design_num;

        }
        echo $design_num; ?>
      </td>
    </tr>
    <tr style="border-bottom: 2px solid #000;">
      <td> INVOICE DATE </td>
      <td>
        <?php echo $date_range; ?>
      </td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
  </table>

  <table border="1" cellspacing="0" cellpadding="3" width="100%">
    <tr style="background-color: #e1e1e9">
      <th style="width: 5%">Sr. No</th>
      <th style="width: 10%">Type</th>
      <th style="width: 10%">Invoice No</th>
      <th style="width: 15%">Date</th>
      <th style="width: 10%">From</th>
      <th style="width: 15%">To</th>
      <th style="width: 15%">Design Number</th>
      <th style="width: 10%">In/Out</th>
      <th style="width: 15%">Balance</th>
    </tr>


    <tbody>
      <?php
      $i = 1;
      foreach ($data_pdf as $row):
        $jsonData = $row->json_data;
        $dataArray = json_decode($jsonData, true);
        // get name of customer
        $user_id = $row->user_id;
        $this->db->select('*');
        $this->db->from('customers');
        $this->db->where('id', $user_id);
        $query = $this->db->get();
        $result = $query->row();
        $person_name = $result->name;
        $invoice_no = $row->invoice_no;

        if (stripos($invoice_no, "PIC") !== false) {
          // echo "The string contains 'PIC'.";
          $type = 'Pices';
        }
        else {
          $type = 'Sell';
        }
        if (stripos($invoice_no, "RPIC") !== false) {
          $type = 'Return Pices';
        }
        if ($type == 'Sell') {
          $to = $person_name;
          $person_name = 'Shop';
        } 
        else {
          $person_name = $person_name;
          $to = 'Shop';
        }

        
        //get material name
        $design_id = $row->material_id;
        $this->db->select('*');
        $this->db->from('designs');
        $this->db->where('id', $design_id);
        $query = $this->db->get();
        $result = $query->row();
        $design_num = $result->design_num;
        ?>
        <tr>
          <td>
            <?php echo $i; ?>
          </td>
          <td>
            <?php echo $type; ?>
          </td>
          <td>
            <?php echo $row->invoice_no; ?>
          </td>
          <td>
            <?php echo $row->created_at; ?>
          </td>
          <td>
            <?php echo $person_name; ?>
          </td>
          <td>
            <?php echo $to; ?>
          </td>
          <td>
            <?php echo $design_num; ?>
          </td>
          <td>
            <?php echo $row->in_out_qnty; ?>
          </td>
          <td>
            <?php echo $row->stock_quantity; ?>
          </td>
        </tr>
        <?php $i++; endforeach; ?>
    </tbody>
  </table>
  <div>

  </div>

</body>

</html>