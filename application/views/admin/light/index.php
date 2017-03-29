<?php include(__DIR__."/../_header.php"); ?>

<div class="container" id="container" style="text-align:center;">
  <h1><?=h($city)?> 路燈現況最新資料</h1>
  
  
  <h2>路燈總覽</h2>
  <table class="table table-bordered">
    <tr>
      <td>路燈狀態</td>
      <td>數量</td>
    </tr>
    <?php 
    $count_status = ["0" => "正常運作" , "1" => "維修中" , "2" => "已停用"];
    $report_status = ["0" => "待處理" , "1" => "已確認報修" , "2" => "無法確認問題"];

    foreach($city_counts as $c_count){ ?>
    <tr>
      <td><?=h($count_status[$c_count->status])?></td>
      <td><?=h($c_count->count)?></td>
    </tr>
    <?php } ?>
  </table>
  
  <p>&nbsp;</p>
  <h2>報修情形</h2>
  <p>尚未處理的報修紀錄</p>
  <table class="table table-bordered">
    <tr>
      <td>報修時間</td>    
      <td>處理情形 </td>
      <td>目前系統路燈狀態 </td>
      <td>報修路燈編號</td>
      <td>報修人</td>
      <td>報修描述</td>
      <td>處理結果</td>
    </tr>
    <?php 

    foreach($reports as $report){ ?>
    <tr>
      <td><?=_date_format_utc($report->ctime)?></td>    
      <td><?=h($report_status[$report->status])?></td>
      <td><?=h($count_status[$report->light_status])?></td>
      <td><?=h($report->light_name)?></td>
      <td><?=h($report->name)?></td>
      <td><?=h($report->comment)?></td>
      <td>
         <?php if($report->status =="0"){ ?>
        <a href="<?=site_url("admin/light/set_report_status/".$report->id."/1")?>" class="btn btn-default">請廠商處理</a>
        <a href="<?=site_url("admin/light/set_report_status/".$report->id."/0")?>" class="btn btn-default">設為無法確認問題</a>
        <?php } ?>
      </td>
    </tr>
    <?php } ?>
  </table>
  


</div>

<?php function js_section(){ ?>

<?php } ?>


<?php include(__DIR__."/../_footer.php"); ?>