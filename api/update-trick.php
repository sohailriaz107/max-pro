<?php
$adFile='../txtdb/update-trick.txt';
if (!empty(file_get_contents($adFile))) {
echo file_get_contents($adFile);
}