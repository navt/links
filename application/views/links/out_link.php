<!DOCTYPE html>
<html>
<head>
    <title>Окно перехода по ссылке</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('app-data/css/style.css'); ?>">
</head>
<body>
<a href="<?php echo $link?>" target="_blank">Извлечённая ссылка откроется в новом окне &rarr;</a> |
<a href="<?php echo site_url('links/')?>">&larr; Обратно</a>
</body>
</html>