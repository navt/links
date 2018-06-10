<?php
    $this->load->view('common/head-0');
?>
<?php if (isset($_SESSION['err_msg']) && $this->session->err_msg !=''): ?>
    <div class="error">
        <?php echo $this->session->err_msg; deleteSI('err_msg'); ?>
    </div>
<?php endif;?>

<a href="<?php echo site_url('office/viewCreateForm');?>">Новый пользователь</a> | 
<a href="<?php echo site_url('office/viewPasswordForm');?>">Сменить свой пароль</a> | 
<a href="<?php echo site_url('workers/deleteAuth');?>">Выход</a> 
<br><br>
<?php foreach ($workers as $worker): ?>
    <span class="user">
        <?php echo $worker['email']; ?>
    </span>&nbsp;&nbsp;
    <span class="user">
        <?php echo $worker['role']; ?>
    </span>
    <span class="sign" >
        <a href="<?php echo site_url("office/viewEditForm/{$worker['id']}");?>" title="Редактировать">&#9998;</a>
        <a href="<?php echo site_url("office/delete/{$worker['id']}");?>" title="Удалить">&#10006;</a>
    </span>
    <br>
<?php endforeach; ?>

</body>
</html>
