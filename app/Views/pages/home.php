<?= $this->extend('default') ?>

<?= $this->section('content') ?>

<h1>Home</h1>

    <h1>Hello World!</h1>
    <?= $this->section('javascript') ?>
       let a = 'a';
    <?= $this->endSection() ?>
    
<?= $this->endSection() ?>