<?php
define('DB_SERVER','localhost');
define('DB_USER','root');
define('DB_PASS' ,'');
define('DB_NAME', 'shopping');
$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
// Check connection
if (mysqli_connect_errno())
{
 echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
define('PAYPAL_CLIENT_ID', 'AWi0gMBSAt_U0ovC1qnoj2KpFqDZjqj5Juutx9cTUj7OmZRKT1PaAlkC4n3F8BlNvbEDjDDcW-ERWHsn');
define('PAYPAL_SECRET', 'ECQxe6GbnnANwLmiWi9viHuHi_wAEequxBAc1c5QQhh-LMh4DaUGxLKJU7xQmr6bk8R7Pvokm_Zrt4kO');
define('PAYPAL_API_BASE', 'https://api-m.sandbox.paypal.com'); // Use live URL in production

?>