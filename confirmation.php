<?php
// DB接続情報
$dsn='mysql:dbname=inquiry;host=localhost';
$user='root';
$password='root';

// 変数の初期化
$sql=null;
$data_array=array();
$gender=null;
$stmt=null;

// タイムゾーン設定
date_default_timezone_set("Asia/Tokyo");

// DB接続
try{
    $pdo=new PDO($dsn, $user, $password);
} catch(PDOException $e) {
    $error_message=$e->getMessage();
}

// DBからデータ取得
// SQL作成
$sql = "SELECT * FROM temporary WHERE id = 1";
// SQLクエリの実行
$stmt = $pdo -> query($sql);
// データの取得
$data_array = $stmt -> fetch();

$sql=null;

// name1,2の結合
$fullname=$data_array['name1']."  ". $data_array['name2'];

// 性別の表記変換
if( $data_array['gender'] === "1") {
    $gender="男性";
} else {
    $gender="女性";
}

// DBへデータ書き込み
if( !empty($_POST['btn_submit']) ){
// 書き込み日時
$current_date = date("Y-m-d H:i:s");
// トランザクション開始
$pdo -> beginTransaction();
try {
// SQL作成
$stmt = $pdo -> prepare( "INSERT INTO contacts (fullname, gender, email, postcode, address, building_name, opinion, created_at) VALUES (:fullname, :gender, :email, :postcode, :address, :building_name, :opinion, :created_at)" );

// 値をセット
$stmt -> bindParam(':fullname', $fullname, PDO::PARAM_STR);
$stmt -> bindValue(':gender', $data_array['gender'], PDO::PARAM_INT);
$stmt -> bindParam(':email', $data_array['email'], PDO::PARAM_STR);
$stmt -> bindParam(':postcode', $data_array['postcode'], PDO::PARAM_STR);
$stmt -> bindParam(':address', $data_array['address'], PDO::PARAM_STR);
$stmt -> bindParam(':building_name', $data_array['building_name'], PDO::PARAM_STR);
$stmt -> bindParam(':opinion', $data_array['opinion'], PDO::PARAM_STR);
$stmt -> bindParam(':created_at', $current_date, PDO::PARAM_STR);

// SQLクエリの実行
$res = $stmt -> execute();

// コミット
if( $res ) {
     $pdo -> commit();
}

// 自動メール送信
//件名を設定
$auto_reply_subject = 'お問い合わせありがとうございます。';

//本文を設定
$auto_reply_text="お問い合わせを受け付けいたしました。確認後、返答いたします。";

// メール送信
mb_send_mail( $data_array['email'], $auto_reply_subject, $auto_reply_text);

} catch ( Exception $e) {
    $pdo -> rollback();
}

if( $res ){  
header("Location: thanks.php");
}
}
$pdo=null;

?>

<DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>内容確認</title>
<style>
/*--------------------------------
    全体
--------------------------------*/
body{
    width: 720px;
    margin-left: 48px;
}

/*--------------------------------
    HEADER
--------------------------------*/
h1{
    text-align: center;
}

/*--------------------------------
    INPUT AREA
--------------------------------*/
label{
    width: 180px;
    font-weight: bold;
    display: inline-block;
    margin-top: 36px;
    font-size: 14px;
}
.form__input{
    width: 532px;
    display: inline-block;
}
.form__input-inner{
    display: flex;
    justify-content: space-between;
}
.form__input-1{
    width: 100%;
    height: 40px;
    border: none;
}
textarea{
    width: 100%;
    height: 120px;
    border: none;
    margin-left: 180px;
}
.form__input-2{
    width: 49%;
    height: 40px;
    border: none;
}
.form__input span{
    font-size: 12px;
    display: flex;
    align-items: center;
}
.form__input-3{
    width: 95%;
    height: 40px;
    border: none;
}
.form__opinion-parent{
    position: relative;
    width 180px;
    display: flex;
    margin-top: 32px;
}
.form__opinion-child{
    position: absolute;
    top: -28px;
    left: 0px;
}
.form__example{
    font-size: 14px;
    color: #999;
    margin-left: 200px;
    margin-top: 8px;
}
.form__example-fn{
    margin-left: 216px;
}
.form__example-pc{
    font-size: 14px;
    color: #999;
    margin-left: 224px;
    margin-top: 8px;
}
input[type="submit"]{
    margin: 24px auto 0 auto;
    text-align: center;
    display: block;
    padding: 6px 48px;
    background-color: black;
    color: white;
    border: none;
    border-radius: 4px;
}
input[type="submit"]:hover{
    cursor: pointer;
    background-color: #222;
}
input[type="radio"]:last-child{
    margin-left: 24px;
}
.btn_cancel{
    display: block;
    text-align: center;
    font-size: 12px;
    color: black;
}
form{
    margin-bottom: 4px;
}

</style>

</head>
<body>
<h1>内容確認</h1>
<form method="post">
    <div>
        <label for="name1" class="mandatory">お名前</label>
        <span class="form__input">
            <span class="form__input-inner">
                <input type="text" name="fullname" class="form__input-2" value=" <?php echo $fullname; ?> " readonly>
            </span>
        </span>
    </div>
    <div>
        <label for="gender" class="mandatory">性別</label>
        <span class="form__input">
            <input type="text" name="gender" class="form__input-1" value=" <?php echo $gender; ?> " readonly>
        </span>
    </div>
    <div>
        <label for="email" class="mandatory">メールアドレス</label>
        <span class="form__input">
            <input type="email" name="email" class="form__input-1" value=" <?php echo $data_array['email']; ?> " readonly>
        </span>
    </div>
    <div>
        <label for="postcode" class="mandatory">郵便番号</label>
        <span class="form__input">
            <span class="form__input-inner">
               <input type="text" name="postcode" class="form__input-3" value=" <?php echo $data_array['postcode']; ?> " readonly>
            </span>
        </span>
    </div>
    <div>
        <label for="address" class="mandatory">住所</label>
        <span class="form__input">
            <input type="text" name="address" class="form__input-1" value=" <?php echo $data_array['address']; ?> " readonly>
        </span>
    </div>
    <div>
        <label for="building_name">建物名</label>
        <span class="form__input">
            <input type="text" name="building_name" class="form__input-1" value=" <?php echo $data_array['building_name']; ?> " readonly>
        </span>
    </div>
    <div class="form__opinion-parent">
        <label for="opinion" class="form__opinion-child">ご意見</label>
        <span class="form__input">
            <textarea name="opinion" id="opinion" readonly> <?php echo $data_array['opinion']; ?> </textarea>
        </span>
    </div>
    <input type="submit" name="btn_submit" value="送信">
</form>
<a href="index.php?message_id=1" class="btn_cancel">修正する</a>
</body>
</html>