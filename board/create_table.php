<?php require_once("db.php"); ?>
<?php
try{
$sql = "CREATE TABLE IF NOT EXISTS posts"
." ("
. "post_id INT AUTO_INCREMENT PRIMARY KEY,"
. "name varchar(32),"
. "comment TEXT,"
. "password varchar(255),"
. "created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP"
.");";
$stmt = $dbh->query($sql);
}catch(PDOException $e){
    exit($e->getMessage());
}