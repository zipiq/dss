<?php
define("RECAPTCHA_V3_SECRET_KEY", '6LfejrkUAAAAADX5jzR5HasxySi0frrQDYkREWtO');
 
if (isset($_POST['email']) && $_POST['email']) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
} else {
    // set error message and redirect back to form...
    header('location: subscribe_newsletter_form.php');
    exit;
}
 
$token = $_POST['token'];
$action = $_POST['action'];
 
// call curl to POST request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => RECAPTCHA_V3_SECRET_KEY, 'response' => $token)));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$arrResponse = json_decode($response, true);
 
// verify the response
if($arrResponse["success"] == '1' && $arrResponse["action"] == $action && $arrResponse["score"] >= 0.5) {
    // valid submission
    // go ahead and do necessary stuff
    $email_from = 'noreply@dssphilly.com';
    $to_email_1 = "williamarthurbowman@gmail.com";
    $email_subject = 'New Form Submission';
    $email_body = "Name:";
    $headers = "From: $email_from \r\n";
    $headers .= "Reply-To: $user_email \r\n";

    mail($to_email_1,$email_subject,$email_body,$headers);
} else {
    // spam submission
    // show error message
}
?>