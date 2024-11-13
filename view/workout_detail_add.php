<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>トレーニング詳細登録画面</title>
    <link href="css/share.css" rel="stylesheet" />
</head>

<body>
    <?php
    require_once('../controller/workout_detail_add_controller.php');
    $controller = new WorkoutDetailAddController();
    $controller->load();
    ?>
    <h1>トレーニング詳細登録画面</h1>
    <form method="POST" action="workout_detail_add.php">
        <div class="workoutdate-table-wrap">
            <table>
                <tr>
                    <th>
                        実施日
                    </th>
                    <th>
                        <p><?= date("Y年m月d日", strtotime($controller->workoutDate)) ?></p>
                        <input id="hdnWorkoutDate" type="hidden" name="workout_date" value="<?= $controller->workoutDate ?>">
                    </th>
                </tr>
                <tr>
                    <th>
                        実施メニュー
                    </th>
                    <th>
                        <p><?= $controller->menuName ?></p>
                        <input id="hdnMenuName" type="hidden" name="menu_name" value="<?= $controller->menuName ?>">
                    </th>
                </tr>
            </table>
        </div>
        <div class="workout-detail-input-table-wrap">
            <table>
                <tr>
                    <th>No.</th>
                    <th>種目</th>
                    <th>セット</th>
                    <th>重量</th>
                    <th>回数</th>
                </tr>
                <?php if (count($controller->workoutExerciseInfo) > 0) { ?>
                    <!-- 既存更新時 -->
                    <?php for ($i = 0; $i < count($controller->workoutExerciseInfo); $i++) { ?>
                        <tr>
                            <?php if ($controller->workoutExerciseInfo[$i]["set"] == 1) { ?>
                                <td class="td-has-item">
                                    <?= $controller->workoutExerciseInfo[$i]["exercise_no"] ?>
                                    <input id="hdnExerciseNo" type="hidden" name="exercise_no[]" value="<?= $controller->workoutExerciseInfo[$i]["exercise_no"] ?>">
                                </td>
                                <td class="td-has-item">
                                    <?= $controller->workoutExerciseInfo[$i]["exercise_name"] ?>
                                    <input id="hdnExerciseName" type="hidden" name="exercise_name[]" value="<?= $controller->workoutExerciseInfo[$i]["exercise_name"] ?>">
                                </td>
                            <?php } else { ?>
                                <td class="td-has-no-item"></td>
                                <td class="td-has-no-item"></td>
                            <?php } ?>
                            <td>
                                <?= $controller->workoutExerciseInfo[$i]["set"] ?>
                                <input id="hdnSet" type="hidden" name="set[]" value="<?= $controller->workoutExerciseInfo[$i]["set"] ?>">
                            </td>
                            <td class="td-weight">
                                <input type="number" name="weight[]" min="0.00" max="999.99" step="0.01" placeholder="123.45" value="<?= $controller->workoutExerciseInfo[$i]["weight"] ?>" required>
                                <p>kg</p>
                            </td>
                            <td class="td-reps">
                                <input type="number" name="reps[]" min="0" max="99" value="<?= $controller->workoutExerciseInfo[$i]["reps"] ?>" required>
                                <p>回</p>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <!-- 新規登録時 -->
                    <?php foreach ($controller->menuExerciseInfo as $info) { ?>
                        <?php for ($i = 0; $i < $info["sets"]; $i++) { ?>
                            <tr>
                                <?php if ($i == 0) { ?>
                                    <td class="td-has-item">
                                        <?= $info["exercise_no"] ?>
                                        <input id="hdnExerciseNo" type="hidden" name="exercise_no[]" value="<?= $info["exercise_no"] ?>">
                                    </td>
                                    <td class="td-has-item">
                                        <?= $info["exercise_name"] ?>
                                        <input id="hdnExerciseName" type="hidden" name="exercise_name[]" value="<?= $info["exercise_name"] ?>">
                                    </td>
                                <?php } else { ?>
                                    <td class="td-has-no-item"></td>
                                    <td class="td-has-no-item"></td>
                                <?php } ?>
                                <td>
                                    <?= $i + 1 ?>
                                    <input id="hdnSet" type="hidden" name="set[]" value="<?= $i + 1 ?>">
                                </td>
                                <td class="td-weight">
                                    <input type="number" name="weight[]" min="0.00" max="999.99" step="0.01" placeholder="123.45" value="<?= "" ?>" required>
                                    <p>kg</p>
                                </td>
                                <td class="td-reps">
                                    <input type="number" name="reps[]" min="0" max="99" value="<?= "" ?>" required>
                                    <p>回</p>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </table>
        </div>
        <div class="workout-detail-input-lower-btn-wrap">
            <input type="button" onclick="history.back()" value="戻る">
            <input type="submit" name="delete" value="削除">
            <input type="submit" name="add" value="登録">
        </div>
    </form>
</body>

</html>