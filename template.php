<?php

$invoiceHTML = <<<HTML
    <!DOCTYPE html><html><head><title>Invoice</title>
    <style type="text/css"> .invoice-box { max-width: 800px; margin: auto; margin: 30px; padding: 30px; border: 1px solid #eee; font-size: 16px; line-height: 24px; font-family: sans-serif; color: #555; } .invoice-box table { width: 100%; text-align: left; } .invoice-box table td { padding: 10px; vertical-align: top; } .invoice-box table tr td:nth-child(2) { text-align: right; } .invoice-box table tr.top table td { padding-bottom: 20px; } .invoice-box table tr.top table td.title { font-size: 45px; line-height: 45px; color: #333; } .invoice-box table tr.information table td { padding-bottom: 40px; } .invoice-box table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; padding: 10px 10px; font-weight: bold; } .invoice-box table tr.details td { padding-bottom: 20px; } .invoice-box table tr.item td { border-bottom: 1px solid #eee; } .invoice-box table tr.item.last td { border-bottom: none; } .invoice-box table tr.total td:nth-child(2) { font-weight: bold; } </style></head><body> 
    <div class="invoice-box"> 
    <table cellpadding="0" cellspacing="0"> 
    <tr class="top"> 
    <td colspan="2"> 
    <table cellpadding="0" cellspacing="0">
    <tr>
    <td class="title">$title</td>
    <td>Invoice #: $client->companyShortname-$client->invoiceNumber<br>Date: $rollDate<br>Due: $dueDate<br>Method: $paymentMethod</td>
    </tr>
    </table>
    </td>
    </tr>
    <tr class="information">
    <td colspan="2">
    <table cellpadding="0" cellspacing="0">
    <tr>
    <td>$name<br><b>T:</b> $phone<br><b>E:</b> $email</td>
    <td align="right" style="text-align:right">$client->name<br>$client->company<br>$client->address</td>
    </tr>
    </table>
    </td>
    </tr>
    <tr class="heading">
    <td>Description</td>
    <td>Amount</td></tr>
    $services
    <tr class="total">
    <td></td>
    <td>Total: &pound;$client->total</td>
    </tr>
    <tr class="information">
    <td colspan="2">
    <table cellpadding="0" cellspacing="0">
    <tr>
    <td><b>Payable To</b><br>$name<br>$bankName<br>$sortCode<br>$accountNumber</td>
    <td align="right" style="text-align:right"><b>Address</b><br>$address1<br>$address2<br>$country</td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    </div>
    </body>
    </html>
HTML;

?>