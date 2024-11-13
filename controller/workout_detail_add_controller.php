<?php
require_once('../controller/base_controller.php');
require_once('../model/workout_detail_add_model.php');

// トレーニング詳細登録画面コントローラ
class WorkoutDetailAddController extends BaseController
{
    private $dbh;

    // トレーニング日付
    public $workoutDate;

    // メニュー名称
    public $menuName;

    // メニュー種目テーブル
    public $menuExerciseInfo;

    // トレーニング種目テーブル
    public $workoutExerciseInfo;

    // 画面表示
    public function load()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->workoutDate = $_POST["workout_date"];

            // トレーニングテーブル取得
            $this->workoutExerciseInfo = $this->selectWorkoutByDate($this->workoutDate);

            if (count($this->workoutExerciseInfo) == 0) {
                // トレーニング新規登録時
                $menuId = isset($_POST["menu_id"]) ? $_POST["menu_id"] : null;

                if (isset($menuId)) {
                    // メニュー種目テーブル取得
                    $this->menuExerciseInfo = $this->selectMenuExerciseByMenuId($menuId);

                    // メニューマスタ取得（メニュー名称の取得）
                    $this->menuName = $this->selectMenuNameById($menuId);
                }
            } else {
                // トレーニング既存更新時
                $this->menuName = $this->workoutExerciseInfo[0]["menu_name"];
            }

            if (isset($_POST["add"])) {
                // 登録ボタン押下時
                $weight = $_POST["weight"];
                $reps = $_POST["reps"];

                // 型変換
                for ($i = 0; $i < count($weight); $i++) {
                    $weight[$i] = (float)$weight[$i];
                    $reps[$i] = (int)$reps[$i];
                }

                // 各種入力チェック
                $validation = new ValidationManager();
                if (
                    $validation->isNotEmpty(array_merge($weight, $reps))
                    && $validation->isValidWeight($weight)
                    && $validation->isValidReps($reps)
                ) {
                    $this->menuName = $_POST["menu_name"];
                    $exerciseNo = $_POST["exercise_no"];
                    $exerciseName = $_POST["exercise_name"];
                    $set = $_POST["set"];
                    $workoutExerciseRegisterInfo = [];

                    // トレーニング種目テーブルの登録・更新データ作成
                    $exerciseCount = 0;
                    for ($i = 0; $i < count($set); $i++) {
                        if ($i != 0 && $set[$i] == 1) {
                            // 初回ループ以外の1セット目は別種目と判定
                            $exerciseCount++;
                        }
                        array_push($workoutExerciseRegisterInfo, array(
                            "exercise_no" => $exerciseNo[$exerciseCount],
                            "exercise_name" => $exerciseName[$exerciseCount],
                            "set" => $set[$i],
                            "weight" => $weight[$i],
                            "reps" => $reps[$i]
                        ));
                    }

                    if (count($this->selectWorkoutByDate($this->workoutDate)) == 0) {
                        // 新規登録
                        $result = $this->insertWorkout($this->workoutDate, $this->menuName, $workoutExerciseRegisterInfo);
                    } else {
                        // 更新
                        $result = $this->updateWorkout($this->workoutExerciseInfo[0]["workout_id"], $this->workoutDate, $this->menuName, $workoutExerciseRegisterInfo);
                    }

                    if ($result) {
                        // ホーム画面へ遷移
                        header("Location: home.php");
                        exit;
                    }
                } else {
                    // 未入力項目が存在するの場合、アラートを表示
                    $alert = "<script type='text/javascript'>alert('" . $validation->errMsg . "');</script>";
                    echo $alert;
                }
            }

