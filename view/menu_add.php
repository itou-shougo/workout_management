<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>メニュー登録画面</title>
  <link href="css/share.css" rel="stylesheet" />
</head>

<body>
  <?php
  require_once('../controller/menu_add_controller.php');
  $controller = new MenuAddController();
  $controller->load();
  ?>
  <h1>メニュー登録画面</h1>
  <?php if (count($controller->exerciseMaster) == 0) { ?>
    <p>※種目未登録のため登録できません。</p>
  <?php } ?>
  <form method="POST" action="menu_add.php">
    <input type="hidden" name="menu_id" value=<?= $controller->menuId ?>>
    <div class="menu-name-table-wrap">
      <table>
        <tr>
          <th>
            メニュー名
          </th>
          <td>
            <input type="text" name="menu_name" value="<?= $controller->menuName ?>" required>
          </td>
        </tr>
      </table>
    </div>
    <div class="menu-input-table-wrap">
      <table id="menuTbl">
        <tr>
          <th>No.</th>
          <th>種目</th>
          <th>セット数</th>
        </tr>
        <?php if (count($controller->menuExerciseInfo) > 0) { ?>
          <!-- 既存更新時 -->
          <?php foreach ($controller->menuExerciseInfo as $info) { ?>
            <tr>
              <td>
                <?= $info["exercise_no"] ?>
                <input id="hdnNo" type="hidden" name="exercise_no[]" value="<?= $info["exercise_no"] ?>">
              </td>
              <td>
                <select id="selExercise" name="exercise_id[]">
                  <?php foreach ($controller->exerciseMaster as $row) {
                    if ($row["id"] == $info["exercise_id"]) { ?>
                      <option value=<?= $row["id"] ?> selected><?= $row["name"] ?></option>
                    <?php } else { ?>
                      <option value=<?= $row["id"] ?>><?= $row["name"] ?></option>
                    <?php } ?>
                  <?php } ?>
                </select>
              </td>
              <td>
                <select id="selSets" name="sets[]">
                  <?php for ($i = 1; $i <= 10; $i++) {
                    if ($i == $info["sets"]) { ?>
                      <option value=<?= $i ?> selected><?= $i ?></option>
                    <?php } else { ?>
                      <option value=<?= $i ?>><?= $i ?></option>
                    <?php } ?>
                  <?php } ?>
                </select>
              </td>
            </tr>
          <?php } ?>
        <?php } else { ?>
          <!-- 新規登録時 -->
          <tr>
            <td>
              1
              <input id="hdnNo" type="hidden" name="exercise_no[]" value="1">
            </td>
            <td>
              <select id="selExercise" name="exercise_id[]">
                <?php foreach ($controller->exerciseMaster as $row) { ?>
                  <option value=<?= $row["id"] ?>><?= $row["name"] ?></option>
                <?php } ?>
              </select>
            </td>
            <td>
              <select id="selSets" name="sets[]">
                <?php for ($i = 1; $i <= 10; $i++) { ?>
                  <option value=<?= $i ?>><?= $i ?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
        <?php } ?>
      </table>
    </div>
    <div class="menu-input-lower-btn-wrap">
      <input type="button" onclick="location.href='./menu_management.php'" value="戻る">
      <?php if (count($controller->exerciseMaster) > 0) { ?>
        <input id="btnAdd" type="button" onclick="addRow()" value="行追加">
        <input id="btnDel" type="button" onclick="delRow()" value="行削除">
        <input type="submit" name="add" value="登録">
      <?php } ?>
    </div>
  </form>
  <script>
    // 行追加ボタン押下時
    function addRow() {
      const menuTbl = document.getElementById("menuTbl");
      let tr = document.createElement("tr");
      let rw = menuTbl.rows.length;

      // No.
      let tdNo = document.createElement("td");
      let hdnNo = document.getElementById("hdnNo").cloneNode(true);
      hdnNo.id = hdnNo.id + rw;
      tdNo.innerHTML = rw;
      hdnNo.value = rw;
      tdNo.appendChild(hdnNo);
      tr.appendChild(tdNo);

      // 種目
      let tdExercise = document.createElement("td");
      let selExercise = document.getElementById("selExercise").cloneNode(true);
      selExercise.id = selExercise.id + rw;
      selExercise.selectedIndex = 0;
      tdExercise.appendChild(selExercise);
      tr.appendChild(tdExercise);

      // セット数
      let tdSets = document.createElement("td");
      let selSets = document.getElementById("selSets").cloneNode(true);
      selSets.id = selSets.id + rw;
      selSets.selectedIndex = 0;
      tdSets.appendChild(selSets);
      tr.appendChild(tdSets);

      menuTbl.appendChild(tr);
    }

    // 行削除ボタン押下時
    function delRow() {
      const menuTbl = document.getElementById("menuTbl");
      let rw = menuTbl.rows.length;
      if (rw != 2) {
        menuTbl.deleteRow(rw - 1);
      }
    }
  </script>
</body>

</html>