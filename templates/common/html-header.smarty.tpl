<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{$title} | Metadata Quality Assurance Framework for Europeana</title>
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.1.0/styles/default.min.css"/>
  <link rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <link rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
        integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Raleway" type="text/css"/>
  <script src="https://use.fontawesome.com/feff23b961.js"></script>
  <!-- choose a theme file -->
  <link rel="stylesheet" href="jquery/theme.default.min.css">
  <link rel="icon" type="image/png" href="https://europeana-style-production.cdnedge.bluemix.net/v0.4.29/images/favicons/favicon-32x32.png?ver=alpha" sizes="32x32">
  <!-- load jQuery and tablesorter scripts -->
  <!-- script type="text/javascript" src="jquery/jquery-1.9.1.min.js"></script -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"
          integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
          crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/europeana-qa.js"></script>
  <link rel="stylesheet" href="europeana-qa.css?a={$rand}" type="text/css"/>
  {if isset($stylesheets)}
    {foreach $stylesheets as $stylesheet}
      <link rel="stylesheet" href="{$stylesheet}?a={$rand}" type="text/css"/>
    {/foreach}
  {/if}
</head>

