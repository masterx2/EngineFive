<!doctype html>
<html lang="ru">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>{block 'title'}MasterX2 Home{/block}</title>
    <!-- Base Assets -->
    <link rel="stylesheet" href="/css/base/bootstrap.min.css"/>
    <script src="/js/base/base.min.js"></script>
    <!-- End Base Assets -->
    <!-- Project Assets-->
    <link rel="stylesheet" href="/css/site.min.css"/>
    <script src="/js/main.min.js"></script>
    <!-- End Project Assets -->
    {block 'scripts'}
        {if isset($scripts)}{$scripts}{/if}
    {/block}
</head>
<body>
{block 'content'}{/block}
</body>
</html>