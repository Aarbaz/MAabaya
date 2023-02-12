<?php

if(isset($db_data))
{
          $filename = $db_data[0]['bakery_name'].'-ledger.xls';
          header("Content-Type: application/vnd.ms-excel");
          header("Content-Disposition: attachment; filename=\"$filename\"");
          $isPrintHeader = false;   
          $excelTable = '';
          $excelTable .= '<table border="1"><tr><td colspan="8"><h3 style="text-align: center"><b>'.$db_data[0]['bakery_name'].'</h3></td></tr>
                  <tr><td colspan="8"><h4 style="text-align: center"><b>'.
                  $db_data[0]['bakery_address'].','.
                  $db_data[0]['bakery_area'].','.
                  $db_data[0]['bakery_city'].'</h4></td></tr>
                  <tr><td colspan="8">&nbsp;<br /></td></tr>';

          $excelTable .= '<tr><th>LAST AMOUNT</th> <th>BILL AMOUNT</th> <th>PAID AMOUNT</th>
           <th>NEW AMOUNT</th> <th>PAY MODE</th> <th>TRN No.</th> <th>CHEQUE No</th>
           <th>DATE</th></tr>';

        foreach ($db_data as $data_row) 
        {
          $data_row['dated'] = date('d F, Y', strtotime($data_row['dated']) );    
          $excelTable.= '<tr><td>'.
            $data_row['last_amount'].'</td><td>'.$data_row['bill_amount'].'</td><td>'.
            $data_row['paid_amount'].'</td><td>'.$data_row['new_amount'].'</td><td>'.
            $data_row['payment_mode'].'</td><td>'.$data_row['transaction_no'].'</td><td>'.
            $data_row['cheque_no'].'</td><td>'.$data_row['dated'].'</td></tr>';     
        }

        echo $excelTable;
}