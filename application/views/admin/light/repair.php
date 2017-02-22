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

<div class="container" id="container" style="text-align:center;">
  <h1><?=h($city)?> 維修中路燈</h1>
  
  <form action="<?=site_url("admin/light/action_submit")?>" method="POST">

    <h2>路燈總覽</h2>
    <table class="table table-bordered">
      <tr>
        <td>狀態</td>
        <td>名字</td>
        <td>村子</td>
        <td>上次回報時間</td>
        <td><label><input type="checkbox" id="chkAll" />全選</label></td>
      </tr>
      <?php 
      $count_status = ["0" => "正常運作" , "1" => "維修中" , "2" => "已停用"];
      $report_status = ["0" => "待處理" , "1" => "已確認報修" , "2" => "無法確認問題"];

      foreach($lights as $light){ ?>
      <tr>
        <td><?=h($count_status[$light->status])?></td>
        <td><?=h($light->name)?></td>
        <td><?=h($light->town_name)?></td>
        <td><?=_date_format_utc($light->mtime)?></td>
        <td><label><input class="chks" type="checkbox" name="ids[]" value="<?=h($light->id)?>" /> 選擇</label></td>
      </tr>
      <?php } ?>
    </table>
    
    
    <p>操作：
    <label><input type="radio" checked name="action" value="1" /> 下載 gdb 檔</label>
    <label><input type="radio" name="action" value="2" /> 設定為修好 </label>
    </p>
    <input type="submit" value="送出" />
  </form>
  
<div>
<script>
  window.points = <?=json_encode($lights)?>;
  if(window.points.push == null){
    window.points = [];
  }
</script>
  <h3>路燈位置</h3>
  <div id="mapid" style=" height: 400px;width:90%;"></div>
  <p>&nbsp;</p>

</div>

<?php function js_section(){ ?>

  <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>

  <script   src="https://code.jquery.com/jquery-2.2.4.min.js"   integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="   crossorigin="anonymous"></script> 


   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBSIFJslwcgjr4ttFgt0TX3KSG6sqLkzY8"
        ></script>
  <script src='https://unpkg.com/leaflet.gridlayer.googlemutant@latest/Leaflet.GoogleMutant.js'></script>

  <script src="<?=base_url("js/leaflet.markercluster.js")?>"></script>

  <script>


    $("#chkAll").change(function(){
      $(".chks").prop("checked",this.checked);
    });

    // var center = [25.043325,121.5195076];
    var point = window.points[0];
    var center = [point.lng,point.lat];

    var mymap = L.map('mapid',{maxZoom:18,minZoom:10}).setView(center, 14);
    

    // var markers = L.markerClusterGroup({disableClusteringAtZoom:17,spiderfyOnMaxZoom:false});

    $.each(window.points,function(ind,point){
        var redIcon = new L.Icon({
          iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
          shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
          iconSize: [25, 41],
          iconAnchor: [12, 41],
          popupAnchor: [1, -34],
          shadowSize: [41, 41]
        });
        var marker = L.marker([point.lng,point.lat],{icon:redIcon,title:point.name});
        marker.bindPopup('<h1>'+point.name+'</h1><p>所屬：'+point.city+point.town_name+'</p>');

        // markers.addLayer(marker);
        mymap.addLayer(marker);
    });

    // mymap.addLayer(markers);

    var osm = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(mymap);

    // var ggl = new L.Google();
    var roads = L.gridLayer.googleMutant({
        type: 'roadmap' // valid values are 'roadmap', 'satellite', 'terrain' and 'hybrid'
    }).addTo(mymap);

    var roads2 = L.gridLayer.googleMutant({
        type: 'satellite' // valid values are 'roadmap', 'satellite', 'terrain' and 'hybrid'
    }).addTo(mymap);

    mymap.addControl(new L.Control.Layers( {'開放街圖':osm,"Google 衛星":roads2,'Google':roads}, {}));
  </script>
  
</div>

<?php } ?>


<?php include(__DIR__."/../_footer.php"); ?>