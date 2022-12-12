<?php
session_start();

if(isset($_POST['clear'])){
    require_once "./models/GradeEntry.php";
    GradeEntry::deleteAll();

    header("Location:index.php");

}else {
    http_response_code(405);
}
?>
