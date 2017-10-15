<?php

if(!isset($_COOKIE['visits'])){
    setcookie('visits', '1', time() + 3600*24*365);
    echo "Witaj pierwszy raz na stronie";
}
else{
    setcookie('visits', $_COOKIE['visits'] +1, time() + 3600*24*365);
    echo "Witaj, odwiedziłeś Nas już: " . $_COOKIE['visits'] . " razy";
}
