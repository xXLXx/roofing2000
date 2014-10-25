<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <?php echo Asset::css([
        'bootstrap.min.css',
        'styles.css'
    ]); ?>
    <?php echo Asset::js(array(
        'http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js',
    )); ?>
    <script>
        $(function(){ $('.topbar').dropdown(); });
    </script>
</head>
<body>

    <div class="container">
        <div class="row header container">
            <div class="col-xs-2 text-center"><?= Html::anchor('', '<span class="glyphicon glyphicon-home"></span>') ?></div>
            <div class="col-xs-8 text-center"><h1><?php echo $title; ?></h1></div>
            <div class="col-xs-2 text-center"><?= Html::anchor('', '<span class="glyphicon glyphicon-cog"></span>') ?></div>
        </div>
        <div class="row">
            <div class="col-xs-12 avatar text-center">
                <?= Html::img('assets/img/avatar.png') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
<?php if (Session::get_flash('success')): ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p>
                    <?php echo implode('</p><p>', (array) Session::get_flash('success')); ?>
                    </p>
                </div>
<?php endif; ?>
<?php if (Session::get_flash('error')): ?>
                <div class="alert alert-error alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p>
                    <?php echo implode('</p><p>', (array) Session::get_flash('error')); ?>
                    </p>
                </div>
<?php endif; ?>
            </div>
            <div class="col-md-12">
<?php echo $content; ?>
            </div>
        </div>

        <footer class="row container">
            <ul class="social-links">
                <li class="twitter"><a href="#"><span>twitter</span></a></li>
                <li class="google-plus"><a href="#"><span>google</span></a></li>
                <li class="facebook"><a href="#"><span>facebook</span></a></li>
           </ul>
        </footer>
    </div>
</body>
</html>