            if (isset($_POST["delete"])) {
                // 削除ボタン押下時
                // メニューマスタとメニュー種目テーブル削除
                $result = $this->deleteWorkoutAndWorkoutExercise($this->workoutExerciseInfo[0]["workout_id"]);

                if ($result) {
                    // ホーム画面へ遷移
                    header("Location: home.php");
                    exit;
                }
            }
        }
    }

    // メニュー種目テーブル取得
    private function selectMenuExerciseByMenuId($menuId)
    {
        $model = new WorkoutDetailAddModel();

        try {
            $db = new DatabaseManager();
            $this->dbh = $db->connectDatabase();
            $result = $model->selectMenuExerciseByMenuId($this->dbh, $menuId);
        } catch (Exception $e) {
            print($e->getMessage());
            die();
        } finally {
            $this->dbh = null;
        }

        return $result;
    }

    // トレーニングテーブル取得
    private function selectWorkoutByDate($workoutDate)
    {
        $model = new WorkoutDetailAddModel();

        try {
            $db = new DatabaseManager();
            $this->dbh = $db->connectDatabase();
            $result = $model->selectWorkoutByDate($this->dbh, $workoutDate);
        } catch (Exception $e) {
            print($e->getMessage());
            die();
        } finally {
            $this->dbh = null;
        }

        return $result;
    }

    // メニュー名称取得
    private function selectMenuNameById($menuId)
    {
        $model = new WorkoutDetailAddModel();

        try {
            $db = new DatabaseManager();
            $this->dbh = $db->connectDatabase();
            $result = $model->selectMenuNameById($this->dbh, $menuId);
        } catch (Exception $e) {
            print($e->getMessage());
            die();
        } finally {
            $this->dbh = null;
        }

        return $result;
    }

    // トレーニングテーブル登録
    private function insertWorkout($workoutDate, $menuName, $workoutExerciseRegisterInfo)
    {
        $result = false;
        $model = new WorkoutDetailAddModel();

        try {
            $db = new DatabaseManager();
            $this->dbh = $db->connectDatabase();
            $this->dbh->beginTransaction();

            // メニューマスタ登録後のIDでメニュー種目テーブルに登録
            $insertedId = $model->insertWorkout($this->dbh, $workoutDate, $menuName);
            $model->insertWorkoutExercise($this->dbh, $insertedId, $workoutExerciseRegisterInfo);
            $this->dbh->commit();
            $result = true;
        } catch (Exception $e) {
            print($e->getMessage());
            $this->dbh->rollBack();
            die();
        } finally {
            $this->dbh = null;
        }

        return $result;
    }

    // トレーニングテーブル更新
    private function updateWorkout($workoutId, $workoutDate, $menuName, $workoutExerciseRegisterInfo)
    {
        $result = false;
        $model = new WorkoutDetailAddModel();

        try {
            $db = new DatabaseManager();
            $this->dbh = $db->connectDatabase();
            $this->dbh->beginTransaction();

            // トレーニングテーブル更新 ⇒ トレーニング種目テーブル削除 ⇒ トレーニング種目テーブル登録
            $model->updateWorkout($this->dbh, $workoutId, $workoutDate, $menuName);
            $model->deleteWorkoutExerciseByWorkoutId($this->dbh, $workoutId);
            $model->insertWorkoutExercise($this->dbh, $workoutId, $workoutExerciseRegisterInfo);
            $this->dbh->commit();
            $result = true;
        } catch (Exception $e) {
            print($e->getMessage());
            $this->dbh->rollBack();
            die();
        } finally {
            $this->dbh = null;
        }

        return $result;
    }

    // トレーニングテーブルとトレーニング種目テーブル削除
    private function deleteWorkoutAndWorkoutExercise($workoutId)
    {
        $result = false;
        $model = new WorkoutDetailAddModel();

        try {
            $db = new DatabaseManager();
            $this->dbh = $db->connectDatabase();
            $this->dbh->beginTransaction();
            $model->deleteWorkoutById($this->dbh, $workoutId);
            $model->deleteWorkoutExerciseByWorkoutId($this->dbh, $workoutId);
            $this->dbh->commit();
            $result = true;
        } catch (Exception $e) {
            print($e->getMessage());
            $this->dbh->rollBack();
            die();
        } finally {
            $this->dbh = null;
        }

        return $result;
    }
}
