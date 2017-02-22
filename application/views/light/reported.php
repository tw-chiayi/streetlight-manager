<?php include(__DIR__."/../_header.php"); ?>

<?php function css_section(){  ?>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css" />
  <link rel="stylesheet" href="<?=base_url("css/MarkerCluster.css")?>" />
  <link rel="stylesheet" href="<?=base_url("css/MarkerCluster.Default.css")?>" />

  <style>
    hr{
      border-top:1px solid #ccc;
    }
  </style>
<?php } ?>

<div id="container" class="container">
  <h1 style="text-align:center;"> 您已回報成功</h1>
  <div>

    <p>我們將儘速確認與處理。</p>
  </div>
  <p class="clearfix"></p>

  
  <hr />
  <p>Power by <a href="https://github.com/tony1223/" target="_blank">智慧城市與青年創業推動辦公室</a> </p>

</div>

<?php function js_section(){ ?>

<?php } ?>


<?php include(__DIR__."/../_footer.php"); ?>