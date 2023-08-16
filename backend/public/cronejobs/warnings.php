<?php

$con = mysqli_connect("localhost", "poscreamug_poscreamnew_user", "poscreampassword", "poscreamug_poscreamnew");
if (!$con) {
	echo "Failed to connect to the database" . mysqli_connect_error();
}

$today = date("Y-m-d");
$closetable = '</tbody></table>';
// require_once('vendor/autoload.php');

$businesses=mysqli_query($con,"SELECT b.id,(SELECT COUNT(*) FROM branches WHERE business_id = b.id AND status = 1) AS nbranches, (SELECT email
        FROM users WHERE business_id = b.id AND status = 1 AND id = (SELECT MIN(id) FROM users WHERE business_id = b.id AND status = 1)) AS owners_email
        FROM businesses b WHERE status = 1");

if (mysqli_num_rows($businesses)>0) {
    $hd = '<H1>NEW POSCREAM WEEKLY NOTIFICATIONS</H1>';
    while ($rb=mysqli_fetch_array($businesses)) {
        $business=$rb['id'];
        $nbranches=$rb['nbranches'];
        $braches=mysqli_query($con,"SELECT id, name from branches where business_id='$business' && status=1");

        if (mysqli_num_rows($braches)>0) {
            while ($rbr=mysqli_fetch_array($braches)) {
                $branch=$rbr['id'];
		   	    $branch_name=$rbr['name'];

		   	    if ($nbranches>1) {
					# code...
					$bnam="BRANCH: ".$branch_name;
				}else{
                    $bnam='';
				}

				$select_outofstock = mysqli_query($con, "SELECT product_name,quantity from products where quantity<minimum_quantity AND branch_id = $branch");

                if (mysqli_num_rows($select_outofstock)>0) {

                    $part1 = '<table>
                    <thead>
                        <tr><h4>PRODUCTS RUNNING OUT OF STOCK ' .$bnam.'</h4><tr>
                    	<tr>
                    		<th>SN</th>
                    		<th>PRODUCT NAME</th>
                    		<th>QUANTITY IN STOCK</th>
                    	</tr>
                    </thead>
                    <tbody>';

                    $k = 0;
                    $mbody1 = '';
                    while ($data = mysqli_fetch_array($select_outofstock)) {
                    	$k++;
                    	$name = $data['product_name'];
                    	$qty = $data['quantity'];
                    	//==================get logs=======================


                        $sbody1 =
                    	'<tr>
                        	<td>'. $k.'</td>
                        	<td>'. $name .'</td>
                        	<td>' .$qty.'</td
                        </tr>';
                    	$mbody1 = $mbody1.$sbody1;
                    }
                    $msg1 = $part1.$mbody1.$closetable;
                }

                $select_expired = mysqli_query($con, "SELECT p.product_name, b.expiry_date FROM products p JOIN batches b ON b.product_id = p.id WHERE b.expiry_date < DATE_ADD(CURRENT_DATE, INTERVAL 90 DAY) AND p.branch_id = $branch");

                if (mysqli_num_rows($select_expired)>0) {
                    $part2 = '<table>
                    <thead>
                    	<tr><h4>EXPIRING PRODUCTS ' .$bnam.'</h4><tr>
                    	<tr>
                    		<th>SN</th>
                    		<th>PRODUCT NAME</th>
                    		<th>EXPIRY DATE</th>
                    	</tr>
                    </thead>
                    <tbody>';

                    $i = 0;

                    $mbody2 = '';

                    while ($expireddata = mysqli_fetch_array($select_expired)) {
                    	$i++;
                    	$expiredpdt = $expireddata['product_name'];
                    	$expiry = $expireddata['expiry_date'];
                    	//==================get logs=======================

                        $sbody2 =
                    	'<tr>
                        	<td>'. $i.'</td>
                        	<td>'. $expiredpdt.'</td>
                        	<td>'. $expiry .'</td
                        </tr>';
                    	$mbody2 = $mbody2.$sbody2;
                    }
                    $msg2 = $part2.$mbody2.$closetable;
                }

            }

            if(!empty($rb['owners_email']) && filter_var($rb['owners_email'], FILTER_VALIDATE_EMAIL)){
                $subject = 'NEW POSCREAM WEEKLY NOTIFICATIONS';
                $from = 'analysis@poscreamug.com';

                // To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                // Create email headers
                $headers .= 'From: ' . $from . "\r\n" .
                    'Reply-To: ' . $from . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

                // Compose a simple HTML email message
                $message = $hd.$msg1.$msg2;

                // Sending email
                if (mail($email, $subject, $message, $headers)) {
                    echo 'Your mail has been sent successfully.';
                } else {
                    echo mysqli_error($con);
                }
            }

        }
    }
}


?>
