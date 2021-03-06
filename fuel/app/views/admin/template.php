<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo $title; ?></title>
    <?php echo Asset::css([
        'futura-pt.css',
        'bootstrap.min.css',
        'styles.css'
    ]); ?>
</head>
<body>
    <?php if ($current_user): ?>
        <script type="text/javascript">
            var USER_NAME = <?= json_encode($current_user->getFullName()) ?>;
            var USER_ID = <?= json_encode((int) ucwords(Auth::get('id'))) ?>;
            var USER_EMAIL = <?= json_encode(Auth::get('email')) ?>;
            var USERNAME = <?= json_encode(Auth::get('username')) ?>;
            var USER_STATUS = <?= json_encode((int)$current_user->getLastStatus()); ?>;
            var BASE_URL = <?= json_encode(Config::get('base_url')) ?>;
            var USER_JOBNO = <?= json_encode($current_user->getLastJobNo()); ?>;
        </script>
    <?php endif; ?>
    <div class="container">
        <div class="row header container">
            <?php
                $controller = Uri::segment(2);
                $leftIcon = Html::anchor('', '<span class="glyphicon glyphicon-home"></span>');
                if ($controller == 'users') {
                    $leftIcon = Html::anchor('admin/logs', '<span class="glyphicon glyphicon-th-list"></span>');
                } else if ($controller == 'logs') {
                    $leftIcon = Html::anchor('admin/users', '<span class="glyphicon glyphicon-user"></span>');
                }
            ?>
            <div class="col-xs-2 text-center"><?= $leftIcon ?></div>
            <div class="col-xs-8 text-center"><h1><?php echo $title; ?></h1></div>
            <?php if ($current_user) : ?>
            <div class="col-xs-2 text-center"><?= Html::anchor('admin/logout', '<span class="glyphicon glyphicon-off"></span>') ?></div>
            <?php else : ?>
            <div class="col-xs-2 text-center"><?= Html::anchor('', '<span class="glyphicon glyphicon-cog"></span>') ?></div>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-xs-12 avatar text-center">
                <?= Html::img('assets/img/logo.png', ['id' => 'logo']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
            <?php if (Session::get_flash('success')): ?>
                <div class="alert alert-success">
                    <button class="close" data-dismiss="alert">×</button>
                    <p><?php echo implode('</p><p>', (array) Session::get_flash('success')); ?></p>
                </div>
            <?php endif; ?>
            <?php if (Session::get_flash('error')): ?>
                <div class="alert alert-danger">
                    <button class="close" data-dismiss="alert">×</button>
                    <p><?php echo implode('</p><p>', (array) Session::get_flash('error')); ?></p>
                </div>
            <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
<?php echo Asset::js(array(
    'jquery.min.js',
    'fastclick.js',
    'template.js',
    'dashboard.js',
)); ?>
<?php echo $content; ?>
            </div>
        </div>

        <footer class="row container">
            <?php if (!$current_user) : ?>
            <ul class="social-links">
                <li class="twitter"><a href="#"><span>twitter</span></a></li>
                <li class="google-plus"><a href="#"><span>google</span></a></li>
                <li class="facebook"><a href="#"><span>facebook</span></a></li>
            </ul>
            <?php else : ?>
            <ul class="social-links extended">
                <?php $date = Date::forge(strtotime(new Date())); ?>
                <li class="time"><span>00:00 pm</span></li>
                <li class="date">
                    <span>
                        <small class="day"><?= $date->format('%A'); ?></small>
                        <small class="full-date"><?= $date->format('%B ' . (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' ? '%#d' : '%e') . ', %Y') ?></small>
                    </span>
                </li>
            </ul>
            <?php endif; ?>
        </footer>
    </div>
</body>
</html>
