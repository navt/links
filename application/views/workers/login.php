<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
    <title>Авторизация</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('app-data/css/style.css'); ?>">
</head>
<body>
	<?php if (isset($_SESSION['err_msg']) && $this->session->err_msg !=''):?>
                <div class="error">
                    <?php echo $this->session->err_msg; deleteSI(); ?>
		</div>
	<?php endif;?>
	<?php
	if (isset($_SESSION['userName']) && isset($_SESSION['passWord'])) {
	 	$userName = $this->session->userName;
	 	$passWord = $this->session->passWord;
	} else {
		$userName = '';
		$passWord = '';
	}
	?>
        <?php
        $csrf = [
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        ];
        $attributes = ["method" => "GET"];
        echo form_open('workers/index', $attributes);
        ?>
            <input class="login" type="text" name="userName" placeholder="логин" value="<?php echo $userName; ?>" required><br>
            <input class="login" type="" name="passWord" placeholder="пароль" value="<?php echo $passWord; ?>" required><br>
            <input class="login" type="checkbox" name="remember" value="yes"> Запомнить меня<br> 
            <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
	<?php
        echo form_submit("submit", "Вход");
        echo form_close(); 
        ?>
</body>
</html>
