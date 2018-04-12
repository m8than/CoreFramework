<?php if(!defined('CORE_SECURE')) { die(); } ?><?=$cur_page?><br />

<?php 
//you can put any php here 
//shortcuts for switches/ifs/foreach and for loops work
?>

<?php switch($cur_page):?>
<?php case "home" :?>
    Switch case example
<?php break;?>
<?php endswitch;?>

<?php foreach($items as $item):?>

The item is <?=$item?> <br />

<?php endforeach;?>
