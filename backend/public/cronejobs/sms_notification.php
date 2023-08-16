<?php 
$today=date('Y-m-d');

$con = mysqli_connect("localhost", "poscreamug_poscreamnew_user", "poscreampassword", "poscreamug_poscreamnew");
if(!$con){
    
	echo"Failed to connect to the database".mysqli_connect_error();
   

}

// $businesses=mysqli_query($con,"SELECT id from businesses where status=1");
$businesses=mysqli_query($con,"SELECT id,(SELECT contact from users where business_id=businesses.id && id= ( SELECT MIN(id)) limit 1) as contact,(SELECT count(id) from branches where business_id=businesses.id  && status=1 limit 1) as nbranches from businesses where status=1");

if (mysqli_num_rows($businesses)>0) {
	while ($rb=mysqli_fetch_array($businesses)) {
		$business=$rb['id'];
		$nbranches=$rb['nbranches'];
		$notification_contact=$rb['contact'];
		$braches=mysqli_query($con,"SELECT id, name from branches where business_id='$business' && status=1");
       if (mysqli_num_rows($braches)>0) {


		   while ($rbr=mysqli_fetch_array($braches)) {
		   	$branch=$rbr['id'];
		   	$branch_name=$rbr['name'];
			    $sql=mysqli_query($con,"SELECT sum(total) as total_sales,sum(paid+discount) as paid , count(id) as records,
				(SELECT sum(total) from purchases where branch_id='$branch' && date='$today') as total_purchases,
                (SELECT sum(paid+discount) from purchases where branch_id='$branch' && date='$today') as purchases_paid,
				(SELECT count(id) from purchases where branch_id='$branch' && date='$today') as purchase_records,
				(SELECT sum(amount)  from expenses where date='$today' && branch_id='$branch') as total_expenses,
				(SELECT sum(quantity * selling_price)  from products where is_product='1' && branch_id='$branch') as stock_value,
				(SELECT count(id) from expenses where date='$today' && branch_id='$branch') as expense_records,
				(SELECT sum(amount) from payments where(EXISTS (SELECT * from sales where id=payments.sale_id && branch_id='$branch' && sale_date!='$today')) && date='$today' && branch_id='$branch') as arreas_paid,
				(SELECT dial_code from countries where id=(SELECT country_id from businesses where business_id='$business' limit 1)) as dial_code
				from sales where EXISTS(SELECT * from sale_details where sale_details.sale_id=sales.id) && sale_date='$today' && branch_id='$branch'");	
				$row = mysqli_fetch_array($sql);
				$dial_code=$row['dial_code'];

				$total_sales=is_null($row['total_sales'])?0:$row['total_sales'];
				$paid_sales=is_null($row['paid'])?0:$row['paid'];
				$credit_sales = $total_sales-$paid_sales;
				$sale_records = $row['records'];

				$total_purchases=is_null($row['total_purchases'])?0:$row['total_purchases'];
				$paid_purchases=is_null($row['purchases_paid'])?0:$row['purchases_paid'];
				$creditors = $total_purchases-$paid_purchases;
				$purchase_records=$row['purchase_records'];
				$stock_value=$row['stock_value'];

				$total_expenses=is_null($row['total_expenses'])?0:$row['total_expenses'];
				$expense_records=$row['expense_records'];


				$arreas_paid=is_null($row['arreas_paid'])?0:$row['arreas_paid'];

				mysqli_query($con,"INSERT INTO `sms_notifications`(`business_id`, `branch_id`, `date`, `purchase_records`, `purchase_total`, `sale_records`, `sales_total`, `expense_records`, `expenses_total`, `credit_sales`, `arears_total`,`creditors`,`stock_value`, `sent_to_tel`) VALUES ('$business','$branch','$today','$purchase_records','$total_purchases','$sale_records','$total_sales','$expense_records','$total_expenses','$credit_sales','$arreas_paid','$stock_value','$creditors',$notification_contact')");
				if ($nbranches>1) {
					# code...
					$bnam="BRANCH: ".$branch_name;
				}else{
                 $bnam='';
				}
                
                $message="POSCREAM SOFTWARE: ".$bnam."\r\nTransactions Summary for ".$today."\r\nTotal Sales: ".$total_sales."  Credit Sales: ".$credit_sales." Arrears Paid: ".$arreas_paid." Expenses: ".$total_expenses." Creditors: ".$creditors." Stock Value: ".$stock_value;
                
                $size = ceil(strlen($message)/156);
                if ($dial_code='256') {
					$pns=substr($notification_contact, -9);
					$pn=$dial_code."".$pns;
                 //use the API to send message
                $data = 'api_id=API34770800361&api_password=nugsoft@2020&sms_type=P&encoding=T&sender_id=BULKSMS&phonenumber=' . $pn. '&textmessage=' . rawurlencode($message);
                // Send the GET request with cURL
                $ch = curl_init('http://apidocs.speedamobile.com/api/SendSMS?' . $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
                $obj = json_decode($response);
                $smsid= $obj->message_id;
                
                $insertmsglog = mysqli_query($con, "INSERT INTO `sms_logs`(`receiver`, `category`, `sms`, `sms_id`, `size`, `branch_id`, `business_id`) VALUES ('$notification_contact','1','$message','$smsid','$size','$branch','$business')");
                
    ?>
    
    <?php
  }

		   }
	    }

	}
}
 
?>