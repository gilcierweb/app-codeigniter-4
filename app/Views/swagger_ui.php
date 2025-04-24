<!DOCTYPE html>
<html>
<head>
    <title>Swagger UI</title>
    <link rel="stylesheet" type="text/css" href="<?= base_url('swagger-ui/swagger-ui.css') ?>" >
</head>
<body>
<div id="swagger-ui"></div>
<script src="<?= base_url('swagger-ui/swagger-ui-bundle.js') ?>" charset="UTF-8"></script>
<script src="<?= base_url('swagger-ui/swagger-ui-standalone-preset.js') ?>" charset="UTF-8"></script>
<script>
    window.onload = function() {
        const ui = SwaggerUIBundle({
            url: "<?= base_url('swagger/swagger.json') ?>",
            dom_id: '#swagger-ui',
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            layout: "StandaloneLayout"
        });
        window.ui = ui;
    };
</script>
</body>
</html>