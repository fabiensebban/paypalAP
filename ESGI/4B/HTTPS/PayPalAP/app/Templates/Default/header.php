
<!DOCTYPE html>
<html lang="<?php echo LANGUAGE_CODE; ?>">
<head>
    <meta charset="utf-8">
    <title><?= $title .' - ' .Config::get('app.name', SITETITLE); ?></title>
<?php
echo $meta; // Place to pass data / plugable hook zone

Assets::css([
    template_url('dist/css/bootstrap.min.css', 'Default'),
    template_url('dist/css/bootstrap-theme.min.css', 'Default'),
    'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css',
    Url::templatePath() .'css/style.css',
]);

echo $css; // Place to pass data / plugable hook zone
?>
<script src="https://code.jquery.com/jquery-3.1.0.slim.min.js" integrity="sha256-cRpWjoSOw5KcyIOaZNo4i6fZ9tKPhYYb6i5T9RSVJG8=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.5.0/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.5.0/highlight.min.js"></script>
</head>
<body style='padding-top: 28px;'>

<nav class="navbar navbar-default navbar-xs navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                
            </ul>
        </div>
    </div>
</nav>

<?= $afterBody; // Place to pass data / plugable hook zone ?>

<div class="container">