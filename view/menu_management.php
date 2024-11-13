<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>メニュー管理画面</title>
  <link href="css/share.css" rel="stylesheet" />
</head>

<body>
  <?php
  require_once('../controller/menu_management_controller.php');
  $controller = new MenuManagementController();
  $controller->load();
  ?>
  <h1>メニュー管理画面</h1>
  <div class="menu-upper-btn-wrap">
    <input type="button" onclick="location.href='./home.php'" value="戻る">
    <input type="button" onclick="location.href='./menu_add.php'" value="新規登録">
  </div>
  <div class="menu-table-wrap">
    <!-- メニューテーブル -->
    <table>
      <?php if (count($controller->menuMaster) > 0) { ?>
        <tr>
          <th>名称</th>
          <th>編集／削除</th>
        </tr>
        <?php foreach ($controller->menuMaster as $row) { ?>
          <tr>
            <?php foreach ($row as $key => $cell) {
              if ($key != "id") { ?>
                <td><?= $cell ?></td>
              <?php } ?>
            <?php } ?>
            <td>
              <form method="POST" action="menu_add.php">
                <input type="hidden" name="menu_id" value=<?= $row["id"] ?>>
                <input type="hidden" name="menu_name" value=<?= $row["name"] ?>>
                <input type="submit" name="edit" value="編集">
              </form>
              <form method="POST" action="menu_management.php">
                <input type="hidden" name="menu_id" value=<?= $row["id"] ?>>
                <input type="submit" name="delete" value="削除">
              </form>
            </td>
          </tr>
        <?php } ?>
      <?php } ?>
    </table>
  </div>
</body>

</html>