<?php $this->extends('layout.php', ['title' => 'Homepage']) ?>

<?php $this->start('content'); ?>
<?= $foo; ?>
<?php $this->end('content'); ?>

<?php $this->start('sidebar'); ?>
<?= $sidebar; ?>
<?php $this->end('sidebar'); ?>
