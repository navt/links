<!DOCTYPE html>
<html>
<head>
    <title>Добавить ссылки</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('app-data/css/style.css'); ?>">
</head>
<body>
<?php
    $csrf = [
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
    ];
    $attributes = ["method" => "POST"];
    echo form_open('links/addLinks', $attributes);
?>    

    <textarea name="links" cols="86" rows="10" placeholder="Введите сюда новые ссылки"></textarea>
    <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
    <br><br>
    <input type="submit" name="submit" value="Ввод">
    &nbsp;&nbsp;&nbsp;
    <a href="<?php echo site_url('links/')?>">&larr; Обратно</a>
</form>
</body>
</html>