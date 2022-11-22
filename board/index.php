<?php require_once("db.php"); ?>
<?php //ファイル書き込み・更新処理（表示処理とは分離）
$edit_id = "";
$edit_name = "";
$edit_comment = "";
if(!empty($_POST["name"]) && !empty($_POST["comment"])){//更新・新規登録
    if(!empty($_POST["edit"])){//編集対象行のidあれば更新処理へ
        try{
            $pass_hashed = password_hash($_POST["pass_regist"], PASSWORD_DEFAULT);
            $stmt = $dbh->prepare("UPDATE posts SET name = ?, comment = ?, password = ? WHERE post_id = ?");
            $stmt->execute([$_POST["name"], $_POST["comment"], $pass_hashed, $_POST["edit"]]);
            $page_message = "更新しました";
        }catch(PDOException $e){
            exit("更新に失敗しました:" .$e->getMessage());
        }
    }else{//編集対象行のidなければ新規登録処理
        try{
            $pass_hashed = password_hash($_POST["pass_regist"], PASSWORD_DEFAULT);
            $stmt = $dbh->prepare("INSERT INTO posts(name, comment, password) VALUES(?, ?, ?)");
            $stmt->execute([$_POST["name"], $_POST["comment"], $pass_hashed]);
            $page_message = "投稿しました";
        }catch(PDOException $e){
           exit("投稿に失敗しました:" .$e->getMessage());
        }
    }
}elseif(!empty($_POST["del_id"])){//削除処理
    try{
        $stmt = $dbh->prepare("SELECT password FROM posts WHERE post_id = ?");
        $stmt->execute([$_POST["del_id"]]);
        $line = $stmt->fetch(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        exit("パスワードの照合に失敗しました:" .$e->getMessage());
    }
    if(password_verify($_POST["pass_verify"], $line["password"])){
        try{
            $stmt = $dbh->prepare("DELETE FROM posts WHERE post_id = ?");
            $stmt->execute([$_POST["del_id"]]);
            $page_message = "削除されました";
        }catch(PDOException $e){
            exit("削除に失敗しました:" .$e->getMessage());
        }
    }else{
        $page_message = "パスワードが違います";
    }
}elseif(!empty($_POST["edit_id"])){//編集対象行の取得処理
    try{
        $stmt = $dbh->prepare("SELECT password FROM posts WHERE post_id = ?");
        $stmt->execute([$_POST["edit_id"]]);
        $line = $stmt->fetch(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        exit("パスワードの照合に失敗しました:" .$e->getMessage());
    }
    if(password_verify($_POST["pass_verify"], $line["password"])){
        try{
            $stmt = $dbh->prepare("SELECT post_id, name, comment FROM posts WHERE post_id = ?");
            $stmt->execute([$_POST["edit_id"]]);
            $edit_line = $stmt->fetch(PDO::FETCH_ASSOC);
            $edit_id = $edit_line["post_id"];
            $edit_name = $edit_line["name"];
            $edit_comment = $edit_line["comment"];
        }catch(PDOException $e){
            exit("編集情報の取得に失敗しました:" .$e->getMessage());
        }
    }else{
        $page_message = "パスワードが違います";
    }
}
?>


<?php include_once("header.php"); ?>
<h1>掲示板</h1>
<?php
if(!empty($page_message)){
    echo $page_message;
}
?>
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value="<?php echo $edit_name; ?>"><br>
        <input type="text" name="comment" placeholder="コメント" value="<?php echo $edit_comment; ?>"><br>
        <input type="password" name="pass_regist" placeholder="パスワード">
        <input type="hidden" name="edit" value="<?php echo $edit_id; ?>">
        <input type="submit" value="送信">
    </form><br>
    <form action="" method="post">
        <input type="number" name="del_id" placeholder="削除対象番号"><br>
        <input type="password" name="pass_verify" placeholder="パスワード">
        <input type="submit" value="削除">
    </form><br>
    <form action="" method="post">
        <input type="number" name="edit_id" placeholder="編集対象番号"><br>
        <input type="password" name="pass_verify" placeholder="パスワード">
        <input type="submit" value="編集">
    </form><br>
<?php
try{
    $stmt = $dbh->prepare ("SELECT post_id, name, comment, created FROM posts");
    $stmt->execute();
    $posts = $stmt->fetchall(PDO::FETCH_ASSOC);
    foreach($posts as $post){
        echo $post["post_id"] . "<>" . $post["name"] . "<>" . $post["comment"] . "<>" . $post["created"] . "<br>";
    }
}catch(PDOException $e){
    exit("スレッドの読み込みに失敗しました:" .$e->getMessage());
}
?>
<?php include_once("footer.php"); ?>



