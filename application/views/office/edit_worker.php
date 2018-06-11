<?php
    $this->load->view('common/head-0');
?>
<div class="container">
<div class="header"></div>    
<a href="<?php echo site_url('office/viewAdminArea');?>">&larr; К меню администратора</a><br><br>  
<?php
$fields = ["id", "email", "hash", "role"];
foreach ($fields as $key): ?>
    <div class="row">
        <div class="two columns">
            <?php echo $key; ?>
        </div>
        <div class="ten columns">
            <?php echo $worker[$key]; ?>
        </div>
    </div>
<?php endforeach;?>
    <br>
<?php 
$attributes = ["method" => "GET"];
echo form_open("office/takeEditForm/{$worker['id']}", $attributes);?>
    <select class="login" name="role">
        <?php foreach ($this->config->item('role') as $caption => $value): ?>
            <option value="<?php echo $value; ?>"><?php echo $caption; ?></option>
        <?php endforeach; ?>
    </select><br>    
<?php
echo form_submit("submit", "Изменить роль");
echo form_close(); 
?>
</div>     
</body>
</html>

