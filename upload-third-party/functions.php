<?php
declare(strict_types=1);

function dateDiffInDays(string $date1, string $date2): int
{
    $t1 = strtotime($date1);
    $t2 = strtotime($date2);
    if ($t1 === false || $t2 === false) {
        return 0;
    }
    $diff = $t2 - $t1;
    return (int)abs(round($diff / 86400));
}
