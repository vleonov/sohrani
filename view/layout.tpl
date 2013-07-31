<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Sohrani.info</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <base href="{$BaseHref}">

    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/base.css" rel="stylesheet">
    <link href="css/grid.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    
    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />

    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/base.js"></script>
    <script type="text/javascript" src="js/lazyload.js"></script>

  </head>

  <body>
    {include file="menu.tpl"}

  <div class="container-fluid">
    {block "content"}

    {/block}
  </div>

  <footer>
      <div class="b-messages">
          <div class="e-message"></div>
      </div>
  </footer>

  </body>

</html>