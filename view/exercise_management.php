<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>種目管理画面</title>
  <link href="css/share.css" rel="stylesheet" />
</head>

<body>
  <?php
  require_once('../controller/exercise_management_controller.php');
  $controller = new ExerciseManagementController();
  $controller->load();
  ?>
  <h1>種目管理画面</h1>
  <div class="exercise-upper-btn-wrap">
    <input type="button" onclick="location.href='./home.php'" value="戻る">
    <input type="button" onclick="location.href='./exercise_add.php'" value="新規登録">
  </div>
  <div class="exercise-table-wrap">
    <!-- 種目テーブル -->
    <table>
      <?php if (count($controller->exerciseMaster) > 0) { ?>
        <tr>
          <th>名称</th>
          <th>種目区分</th>
          <th>対象筋</th>
          <th>編集／削除</th>
        </tr>
        <?php foreach ($controller->exerciseMaster as $row) { ?>
          <tr>
            <?php foreach ($row as $key => $cell) { ?>
              <?php if ($key != "exercise_id") { ?>
                <td><?= $cell ?></td>
              <?php } ?>
            <?php } ?>
            <td>
              <form method="POST" action="exercise_add.php">
                <input type="hidden" name="exercise_id" value=<?= $row["exercise_id"] ?>>
                <input type="hidden" name="exercise_name" value=<?= $row["exercise_name"] ?>>
                <input type="submit" name="edit" value="編集">
              </form>
              <form method="POST" action="exercise_management.php">
                <input type="hidden" name="id" value=<?= $row["exercise_id"] ?>>
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