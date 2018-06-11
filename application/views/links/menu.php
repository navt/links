<?php
    $this->load->view('common/head-0');
?>
<div class="container">
    <div class="header"></div>    
    Имеется ссылок в БД &nbsp;  <span class="count"><?php echo $count; ?></span> |
    <a href="<?php echo site_url('links/showLink')?>">Извлечь ссылку</a> |
    <?php if ($isAdmin): ?>
        <a href="<?php echo site_url('links/addLinks')?>">Добавить ссылки в БД</a> |
    <?php endif; ?>
    <a href="<?php echo site_url('workers/deleteAuth')?>">Выход</a>  |&nbsp; &nbsp; 
    <?php if ($isAdmin): ?>
        <span class="sign" ><a href="<?php echo site_url('office/viewAdminArea')?>" title="Меню администратора">&#9998;</a></span>
    <?php else: ?>
        <span class="sign" ><a href="<?php echo site_url('office/viewPasswordForm')?>" title="Сменить свой пароль">&#9998;</a></span>
    <?php endif; ?>
</div>
</body>
</html>