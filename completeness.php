<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Metadata Quality Assurance Framework</title>
  <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.1.0/styles/default.min.css" />
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway" type="text/css" />
  <link rel="stylesheet" href="europeana-qa.css" />
  <!-- choose a theme file -->
  <link rel="stylesheet" href="jquery/theme.default.min.css">
  <!-- load jQuery and tablesorter scripts -->
  <script type="text/javascript" src="jquery/jquery-1.2.6.min.js"></script>
  <script type="text/javascript" src="jquery/jquery.tablesorter.min.js"></script>

  <!-- tablesorter widgets (optional) -->
  <script type="text/javascript" src="jquery/jquery.tablesorter.widgets.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/highcharts-more.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
<style type="text/css">
.node {
  border: solid 1px white;
  font: 10px sans-serif;
  line-height: 12px;
  overflow: hidden;
  position: absolute;
  text-indent: 2px;
}
</style>
</head>
<body>

<div class="container">

<div class="page-header">
  <h1>Field cardinality</h1>
  <h3><a href="./">Metadata Quality Assurance Framework</a></h3>
</div>

<p>This chart shows the cardinality of the analyzed fields in all records.</p>


<div id="customLegend1"></div>
<br>
<div id="customLegend2"></div>
<div id="container" style="min-width: 600px; max-width: 1000px; height: 700px; margin: 0 auto"></div>

<script type='text/javascript'>//<![CDATA[
Highcharts.chart(

  'container',

  {
    chart: {
      polar: true,
      type: 'line',
      vscroll: false,
      events: {
        load: function() {
          this.series[0].show();
        }
      }
    },

    title: {
      text: '',
      x: -10
    },

    pane: {
      size: '70%'
    },

    xAxis: {
      categories: [
        "every fields", "mandatory", "descriptiveness", "searchability", "contextualization", "identification",
        "browsing", "viewing", "reusability", "multilinguality", "multilingual saturation"
      ],
      tickmarkPlacement: 'off',
      lineWidth: 0
    },

    yAxis: {
      gridLineInterpolation: 'polygon',
      lineWidth: 0,
      min: 0
    },

    tooltip: {
      shared: true,
      pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.2f}</b><br/>'
    },

    legend: {
      enabled: false
    },

    exporting: { enabled: false },

    plotOptions: {
      series: {
        visible: false
      }
    },

    series:[
      {"name":"National Library of France","pointPlacement":"on","data":[0.2241,1,0.4188,0.5369,0.4161,0.6904,0.5359,0.7478,0.4785,0.5956,0.5531]},
      {"name":"Österreichische Nationalbibliothek - Austrian National Library","pointPlacement":"on","data":[0.2386,1,0.3118,0.4964,0.4348,0.6507,0.49,0.6252,0.4244,0.5635,0.6155]},
      {"name":"National Library of the Netherlands","pointPlacement":"on","data":[0.2336,1,0.3758,0.5404,0.4396,0.694,0.5192,0.5936,0.4899,0.5949,0.5322]},
      {"name":"Biblioteca Virtual de Prensa Histórica","pointPlacement":"on","data":[0.1621,1,0.3416,0.4465,0.3413,0.647,0.4448,0.5,0.3834,0.5055,0.4625]},
      {"name":"Bavarian State Library","pointPlacement":"on","data":[0.2433,1,0.5215,0.5595,0.4477,0.7199,0.4146,0.503,0.4859,0.6661,0.5771]},
      {"name":"Deutsche Fotothek","pointPlacement":"on","data":[0.2069,1,0.3431,0.4213,0.3258,0.5998,0.3989,0.75,0.3636,0.5996,0.6258]},
      {"name":"Bildarchiv Foto Marburg","pointPlacement":"on","data":[0.1974,1,0.2841,0.3223,0.1504,0.5947,0.2425,0.4609,0.2978,0.2743,0.6687]},
      {"name":"Riksantikvarieämbetet | Swedish National Heritage Board","pointPlacement":"on","data":[0.2112,1,0.3402,0.4301,0.3402,0.6742,0.2857,0.4182,0.3339,0.5483,0.5493]}
    ]
  },

  function(chart) {

    var $customLegend1 = $('#customLegend1').append('<select id="customSelect1"></select>').find('select'),
        $option,
        serie;
    var $customLegend2 = $('#customLegend2').append('<select id="customSelect2"></select>').find('select'),
        $option,
        serie;

    $customLegend1.append('<option value="0">Select region </option>');
    $customLegend2.append('<option value="0">Select region to compare</option>');

    $.each(chart.series, function(i, serie) {
      if (i == 0) {
        $customLegend1.append('<option value="' + i + '" selected="selected">' + serie.name + '</option>');
        $customLegend2.append('<option value="' + i + '" >' + serie.name + '</option>');
      } else {
        $customLegend1.append('<option value="' + i + '" >' + serie.name + '</option>');
        $customLegend2.append('<option value="' + i + '" >' + serie.name + '</option>');
      }
    });

    $customLegend1.change(function() {

      var chart = $('#container').highcharts();
      var series = chart.series;
      var index1 = $('#customSelect1').val();
      var index2 = $('#customSelect2').val();

      $.each(chart.series, function(i) {
        if (i>0 && i != index2 && series[i].visible) {
          series[i].hide();
        }
      });
      series[index1].show();
      series[index1].options.color = "#FF0000";
      series[index1].update(chart.series[index1].options);
    }),

    $customLegend2.change(function(){

      var chart = $('#container').highcharts();
      var series = chart.series;
      var index1 = $('#customSelect1').val();
      var index2 = $('#customSelect2').val();

      $.each(chart.series, function(i) {
        if(i>0 && i != index1 && series[i].visible){
          series[i].hide();
        }
      });
      series[index2].show();
      series[index2].options.color = "#00FF00";
      series[index2].update(chart.series[index2].options);
    });

  }
);
//]]>
</script>

<script>
  // tell the embed parent frame the height of the content
  if (window.parent && window.parent.parent){
    window.parent.parent.postMessage(["resultsFrame", {
      height: document.body.getBoundingClientRect().height,
      slug: "None"
    }], "*")
  }
</script>

<footer>
  <p><a href="http://pkiraly.github.io/">What is this?</a> &ndash; about the Metadata Quality Assurance Framework project.</p>
</footer>
</div>
