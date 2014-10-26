<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo $title; ?></title>
    <?php echo Asset::css([
        'bootstrap.min.css',
        'styles.css'
    ]); ?>
    <?php echo Asset::js(array(
        'jquery.min.js',
    )); ?>
</head>
<body>
    <?php if ($current_user): ?>
        <script type="text/javascript">
            var USER_NAME = <?= json_encode(ucwords(Auth::get('first_name') . ' ' . Auth::get('last_name'))) ?>;
            var USER_ID = <?= json_encode((int) ucwords(Auth::get('id'))) ?>;
            var USER_EMAIL = <?= json_encode(Auth::get('email')) ?>;
            var USERNAME = <?= json_encode(Auth::get('username')) ?>;
            var USER_STATUS = <?= json_encode((int)$current_user->getLastStatus()); ?>;
            var BASE_URL = <?= json_encode(Config::get('base_url')) ?>;
        </script>
    <?php endif; ?>
    <div class="container">
        <div class="row header container">
            <div class="col-xs-2 text-center"><?= Html::anchor('', '<span class="glyphicon glyphicon-home"></span>') ?></div>
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
            <div class="col-md-12">
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
