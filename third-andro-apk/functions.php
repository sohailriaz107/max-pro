<?php 
declare(strict_types=1);

if (!function_exists('dateDiffInDays')) {
    function dateDiffInDays(?string $date1, ?string $date2): int { 
        $t1 = strtotime((string)($date1 ?? ''));
        $t2 = strtotime((string)($date2 ?? ''));
        if ($t1 === false || $t2 === false) {
            return 0;
        }
        $diff = $t2 - $t1; 
        return (int)abs(round($diff / 86400)); 
    }
}
