<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>種目登録画面</title>
  <link href="css/share.css" rel="stylesheet" />
</head>

<body>
  <?php
  require_once('../controller/exercise_add_controller.php');
  $controller = new ExerciseAddController();
  $controller->load();
  ?>
  <h1>種目登録画面</h1>
  <form method="POST" action="exercise_add.php">
    <input type="hidden" name="exercise_id" value=<?= $controller->exerciseId ?>>
    <div class="exercise-input-table-wrap">
      <table>
        <tr>
          <th>
            種目名
          </th>
          <td>
            <input type="text" name="exercise_name" value="<?= $controller->exerciseName ?>" required>
          </td>
        </tr>
        <?php if (count($controller->exerciseMaster) > 0) { ?>
          <!-- 既存更新 -->
          <tr>
            <th>
              種目区分
            </th>
            <td>
              <select name="exercise_type_id">
                <?php foreach ($controller->exerciseTypeMaster as $row) {
                  if ($row["id"] == $controller->exerciseMaster["exercise_type_id"]) { ?>
                    <option value=<?= $row["id"] ?> selected><?= $row["name"] ?></option>
                  <?php } else { ?>
                    <option value=<?= $row["id"] ?>><?= $row["name"] ?></option>
                  <?php } ?>
                <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <th>
              対象筋
            </th>
            <td>
              <select name="target_id">
                <?php foreach ($controller->targetMaster as $row) {
                  if ($row["id"] == $controller->exerciseMaster["target_id"]) { ?>
                    <option value=<?= $row["id"] ?> selected><?= $row["name"] ?></option>
                  <?php } else { ?>
                    <option value=<?= $row["id"] ?>><?= $row["name"] ?></option>
                  <?php } ?>
                <?php } ?>
              </select>
            </td>
          </tr>
        <?php } else { ?>
          <!-- 新規登録 -->
          <tr>
            <th>
              種目区分
            </th>
            <td>
              <select name="exercise_type_id">
                <?php foreach ($controller->exerciseTypeMaster as $row) { ?>
                  <option value=<?= $row["id"] ?>><?= $row["name"] ?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <th>
              対象筋
            </th>
            <td>
              <select name="target_id">
                <?php foreach ($controller->targetMaster as $row) { ?>
                  <option value=<?= $row["id"] ?>><?= $row["name"] ?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
        <?php } ?>
      </table>
    </div>
    <div class="exercise-input-lower-btn-wrap">
      <input type="button" onclick="location.href='./exercise_management.php'" value="戻る">
      <input type="submit" name="add" value="登録">
    </div>
  </form>
</body>

</html>