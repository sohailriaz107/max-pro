<?php 
function dateDiffInDays($date1, $date2)  
{ 
$diff = strtotime($date2) - strtotime($date1); 
return abs(round($diff / 86400)); 
}
?>