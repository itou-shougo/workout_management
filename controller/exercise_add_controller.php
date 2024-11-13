<?php
require_once('../controller/base_controller.php');
require_once('../model/exercise_add_model.php');

// 種目登録画面コントローラ
class ExerciseAddController extends BaseController
{
    private $dbh;

    // 種目ID
    public $exerciseId;

    // 種目名称
    public $exerciseName;

    // 種目区分マスタ
    public $exerciseTypeMaster;

    // 対象筋マスタ
    public $targetMaster;

    // 種目マスタ
    public $exerciseMaster = [];

    // 画面表示
    public function load()
    {
        // 種目区分マスタ取得
        $this->exerciseTypeMaster = $this->selectAllExerciseType();

        // 対象筋マスタ取得
        $this->targetMaster = $this->selectAllTarget();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->exerciseId = $_POST["exercise_id"];
            $this->exerciseName = $_POST["exercise_name"];

            if (isset($_POST["add"])) {
                // 登録ボタン押下時
                // 未入力チェック
                $validation = new ValidationManager();
                if ($validation->isNotEmpty(array($this->exerciseName))) {
                    $exerciseTypeId = $_POST["exercise_type_id"];
                    $targetId = $_POST["target_id"];

                    if (!$this->exerciseId) {
                        // 新規登録
                        $result = $this->insertExercise(array(
                            'exercise_name' => $this->exerciseName,
                            'exercise_type_id' => $exerciseTypeId,
                            'target_id' => $targetId
                        ));
                    } else {
                        // 更新
                        $result = $this->updateExercise(array(
                            'exercise_id' => $this->exerciseId,
                            'exercise_name' => $this->exerciseName,
                            'exercise_type_id' => $exerciseTypeId,
                            'target_id' => $targetId
                        ));
                    }

                    if ($result) {
                        // 種目マスタ管理画面へ遷移
                        header("Location: exercise_management.php");
                        exit;
                    }
                } else {
                    // 未入力項目が存在するの場合、アラートを表示
                    $alert = "<script type='text/javascript'>alert('" . $validation->errMsg . "');</script>";
                    echo $alert;
                }
            }

            if (isset($_POST["edit"])) {
                // 編集ボタン押下時
                if ($this->exerciseId) {
                    $this->exerciseMaster = $this->selectExerciseById($this->exerciseId);
                }
            }
        }
    }

    // 種目区分マスタ取得
    private function selectAllExerciseType()
    {
        $model = new ExerciseAddModel();

        try {
            $db = new DatabaseManager();
            $this->dbh = $db->connectDatabase();
            $result = $model->selectAllExerciseType($this->dbh);
        } catch (Exception $e) {
            print($e->getMessage());
            die();
        } finally {
            $this->dbh = null;
        }

        return $result;
    }

    // 対象筋マスタ取得
    private function selectAllTarget()
    {
        $model = new ExerciseAddModel();

        try {
            $db = new DatabaseManager();
            $this->dbh = $db->connectDatabase();
            $result = $model->selectAllTarget($this->dbh);
        } catch (Exception $e) {
            print($e->getMessage());
            die();
        } finally {
            $this->dbh = null;
        }

        return $result;
    }

    // 種目マスタ取得
    private function selectExerciseById($exerciseId)
    {
        $model = new ExerciseAddModel();

        try {
            $db = new DatabaseManager();
            $this->dbh = $db->connectDatabase();
            $result = $model->selectExerciseById($this->dbh, $exerciseId);
        } catch (Exception $e) {
            print($e->getMessage());
            die();
        } finally {
            $this->dbh = null;
        }

        return $result;
    }

    // 種目マスタ登録
    private function insertExercise($exerciseInfo)
    {
        $result = false;
        $model = new ExerciseAddModel();

        try {
            $db = new DatabaseManager();
            $this->dbh = $db->connectDatabase();
            $model->insertExercise($this->dbh, $exerciseInfo);
            $result = true;
        } catch (Exception $e) {
            print($e->getMessage());
            die();
        } finally {
            $this->dbh = null;
        }

        return $result;
    }

    // 種目マスタ更新
    private function updateExercise($exerciseInfo)
    {
        $result = false;
        $model = new ExerciseAddModel();

        try {
            $db = new DatabaseManager();
            $this->dbh = $db->connectDatabase();
            $model->updateExercise($this->dbh, $exerciseInfo);
            $result = true;
        } catch (Exception $e) {
            print($e->getMessage());
            die();
        } finally {
            $this->dbh = null;
        }

        return $result;
    }
}
