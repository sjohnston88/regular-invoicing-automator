## Synopsis

This is a regular invoice automator that creates PDF invoices and email's them automatically to customers at a set date and time. 

## Motivation

This software aims to aid in the simplification of invoicing. If you regularly create and send out invoices that are exactly the same each month, then this will automate the job completely. All your, and your clients information are stored in json files, so the application is simple, scalable and maintainable.

## Requirements

A webserver with CRON capabilities.

dompdf (included in repository).

PHPMailer (included in repository).

SMTP Email Settings

## Installation & Configuration

Clone the repository to your local or webserver

Replace the values in config.json with your own details.

Replace the values in clients.json with those of your clients, you can add as many as you'd like.

Create a cron job that runs run-once.php at a set time and date each month, my invoicing runs on the 18th of each month.