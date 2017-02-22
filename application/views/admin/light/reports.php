<?php include(__DIR__."/../_header.php"); ?>

<div class="container" id="container" style="text-align:center;">
  <h1><?=h($city)?> 過去半年報修情形</h1>
  
  
  <p>&nbsp;</p>
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
    $count_status = ["0" => "正常運作" , "1" => "維修中" , "2" => "已停用"];

    $report_status = ["0" => "待處理" , "1" => "已確認報修" , "2" => "無法確認問題"];

    foreach($reports as $report){ ?>
    <tr>
      <td><?=_date_format($report->ctime)?></td>    
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