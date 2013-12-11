<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
<!-- <head> section-->

<?php echo $this->renderPartial('/site/_head',null,true,false);?>

<body>
<?php require_once('_navigation.php') ?>

<?php require_once('_header.php')?>

<!-- Require the navigation -->

<!-- Include content pages -->
<?php echo $content; ?>

<!-- Footer -->
<?php echo $this->renderPartial('/site/_footer') ?>

<?php //echo $this->loginDialog; ?>
</body>
</html>