<?php
    $this->load->view('common/head-0');
?>
<div class="container">
    <div class="row header">
	<?php if (isset($_SESSION['err_msg']) && $this->session->err_msg !=''):?>
                <div class="error">
                    <?php echo $this->session->err_msg; deleteSI('err_msg'); ?>
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

        $attributes = ["method" => "GET"];
        echo form_open('office/takeCreateForm', $attributes);
        ?>
            <input class="login" type="text" name="userName" placeholder="логин" value="<?php echo $userName; ?>" required><br>
            <input class="login" type="text" name="passWord" placeholder="пароль" value="<?php echo $passWord; ?>" required><br>
            <select class="login" name="role">
                <?php foreach ($this->config->item('role') as $caption => $value): ?>
                    <option value="<?php echo $value; ?>"><?php echo $caption; ?></option>
                <?php endforeach; ?>
            </select><br> 

	<?php
        echo form_submit("submit", "Новый пользователь");
        echo form_close(); 
        ?>
    </div>
</div>
</body>
</html>

