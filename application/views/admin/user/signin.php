<?php include(__DIR__."/../../_header.php"); ?>

<?php function css_section(){  ?>
<?php } ?>

<div class="col-xs-8 col-xs-offset-2">
  <form action="<?=site_url("admin/user/signing")?>" method="POST">
    <?php if($fail == "1") { ?>
    <p style='color:red;'>登入失敗:帳號密碼錯誤。</p>
    <?php } ?>
    <table class="table table-bordered">
      <tr><td>帳號</td><td> <input type="text" name="account" /></td></tr>
      <tr><td>密碼 </td><td><input type="text" name="pwd" /></td></tr>
      <tr><td colspan="2"><button> 送出 </button> </td></tr>
    </table>
  </form>
</div>
<?php function js_section(){ ?>

<?php } ?>


<?php include(__DIR__."/../../_footer.php"); ?>