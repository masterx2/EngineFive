<!doctype html>
<html lang="ru">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>{block 'title'}MasterX2 Home{/block}</title>
    <link rel="stylesheet" href="/css/style.min.css"/>
    <script src="/js/app.min.js"></script>
    {block 'scripts'}
        {if isset($scripts)}{$scripts}{/if}
    {/block}
</head>
<body>
    <div class="col-md-12 header">
        <a href="/"><img src="/images/mass_black.png" alt=""/></a>
    </div>
    <div class="container">
        <div class="row">
            {block 'content'}
                {if isset($content)}{$content}{/if}
            {/block}
        </div>
    </div>
</body>
</html>