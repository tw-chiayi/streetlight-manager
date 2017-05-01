<?php include(__DIR__."/../_header.php"); ?>

<?php function css_section(){  ?>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css" />
  <link rel="stylesheet" href="<?=base_url("css/MarkerCluster.css")?>" />
  <link rel="stylesheet" href="<?=base_url("css/MarkerCluster.Default.css")?>" />
  <link rel="stylesheet" href="<?=base_url("js/jquery-ui-1.12.1.custom/jquery-ui.min.css")?>">

  <style>
    hr{
      border-top:1px solid #ccc;
    }
    .ui-widget.ui-widget-content{
      z-index:1000;
    }
    .ui-autocomplete {
      max-height: 300px;
      width:400px;
      overflow-y: auto;
      /* prevent horizontal scrollbar */
      overflow-x: hidden;
    }
    /* IE 6 doesn't support max-height
     * we use height instead, but this forces the menu to always be this tall
     */
    * html .ui-autocomplete {
      height: 300px;
      width:400px;
    }
  </style>
<?php } ?>

<script>
  window.points = <?=json_encode($points)?>;
  if(window.points.push == null){
    window.points = [];
  }
</script>
<div id="container" class="container">
  <h1 style="text-align:center;"> 路燈清單</h1>
  <div class="alert alert-info">
    目前測試中，僅開放鹿草鄉資料。
  </div>

  <p>燈號搜尋：<input type="text" name="search" /></p>
    
  <p id="message" style='color:red;'></p>
  <div id="mapid" style="width: 100%; height: 600px"></div>
    
  <hr />
  <p>Power by <a href="https://github.com/tony1223/" target="_blank">智慧城市與青年創業推動辦公室</a> </p>
  

  <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>

  <script   src="https://code.jquery.com/jquery-2.2.4.min.js"   integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="   crossorigin="anonymous"></script> 


   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBSIFJslwcgjr4ttFgt0TX3KSG6sqLkzY8"
        ></script>
  <script src='https://unpkg.com/leaflet.gridlayer.googlemutant@latest/Leaflet.GoogleMutant.js'></script>

  <script src="<?=base_url("js/leaflet.markercluster.js")?>"></script>

  <script src="<?=base_url("js/jquery-ui-1.12.1.custom/jquery-ui.js")?>"></script>


  <script>

    var greenIcon = new L.Icon({
      iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
      shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      shadowSize: [41, 41]
    });
    var violetIcon = new L.Icon({
      iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-violet.png',
      shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      shadowSize: [41, 41]
    });


    // var center = [25.043325,121.5195076];
    var point = window.points[0];
    var center = [point.lat,point.lng];

    var mymap = L.map('mapid',{maxZoom:18,minZoom:12}).setView(center, 12);
    

    var markers = L.markerClusterGroup({disableClusteringAtZoom:17,spiderfyOnMaxZoom:false});

    window.reloc = function(id){
      $(".leaflet-popup-close-button")[0].click()
      $("#message").text("請選擇你要修改的新位置");
    };
    var autocompletes = [];
    var helper = null;
    $.each(window.points,function(ind,point){

      if(point.status == 0){
        var marker = L.marker([point.lat,point.lng],{
          title:point.name,
          draggable: true
        });
        marker.bindPopup('<h1>'+point.name+'</h1><p>所屬：'+point.city+point.town_name+'</p>'+
          '<a target="_blank" href="<?=site_url('light/report/')?>/'+point.id+'">回報路燈問題</a><p></p>'
          );
        marker.on('dragstart', function (e) {
          helper =  L.marker([point.lat,point.lng],{
            title:point.name,
            icon:greenIcon
          });
          // marker.setIcon(violetIcon);
          mymap.addLayer(helper);

        });
        marker.on('dragend', function (e) {

          if(window.confirm("您正在修改路燈，是否確定？")){
            var latlng = e.target.getLatLng();
            $.post("/admin/light/change_loc/"+point.id,{lat:latlng.lat,lng:latlng.lng},
              function(strRes){
                var res = JSON.parse(strRes);
                if(res.success){
                  $("#message").text("修改成功");
                  point.lat = e.target.getLatLng().lat;
                  point.lng = e.target.getLatLng().lng;
                  marker.setLatLng(e.target.getLatLng());
                }else{
                  $("#message").text("修改失敗");
                  marker.setLatLng([point.lat,point.lng]);
                }
            });
          }else{
            marker.setLatLng([point.lat,point.lng]);
          }

          mymap.removeLayer(helper);
          //e.latlng
          //e.oldLatLng
        });
        markers.addLayer(marker);
      }else{
        var redIcon = new L.Icon({
          iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
          shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
          iconSize: [25, 41],
          iconAnchor: [12, 41],
          popupAnchor: [1, -34],
          shadowSize: [41, 41]
        });
        var marker = L.marker([point.lat,point.lng],{icon:redIcon,title:point.name,
          draggable: true});
        marker.on('dragstart', function (e) {
          helper =  L.marker([point.lat,point.lng],{
            title:point.name,
            icon:greenIcon
          });
          // marker.setIcon(violetIcon);
          mymap.addLayer(helper);
        });
        marker.on('dragend', function (e) {

          if(window.confirm("您正在修改路燈，是否確定？")){
            var latlng = e.target.getLatLng();
            $.post("/admin/light/change_loc/"+point.id,{lat:latlng.lat,lng:latlng.lng},
              function(strRes){
                var res = JSON.parse(strRes);
                if(res.success){
                  $("#message").text("修改成功");
                  point.lat = e.target.getLatLng().lat;
                  point.lng = e.target.getLatLng().lng;
                  marker.setLatLng(e.target.getLatLng());
                }else{
                  $("#message").text("修改失敗");
                  marker.setLatLng([point.lat,point.lng]);
                }
            });
          }else{
            marker.setLatLng([point.lat,point.lng]);
          }

          mymap.removeLayer(helper);
          //e.latlng
          //e.oldLatLng
        });
        marker.bindPopup('<h1>'+point.name+'</h1><p>所屬：'+point.city+point.town_name+'</p><p style="color:red;">路燈報修中</p>');
        
        // markers.addLayer(marker);
        mymap.addLayer(marker);
      }
      point.marker = marker;
      autocompletes.push({label:
        point.name + " - " + point.city + point.town_name
      ,value:point});      
    });

    // debugger;
    $( "[name=search]" ).autocomplete({
      minLength: 1,
      delay: 0 ,
      source: function(req,res){
        res( $.grep( autocompletes, function( item ){
            return item.label.indexOf(req.term) != -1;
        }).slice(0,100) );
      },
      select: function( event, ui ) {
        mymap.setView(ui.item.value.marker.getLatLng(),18);
        ui.item.value.marker.openPopup();
        return false;
      }
    });

    mymap.addLayer(markers);

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

    mymap.on('contextmenu', function(e) { 
      var temp = L.marker(e.latlng,{
        title:point.name,
        icon:greenIcon
      });
      temp.bindPopup('<a href="">新增路燈</a>');
      temp.addTo(mymap);
    });

  </script>
  
</div>

<?php function js_section(){ ?>

<?php } ?>


<?php include(__DIR__."/../_footer.php"); ?>