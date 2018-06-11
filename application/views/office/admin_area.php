<?php
    $this->load->view('common/head-0');
?>
<div class="container">
    <div class="header">
        <?php if (isset($_SESSION['err_msg']) && $this->session->err_msg !=''): ?>
            <span class="error">
                <?php echo $this->session->err_msg; deleteSI('err_msg'); ?>
            </span>
        <?php endif;?>
    </div>
<a href="<?php echo site_url('office/viewCreateForm');?>">Новый пользователь</a> | 
<a href="<?php echo site_url('office/viewPasswordForm');?>">Сменить свой пароль</a> |
<a href="<?php echo site_url('links/index');?>">Меню клиента</a> | 
<a href="<?php echo site_url('workers/deleteAuth');?>">Выход</a> 
<br><br>
<?php foreach ($workers as $worker): ?>
<div class="row">
    <div class="three columns user">
        <?php echo $worker['email']; ?>
    </div>
    <div class="three columns user">
        <?php echo $worker['role']; ?>
    </div>
    <div class="six columns">
        <span class="sign">
        <a href="<?php echo site_url("office/viewEditForm/{$worker['id']}");?>" title="Редактировать">&#9998;</a>
        <a href="<?php echo site_url("office/delete/{$worker['id']}");?>" title="Удалить">&#10006;</a>
        </span>
    </div>
    <br>
</div>   
<?php endforeach; ?>
</div>
</body>
</html>
