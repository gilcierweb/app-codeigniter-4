<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<h1>About</h1>
<h1>Hello World!</h1>

    <?= $this->section('javascript') ?>
    <script>
       let a = 'a';
    </script>
    <?= $this->endSection() ?>
    
<?= $this->endSection() ?>