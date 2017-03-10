<?php echo'<?xml version="1.0" encoding="UTF-8" standalone="no" ?>' ?>
<gpx xmlns="http://www.topografix.com/GPX/1/1" creator="MapSource 6.16.3" version="1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd">

  <metadata>
    <link href="http://www.garmin.com">
      <text>Garmin International</text>
    </link>
    <time>2017-02-22T01:57:44Z</time>
    <bounds maxlat="23.410032531246543" maxlon="120.31601456925273" minlat="23.410032531246543" minlon="120.31601456925273"/>
  </metadata>

  <?php foreach($lights as $light){ ?>
  <wpt lat="<?=$light->lng?>" lon="<?=$light->lat?>">
  <?php if(0){ ?>
    <ele><?=$light->height?></ele>
  <?php } ?>
    <name><?=$light->name?></name>
    <cmt></cmt>
    <desc></desc>
    <sym>Flag, Blue</sym>
    <extensions>
      <gpxx:WaypointExtension xmlns:gpxx="http://www.garmin.com/xmlschemas/GpxExtensions/v3">
        <gpxx:DisplayMode>SymbolAndName</gpxx:DisplayMode>
      </gpxx:WaypointExtension>
    </extensions>
  </wpt>
  <?php } ?>
</gpx>
