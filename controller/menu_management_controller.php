<?php
require_once('../controller/base_controller.php');
require_once('../model/menu_management_model.php');

// メニュー管理画面コントローラー
class MenuManagementController extends BaseController
{
    private $dbh;

    // メニューマスタ
    public $menuMaster;

    // 画面表示
    public function load()
    {
        // 種目マスタ取得
        $this->menuMaster = $this->selectAllMenu();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["delete"])) {
                // 削除ボタン押下時
                $menuId = $_POST["menu_id"];

                if (isset($menuId)) {
                    // メニューマスタとメニュー種目テーブル削除
                    $result = $this->deleteMenuAndMenuExercise($menuId);
                }

                if ($result) {
                    // 削除を反映するために本画面に遷移
                    header("Location: menu_management.php");
                    exit;
                }
            }
        }
    }

    // メニューマスタ取得
    private function selectAllMenu()
    {
        $model = new MenuManagementModel();

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

    // メニューマスタとメニュー種目テーブル削除
    private function deleteMenuAndMenuExercise($menuId)
    {
        $result = false;
        $model = new MenuManagementModel();

        try {
            $db = new DatabaseManager();
            $this->dbh = $db->connectDatabase();
            $this->dbh->beginTransaction();
            $model->deleteMenuById($this->dbh, $menuId);
            $model->deleteMenuExerciseByMenuId($this->dbh, $menuId);
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
