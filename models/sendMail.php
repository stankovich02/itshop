<?php
require "../helpers/mail.php";
echo Mail::sendMail("markostankovicdev@gmail.com", "Predmet", "Cao, kako si?");
/*ini_set("SMTP","ssl://smtp.gmail.com");
ini_set("smtp_port","587");
ini_set('sendmail_from', "marefaca2002@gmail.com");
ini_set('sendmail_path', "C:\xampp\sendmail\sendmail.exe -t");
ini_set('mail.add_x_header', "Off");

mail("markostankovicdev@gmail.com", "Predmet", "Cao, kako si?");*/
?>
