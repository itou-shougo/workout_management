<?php
require_once('../controller/base_controller.php');
require_once('../model/exercise_management_model.php');

// 種目管理画面コントローラ
class ExerciseManagementController extends BaseController
{
    private $dbh;

    // 種目マスタ
    public $exerciseMaster;

    // 画面表示
    public function load()
    {
        // 種目マスタ取得
        $this->exerciseMaster = $this->selectAllExercise();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["delete"])) {
                // 削除ボタン押下時
                $exerciseId = $_POST["id"];
                $result = false;

                if (isset($exerciseId)) {
                    if (count($this->selectMenuExerciseByExerciseId($exerciseId)) == 0) {
                        // メニュー種目テーブルに存在しない種目のみ削除可
                        $result = $this->deleteExerciseById($exerciseId);
                    } else {
                        // メニュー種目テーブルに存在する種目の場合、アラートを表示
                        $alert = "<script type='text/javascript'>alert('メニューに登録されているため、削除できません。');</script>";
                        echo $alert;
                    }
                }

                if ($result) {
                    // 削除を反映するために本画面に遷移
                    header("Location: exercise_management.php");
                    exit;
                }
            }
        }
    }

    // 種目マスタ取得
    private function selectAllExercise()
    {
        $model = new ExerciseManagementModel();

        try {
            $db = new DatabaseManager();
            $this->dbh = $db->connectDatabase();
            $result = $model->selectAllExercise($this->dbh);
        } catch (Exception $e) {
            print($e->getMessage());
            die();
        } finally {
            $this->dbh = null;
        }

        return $result;
    }

    // 種目マスタ削除
    private function deleteExerciseById($exerciseId)
    {
        $model = new ExerciseManagementModel();

        try {
            $db = new DatabaseManager();
            $this->dbh = $db->connectDatabase();
            $result = $model->deleteExerciseById($this->dbh, $exerciseId);
        } catch (Exception $e) {
            print($e->getMessage());
            die();
        } finally {
            $this->dbh = null;
        }

        return $result;
    }

    // メニュー種目テーブル取得
    private function selectMenuExerciseByExerciseId($exerciseId)
    {
        $model = new ExerciseManagementModel();

        try {
            $db = new DatabaseManager();
            $this->dbh = $db->connectDatabase();
            $result = $model->selectMenuExerciseByExerciseId($this->dbh, $exerciseId);
        } catch (Exception $e) {
            print($e->getMessage());
            die();
        } finally {
            $this->dbh = null;
        }

        return $result;
    }
}
