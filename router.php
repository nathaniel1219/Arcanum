<?php

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

if($path === "/Arcanum/home"){
    echo "Home Page";
}
else if($path === "/Arcanum/cards"){
    echo "<h1>This is the cards page</h1>";
}
else{
    echo "fugoff";
}

?>