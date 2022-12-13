<?php
// DB接続情報
$dsn='mysql:dbname=inquiry;host=localhost';
$user='root';
$password='root';

// 変数の初期化
$error_message1=null;
$error_message2=null;
$error_message3=null;
$error_message4=null;
$error_message5=null;
$error_message6=null;
$error_message7=null;
$fullname=null;
$postcode=null;
$stmt=null;
$res=null;
$data_array=array();

// タイムゾーン設定
date_default_timezone_set("Asia/Tokyo");

// DB接続
try{
    $pdo=new PDO($dsn, $user, $password);
} catch(PDOException $e) {
    $error_message=$e->getMessage();
}

// DBからデータ取得
if( !empty($_GET['message_id']) ){
// SQL作成
$sql = "SELECT * FROM temporary WHERE id = 1";
// SQLクエリの実行
$stmt = $pdo -> query($sql);
// データの取得
$data_array = $stmt -> fetch();

$sql=null;
}

if( !empty($_POST['btn_submit']) ){
    // 入力チェック
    if( empty($_POST['name1']) || empty($_POST['name2'])) {
        $error_message1 = "お名前を入力してください。";
    } else {
        $fullname=$_POST['name1']. $_POST['name2'];
    }
    if( empty($_POST['email']) ){
        $error_message2 = "メールアドレスを入力してください。";
    } elseif( !preg_match('/^[0-9a-zA-Z_.\/?-]+@([0-9a-zA-Z-]+\.)+[0-9a-zA-Z-]+$/', $_POST['email']) ) {
        $error_message6 = "メールアドレスの形式を確認してください。";
    }
    // 郵便番号の半角への変換
    $postcode=mb_convert_kana( $_POST['postcode'], 'a' );
    if( empty($_POST['postcode']) ){
        $error_message3 = "郵便番号を入力してください。";
    } elseif( !preg_match("/\A\d{3}[-]\d{4}\z/", $postcode) ){
        $error_message7 = "郵便番号の形式を確認してください。";
    }
    if( empty($_POST['address']) ){
        $error_message4 = "住所を入力してください。";
    }
    if( empty($_POST['opinion']) ){
        $error_message5 = "ご意見を入力してください。";
    }

    // DBへデータ書き込み
    // トランザクション開始
    $pdo->beginTransaction();
    if( empty($error_message1) && empty($error_message2) && empty($error_message3) && empty($error_message4) && empty($error_message5) && empty($error_message6) && empty($error_message7) ){
        
        try{
        // SQL作成
        $stmt=$pdo->prepare( "UPDATE temporary set name1=:name1, name2=:name2, gender=:gender, email=:email, postcode=:postcode, address=:address, building_name=:building_name, opinion=:opinion WHERE id = 1" );

        // 値をセット
        $stmt -> bindParam(":name1", $_POST['name1'], PDO::PARAM_STR);
        $stmt -> bindParam(":name2", $_POST['name2'], PDO::PARAM_STR);
        $stmt -> bindValue(":gender", $_POST['gender'], PDO::PARAM_INT);
        $stmt -> bindParam(":email", $_POST['email'], PDO::PARAM_STR);
        $stmt -> bindParam(":postcode", $_POST['postcode'], PDO::PARAM_STR);
        $stmt -> bindParam(":address", $_POST['address'], PDO::PARAM_STR);
        $stmt -> bindParam(":building_name", $_POST['building_name'], PDO::PARAM_STR);
        $stmt -> bindParam(":opinion", $_POST['opinion'], PDO::PARAM_STR);

        // SQLクエリの実行
        $stmt -> execute();

        // コミット
        $res = $pdo -> commit();
        } catch(Exception $e) {
            $pdo -> rollback();
        }

        if( $res ){
            header("Location: confirmation.php");
        }
    }
    $stmt=null;
    $pdo=null;
}
?>


<DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>お問い合わせ</title>
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
    margin-top: 32px;
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
    border: 0.5px solid #999;
    border-radius: 6px;
}
textarea{
    width: 100%;
    height: 120px;
    border: 0.5px solid #999;
    border-radius: 6px;
    margin-left: 180px;
}
.form__input-2{
    width: 49%;
    height: 40px;
    border: 0.5px solid #999;
    border-radius: 6px;
}
.form__input span{
    font-size: 12px;
    display: flex;
    align-items: center;
}
.form__input-3{
    width: 95%;
    height: 40px;
    border: 0.5px solid #999;
    border-radius: 6px;
}
.form__opinion-parent{
    position: relative;
    width 180px;
    display: flex;
    margin-top: 32px;
}
.form__opinion-child{
    position: absolute;
    top: -24px;
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
.mandatory{
    font-size: 14px;
}
.mandatory::after{
    content: "※";
    color: red;
    margin-left: 6px;
}
.form__opinion-child::after{
    content: "※";
    color: red;
    margin-left: 6px;
}
.error__message{
    color: red;
    margin-left: 200px;
}

</style>

</head>
<body>
<h1>お問い合わせ</h1>
<form method="post">
    <div>
        <label for="name1" class="mandatory">お名前</label>
        <span class="form__input">
            <span class="form__input-inner">
                <input type="text" name="name1" class="form__input-2" value="<?php if( !empty($_GET['message_id']) ){ echo $data_array['name1']; } ?>">
                <input type="text" name="name2" class="form__input-2" value="<?php if( !empty($_GET['message_id']) ){ echo $data_array['name2']; } ?>">
            </span>
        </span>
        <?php if( !empty($_POST['btn_submit']) && empty($fullname)): ?>
            <p class="error__message"> <?php echo $error_message1; ?> </p>
        <?php endif; ?>
        <div class="form__example">
            <span>例) 山田</span>
            <span class="form__example-fn">例) 太郎</span>
        </div>
    </div>
    <div>
        <label for="gender" class="mandatory">性別</label>
        <span class="form__input">
            <?php if( empty($_GET['message_id']) ): ?>            
                <input type="radio" name="gender" value="1" style="transform:scale(1.5)" checked>  男性
                <input type="radio" name="gender" value="2" style="transform:scale(1.5)">  女性
            <?php elseif( $data_array['gender'] ==="1"): ?>
                <input type="radio" name="gender" value="1" style="transform:scale(1.5)" checked>  男性
                <input type="radio" name="gender" value="2" style="transform:scale(1.5)">  女性
            <?php elseif( $data_array['gender'] ==="2"): ?>
                <input type="radio" name="gender" value="1" style="transform:scale(1.5)">  男性
                <input type="radio" name="gender" value="2" style="transform:scale(1.5)" checked>  女性
            <?php endif ?>
        </span>
    </div>
    <div>
        <label for="email" class="mandatory">メールアドレス</label>
        <span class="form__input">
            <input type="email" name="email" class="form__input-1" value="<?php if( !empty($_GET['message_id']) ){ echo $data_array['email']; } ?>">
        </span>
        <?php if( !empty($_POST['btn_submit']) && empty($_POST['email']) ): ?>
            <p class="error__message"> <?php echo $error_message2; ?> </p>
        <?php elseif( !empty($error_message6) ): ?>
            <p class="error__message"> <?php echo $error_message6; ?> </p>
        <?php endif; ?>
        <div class="form__example">例) test@example.com</div>
    </div>
    <div>
        <label for="postcode" class="mandatory">郵便番号</label>
        <span class="form__input">
            <span class="form__input-inner">
                <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
                <span>〒</span><input type="text" name="postcode" id="postcode" class="form__input-3" onKeyUp="AjaxZip3.zip2addr(this,'','address','address');" value="<?php if( !empty($_GET['message_id']) ){ echo $data_array['postcode']; } ?>">
            </span>
        </span>
        <?php if( !empty($_POST['btn_submit']) && empty($_POST['postcode']) ): ?>
            <p class="error__message"> <?php echo $error_message3; ?> </p>
        <?php elseif( !empty($error_message7) ): ?>
            <p class="error__message"> <?php echo $error_message7; ?> </p>
        <?php endif; ?>
        <div class="form__example-pc">例) 123-4567</div>
    </div>
    <div>
        <label for="address" class="mandatory">住所</label>
        <span class="form__input">
            <input type="text" name="address" id="address" class="form__input-1" value="<?php if( !empty($_GET['message_id']) ){ echo $data_array['address']; } ?>">
        </span>
        <?php if( !empty($_POST['btn_submit']) && empty($_POST['address']) ): ?>
            <p class="error__message"> <?php echo $error_message4; ?> </p>
        <?php endif; ?>
        <div class="form__example">例) 東京都千駄ヶ谷1-2-3</div>
    </div>
    <div>
        <label for="building_name">建物名</label>
        <span class="form__input">
            <input type="text" name="building_name" class="form__input-1" value="<?php if( !empty($_GET['message_id']) ){ echo $data_array['building_name']; } ?>">
        </span>
        <div class="form__example">例) 千駄ヶ谷マンション101</div>
    </div>
    <div class="form__opinion-parent">
        <label for="opinion" class="form__opinion-child">ご意見</label>
        <span class="form__input">
            <textarea name="opinion" id="opinion"> <?php if( !empty($_GET['message_id']) ){ echo $data_array['opinion']; } ?> </textarea>
        </span>
    </div>
    <?php if( !empty($_POST['btn_submit']) && empty($_POST['opinion']) ): ?>
        <div class="error__message"> <?php echo $error_message5; ?> </div>
    <?php endif; ?>
    <input type="submit" name="btn_submit" value="確認">
</form>
</body>
</html>