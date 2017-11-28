<?php
 require_once("../resources/config.php");
include(FRONT.DS."header.php");

?>
<?php
if(isset($_GET['buyerId']) && isset($_GET['itemId']) )
{
  approvesale($_GET['buyerId'],$_GET['itemId']);
}

?>