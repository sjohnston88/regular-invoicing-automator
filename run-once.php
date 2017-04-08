<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
set_include_path('/var/www/html/regular-invoicing-automator/');

// Initial Setup 

require './dompdf/autoload.inc.php';
use Dompdf\Dompdf;

require './PHPMailer/class.phpmailer.php';

date_default_timezone_set('Europe/London');
$services = '';
$rollDate = date("d/m/Y"); 
$saveDate = date("dmY");
$dueDate = date("d/m/Y", strtotime('next month'));

// Get config JSON data

$json = file_get_contents('config.json');
$config = json_decode($json, true);

extract($config, EXTR_PREFIX_SAME, "config");

// Loop through clients

$clients = json_decode(file_get_contents('clients.json'));

foreach ($clients->clients as $client){
    
    // Generate services rendered
    foreach($client->services as $service){
        $services .= '<tr class="item"><td>'.$service->service.'</td>';
        $services .= '<td>&pound;'.$service->price.'</td></tr>';
    }
    
    // Generate PDF Invoice
    $dompdf = new Dompdf();
    $dompdf->set_option('isHtml5ParserEnabled', true);
    
    include './template.php';
    
    $dompdf->loadHtml($invoiceHTML);
    $dompdf->render();
    $output = $dompdf->output();
    
    // Save Invoice
    $invoiceCompany = str_replace(' ', '-', $client->company);
    $filename = 'Invoice-'.$client->companyShortname.$client->invoiceNumber.'-'.$invoiceCompany.'-'.$saveDate.'.pdf';
    file_put_contents('./invoices/'.$filename, $output);
    
    // Enable to assist debugging
    // echo $filename . ' has been written.<br />';
    
    // Update clients.json
    $client->invoiceNumber = $client->invoiceNumber + 1;
    $updateJson = json_encode($clients, JSON_PRETTY_PRINT);
    file_put_contents('clients.json', $updateJson);
    
    // Enable to assist debugging
    // echo $filename . ' invoice number has been increased by 1 ('.$client->invoiceNumber.')<br />';
    
    // Create new mailer
    $mail = new PHPMailer;

    $mail->isSMTP();
    
    // Enable for debugging
    // $mail->SMTPDebug = 2; 
    
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls";
    $mail->Host = $mailHost;
    $mail->Port = $mailPort;
    $mail->Username = $mailUsername;
    $mail->Password = $mailPassword;

    $mail->From = $email;
    $mail->FromName = $name;
    $mail->addAddress($client->email);
    $mail->addReplyTo($email, $name);
    $mail->addBCC($email);

    $mail->addAttachment('./invoices/'.$filename);

    $mail->Subject = $mailSubject;
    
    $mail->IsHTML(true);

    $mail->Body  = "Dear " .$client->name.",<br /><br />";
    $mail->Body .= $emailMessage;
    $mail->Body .= "<br /><br />Many Thanks,<br />";
    $mail->Body .= $name;
    
    $mail->AltBody  = "Dear " .$client->name.",\r\n\r\n";
    $mail->AltBody .= $emailMessage;
    $mail->AltBody .= "\r\n\r\nMany Thanks,\r\n";
    $mail->AltBody .= $name;
    
    // Send the mailer
    if(!$mail->send()) {
        // Enable to assist debugging
        // echo 'Message could not be sent.';
        // echo 'Mailer Error: ' . $mail->ErrorInfo;
  		
        // Send Error Email to Admin
        $to      = $email;
		$subject = 'Error: New Invoice Failed to Send';
		$message = 'An invoice due for payment by ' .$dueDate. 'has failed to send to ' . $client->name;
		$headers = 'From:' . $email . "\r\n" .
                   'Reply-To:' . $email . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();
						
		mail($to, $subject, $message, $headers);
        
    } else {
        
        // Enable to assist debugging
        echo $filename.' has been sent to the client<br /><br />';
        
        // Send Success Email to Admin
        $to      = $email;
		$subject = 'New Invoice Created';
		$message = 'An invoice due for payment by ' .$dueDate. 'has sucessfully been sent to.' . $client->name;
		$headers = 'From:' . $email . "\r\n" .
                   'Reply-To:' . $email . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();
    }
    
    // Unset services ready for next run
    $services = '';
}

?>