<?php
require_once dirname(__FILE__) . '/../init.php';
print_r($configs);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <title>title</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/uikit.min.css" />
    <script src="js/uikit.min.js"></script>
    <script src="js/uikit-icons.min.js"></script>
</head>
<body>

<div class="uk-container">
    <button class="uk-button uk-button-primary"><?php echo date('Y-m-d H:i:s');?></button>
</div>

<div>
    <?php echo APP_ROOT;?>
</div>
<div>
    <?php echo WWW_ROOT;?>
</div>

</body>
</html>