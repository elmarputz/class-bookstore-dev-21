<?php 

$orderId = $_REQUEST['orderId'] ?? null;

require_once('views/partials/header.php'); 
?>


<div class="page-header">
    <h2>Success</h2>
</div>

<p>Thank you, order number is: <?php echo $orderId; ?>



<?php 
require_once('views/partials/footer.php');