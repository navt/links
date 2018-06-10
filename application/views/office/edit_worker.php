<?php
    $this->load->view('common/head-0');
?>
<a href="<?php echo site_url('office/viewAdminArea');?>">&larr; К меню администратора</a><br><br>  
<?php
$fields = ["id", "email", "hash", "role"];
foreach ($fields as $key): ?>
    <span class="user">
        <?php echo $key; ?>
    </span>
    <span class="user">
        <?php echo $worker[$key]; ?>
    </span>
    <br>
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
     
</body>
</html>

