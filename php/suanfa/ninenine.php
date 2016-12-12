<?php
/**
 * Author    : zhaosuji
 * Data      : 2016/12/12
 * Time      : 22:02
 **/

function nineto(){
    for($i=9;$i>0;$i--){
        for($j=$i;$j>0;$j--){
            echo "$i * $j = ".$i*$j.' ';
            if($j==1){
                echo '<br/>';
            }
        }
    }
}
nineto();