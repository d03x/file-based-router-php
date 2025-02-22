<?php $this->extend('layout.global'); ?>
<?php $this->startSection('home'); ?>
Lorem ipsum dolor, sit amet consectetur adipisicing elit. Incidunt, magni. Quae, eius possimus eos laudantium doloribus quia. Cupiditate voluptate tempore rem cum, aperiam, cumque accusantium iusto dicta consectetur pariatur voluptatum.
<?php $this->endSection(); ?>
<?php $this->startSection('home'); ?>

<div>
    <h1>Section Home </h1>
    <?= $dadan ?>
    <h1>Sialan</h1>
    <?php var_dump($_GET) ?>
</div>

<?php $this->endSection(); ?>