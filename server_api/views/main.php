<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Статистика</title>

    <!-- Bootstrap -->
    <link href="<?php echo $publicUrl ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $publicUrl ?>css/main.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<header class="container">

    <div class="row">
        <div class="dg-center-block col-md-4">
            <h1 style="text-align: center">Статистика работы серверов</h1>
        </div>
    </div>

    <div class="row">
        <form class="form-inline" action="diagram.php" method="post">
            <div class="form-group">
                <div class="input-group">
                    <input type="text" name="from" class="form-control" id="from-date" placeholder="от: 2016-05-31"/>
                </div>
                <div class="input-group">
                    <input type="text" name="to" class="form-control" id="to-date" placeholder="до: 2016-06-31"/>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Применить</button>
        </form>
    </div>

</header>

<?php echo $view->renderAction('DiagramController', 'tableSended'); ?>
<?php echo $view->renderAction('DiagramController', 'tableAccessed'); ?>
<?php echo $view->renderAction('DiagramController', 'tableUnsubscribed'); ?>

<footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="<?php echo $publicUrl ?>js/bootstrap.min.js"></script>
</footer>
</body>
</html>