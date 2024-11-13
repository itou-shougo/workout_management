<?php
require_once('../controller/base_controller.php');
require_once('../model/workout_add_model.php');

// トレーニング登録画面コントローラ
class WorkoutAddController extends BaseController
{
    private $dbh;

    // メニューマスタ
    public $menuMaster;

    // トレーニング日付
    public $workoutDate;

    // 画面表示
    public function load()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->workoutDate = $_POST["workout_date"];
        }

        // メニューマスタ取得
        $this->menuMaster = $this->selectAllMenu();
    }

    // メニューマスタ取得
    private function selectAllMenu()
    {
        $model = new WorkoutAddModel();

        try {
            $db = new DatabaseManager();
            $this->dbh = $db->connectDatabase();
            $result = $model->selectAllMenu($this->dbh);
        } catch (Exception $e) {
            print($e->getMessage());
            die();
        } finally {
            $this->dbh = null;
        }

        return $result;
    }
}
