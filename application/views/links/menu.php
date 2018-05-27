<!DOCTYPE html>
<html>
<head>
    <title>Меню</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('app-data/css/style.css'); ?>">
</head>
<body>
    Имеется ссылок в БД &nbsp;  <span class="count"><?php echo $count; ?></span> |
<a href='<?php echo site_url('links/showLink')?>'>Извлечь ссылку</a> |
<a href='<?php echo site_url('links/addLinks')?>'>Добавить ссылки в БД</a> |
<a href='<?php echo site_url('workers/deleteAuth')?>'>Выход</a>
</body>
</html>