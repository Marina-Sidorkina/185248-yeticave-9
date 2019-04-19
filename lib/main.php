<?php
function formatPrice($number) {
   $result = ceil($number);
   if ($result >= 1000) {
       $result = number_format($result, 0, "", " ");
   }
   return $result;
}
?>
