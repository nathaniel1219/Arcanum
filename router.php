<?php
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
if($path === "/Arcanum/index.php"){
    echo "Home Page";
}
else if($path === "/Arcanum/pokemon.php"){
    echo "<h1>This is the pokemon page</h1>";
}
else{
    echo "error 404";
}
?>