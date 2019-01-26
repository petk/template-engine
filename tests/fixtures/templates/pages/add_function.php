<?php $this->extends('layout.php', ['title' => 'Homepage']) ?>

<?php $this->start('content'); ?>
<?= $this->addAsterisks($foo); ?>
<?php $this->end('content'); ?>
