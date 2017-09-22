<?php

require_once './classes/class.mail.php';
require_once './classes/tcpdf/tcpdf.php';

//echo '<pre>'; print_r($_REQUEST); echo '</pre>';
foreach ($_POST as $var=>$val) $$var = $val;
//echo '<pre>'; print_r($_POST); exit();

switch ($submit) {
    case 'cbct':
        include_once './process_cbct.php';
        break;
    default:
        echo 'There is a broken link in the referral process, please contact the University of Michigan School of Dentistry Help Desk at 763-3318.';
} //end switch

echo $out_message;
?>

