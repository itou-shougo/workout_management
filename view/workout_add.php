<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>トレーニング登録画面</title>
    <link href="css/share.css" rel="stylesheet" />
</head>

<body>
    <?php
    require_once('../controller/workout_add_controller.php');
    $controller = new WorkoutAddController();
    $controller->load();
    ?>
    <h1>トレーニング登録画面</h1>
    <?php if (count($controller->menuMaster) == 0) { ?>
        <p>※メニュー未登録のため登録できません。</p>
    <?php } ?>
    <form method="POST" action="workout_detail_add.php">
        <div class="workout-input-table-wrap">
            <table>
                <tr>
                    <th>
                        日付
                    </th>
                    <td>
                        <p><?= $controller->workoutDate ?></p>
                        <input type="hidden" name="workout_date" value="<?= $controller->workoutDate ?>">
                    </td>
                </tr>
                <tr>
                    <th>
                        メニュー名
                    </th>
                    <td>
                        <select id="selMenu" name="menu_id">
                            <?php foreach ($controller->menuMaster as $row) { ?>
                                <option value=<?= $row["id"] ?>><?= $row["name"] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <div class="workout-input-lower-btn-wrap">
            <input type="button" onclick="location.href='./home.php'" value="戻る">
            <?php if (count($controller->menuMaster) > 0) { ?>
                <input type="submit" name="next" value="次へ">
            <?php } ?>
        </div>
    </form>
</body>

</html>