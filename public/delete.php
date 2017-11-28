<?php
 require_once("../resources/config.php");
include(FRONT.DS."header.php");

?>
<?php

if(isset($_GET['item_id'])){

   deletebook($_GET['item_id']);
//    var_dump($book);
}

?>