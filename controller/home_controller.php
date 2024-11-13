<?php
require_once('../controller/base_controller.php');
require_once('../model/home_model.php');

// ホーム画面コントローラ
class HomeController extends BaseController
{
    private $dbh;

    // トレーニングテーブル
    public $workoutInfo;

    // 本日トレーニングしたか
    public $isWorkoutToday = false;

    // 画面表示
    public function load()
    {
        // トレーニングテーブル取得
        $this->workoutInfo = $this->selectAllWorkout();

        // 本日トレーニングしたかどうか判定
        foreach ($this->workoutInfo as $info) {
            if (date("Y-m-d") == $info["workout_date"]) {
                $this->isWorkoutToday = true;
            }
        }
    }

    // トレーニングテーブル取得
    private function selectAllWorkout()
    {
        $model = new HomeModel();

        try {
            $db = new DatabaseManager();
            $this->dbh = $db->connectDatabase();
            $result = $model->selectAllWorkout($this->dbh);
        } catch (Exception $e) {
            print($e->getMessage());
            die();
        } finally {
            $this->dbh = null;
        }

        return $result;
    }
}
