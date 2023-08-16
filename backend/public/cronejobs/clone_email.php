<style>
	.container {
		display: flex;
		justify-content: center;
		align-items: center;
	}
</style>
<?php
$hd = '<H1>NEW POSCREAM DAILY OVERVIEW</H1>';
$part1 = '<table>
	<thead>
		<tr>
			<th>SN</th>
			<th>BUSINESS NAME</th>
			<th>SALES TODAY</th>
			<th>TRANSACTIONS TODAY</th>
		</tr>


	</thead>
	<tbody>';



// the message
// include "../includes/connection.php";

$con = mysqli_connect("localhost", "poscreamug_poscreamnew_user", "poscreampassword", "poscreamug_poscreamnew");
if (!$con) {
	echo "Failed to connect to the database" . mysqli_connect_error();
}

//====get businesses======================
$today = date("Y-m-d");
$select_businesses = mysqli_query($con, "SELECT name,phone1,logo,(SELECT COUNT('id') FROM sales where sales.business_id = businesses.id AND sale_date ='$today') as sales_total,(SELECT COUNT('id') FROM transactions where transactions.business_id = businesses.id AND date ='$today') as transactions_total FROM businesses WHERE status = 1 ORDER BY transactions_total DESC");
$i = 0;
$mbody = '';
while ($buss = mysqli_fetch_array($select_businesses)) {
	$i++;
	$business_name = $buss['name'];
	$business_contact = $buss['phone1'];
	$sales = $buss['sales_total'];
	$transactions = $buss['transactions_total'];
	//==================get logs=======================

	$sbody =
		'<tr>
	<td>' . $i . '</td>
	<td>' . $business_name . '</td>
	<td class="container">' .  $sales . '</td>
	<td class="container">' .  $transactions . '</td>
</tr>';
	$mbody = $mbody . $sbody;
}
$msg = $hd . $part1 . $mbody;
?>
</tbody>
</table>
<?php
$sendto = array("ceo@nugsoft.com","ptersont@gmail.com");

foreach ($sendto as $to) {
	$subject = 'NEW POSCREAM OVERVIEW FOR ' . $today;
	$from = 'analysis@poscreamug.com';

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Create email headers
	$headers .= 'From: ' . $from . "\r\n" .
		'Reply-To: ' . $from . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

	// Compose a simple HTML email message

	$message = $msg;

	// Sending email
	if (mail($to, $subject, $message, $headers)) {
		echo 'Your mail has been sent successfully.';
	} else {
		echo 'Unable to send email. Please try again.';
	}
}

?>