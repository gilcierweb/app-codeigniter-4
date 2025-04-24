<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">      

    <title>App CodeIgniter 4</title>

    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
</head>
<body>
<?= $this->include('layouts/_header') ?>

<div class="container mx-auto px-4">
    <?= $this->renderSection('content') ?>
</div>

<?= $this->include('layouts/_footer') ?>

<?= $this->renderSection('javascript') ?>

</body>
</html>