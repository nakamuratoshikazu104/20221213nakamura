<?php
// DB接続情報
$dsn='mysql:dbname=inquiry;host=localhost';
$user='root';
$password='root';

// タイムゾーン設定
date_default_timezone_set("Asia/Tokyo");

// DB接続
try{
    $pdo=new PDO($dsn, $user, $password);
} catch(PDOException $e) {
    $error_message=$e->getMessage();
}

if( !empty($_POST['btn_submit']) ){
// 検索条件でデータ取得
    // SQL作成　  SELECT WHERE
    // 値をセット $stmt -> bindParam
    // SQLクエリの実行 $stmt -> execute();
    // データ取得　$data_array = $stmt-> fetch();

}

if( !empty($_POST['btn_delete']) ){
// $value['id']を持つデータをDBから削除
    // SQL作成　DELETE WHERE id = $value['id']
    // SQLクエリの実行 $stmt -> execute();
}
?>




<DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>管理システム</title>
<style>
body{
    width: 860px;
    margin: 60px auto auto 60px;
}
h2{
    text-align: center;

}
.form{
    border: 0.5px solid black;
    width: 100%;
}
form{
    margin: 24px;
}
.inner{
    display: flex;
}
input{
    height: 28px;
    border-radius: 4px;
}
label{
    width: 120px;
    display: inline-block;
    margin-bottom: 36px;
}
.label_gender{
    margin-left: 60px;
    width: 36px;
}
.form__gender{
    display: flex;
    align-items: flex-start;
}
.form__gender input{
    margin: 0px 12px auto 36px;
}
span{
    margin-left: 20px;
    margin-right: 20px;
}
.btn_submit{
    text-align: center;
}
input[type=submit]{
    background-color: black;
    color: white;
    border: none;
    border-radius: 4px;
    padding-left: 32px;
    padding-right: 32px;
}
input[type=submit]:hover{
    background-color: #222;
    cursor: pointer;
}
a{
    font-size: 12px;
    display: block;
    text-align: center;
    margin-top: 6px;
}
.delete__top{
    display: flex;
    justify-content: space-between;
    margin-top: 36px;
}
.delete__main{
    margin-top: 24px;
}
.delete__main-id,
.delete__main-fullname,
.delete__main-gender{
    width: 72px;
    text-align: center;
}
.delete__main-email{
    width: 180px;
    text-align: center;
}
.delete__main-opinion{
    width: 400px;
    text-align: left;
}
.delete__main-delete{
    width: 64px;
    text-align: center;
}
.btn_delete{
    background-color: black;
    color: white;
    border: none;
    border-radius: 2px;
    padding-left: 4px;
    padding-right: 4px;
    font-size: 12px;
}
.btn_delete:hover{
    cursor: pointer;
    background-color: #222;
}
table tr:first-child{
    border: 0.5px solid black;
}

</style>
</head>
<body>
<h2>管理システム</h2>
<div class="form">
    <form method="post">
        <div class="inner">
            <div>
                <label for="fullname">お名前</label>
                <input type="text" name="fullname">
            </div>
            <div class="form__gender">
                <label for="gender" class="label_gender">性別</label>
                <input type="radio" name="gender" value="0" style="transform:scale(1.5)" checked> 全て
                <input type="radio" name="gender" value="1" style="transform:scale(1.5)"> 男性
                <input type="radio" name="gender" value="2" style="transform:scale(1.5)"> 女性
            </div>
        </div>
        <div>
            <label for="created_at">登録日</label>
            <input type="timestamp" name="created_from">
            <span>~</span>
            <input type="timestamp" name="created_until">
        </div>
        <div>
            <label for="email">メールアドレス</label>
            <input type="email" name="email">
        </div>
        <div class="btn_submit">
            <input type="submit" name="btn_submit">
        </div>
        <a href="admin.php">リセット</a>
    </form>
</div>
<div class="delete">
    <div class="delete__top">
        <div>11~20件</div>
        <div> | 1 2 3 4 | </div>
    </div>
    <div>
        <table>
            <tr>
                <th class="delete__main-id">ID</th>
                <th class="delete__main-fullname">お名前</th>
                <th class="delete__main-gender">性別</th>
                <th class="delete__main-email">メールアドレス</th>
                <th class="delete__main-opinion">ご意見</th>
                <th class="delete__main-delete"></th>
            </tr>
            <tr>
                <?php // foreach($data_array as $value) : ?>  
                <td class="delete__main-id">1 <?php // echo $value['id'] ?></td>
                <td class="delete__main-fullname">yamada</td>
                <td class="delete__main-gender">male</td>
                <td class="delete__main-email">test@gmail.com</td>
                <td class="delete__main-opinion">test</td>
                <td class="delete__main-delete">
                    <form method="post">
                        <input type="submit" name="btn_delete" value="削除" class="btn_delete">
                        <input type="hidden" name="opinion_id" value="<?php //echo $value['id']; ?>">
                    </form>
                </td>
            </tr>
        </table>
    </div>

</div>







</body>