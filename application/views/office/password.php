<?php
    $this->load->view('common/head-0');
?>
	<?php if (isset($_SESSION['err_msg']) && $this->session->err_msg !=''):?>
                <div class="error">
                    <?php echo $this->session->err_msg; deleteSI('err_msg'); ?>
		</div>
	<?php endif;?>
	<?php
	if (isset($_SESSION['oldPass']) && isset($_SESSION['passWord'])) {
            $oldPass  = $this->session->oldPass; 	
            $passWord = $this->session->passWord;
	} else {
            $oldPass  = '';	
            $passWord = '';
	}
	?>
        <h3><?php  echo $user; ?></h3>
        <?php
        $attributes = ["method" => "GET"];
        echo form_open('office/takePasswordForm', $attributes);
        ?>
            <input class="login" type="" name="oldPass" placeholder="прежний пароль" value="<?php echo $oldPass; ?>" required><br>
            <input class="login" type="" name="passWord" placeholder="новый пароль" value="<?php echo $passWord; ?>" required><br> 
	<?php
        echo form_submit("submit", "Сменить пароль");
        echo form_close(); 
        ?>

</body>
</html>
