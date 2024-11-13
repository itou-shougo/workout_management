<?php
require_once('../controller/base_controller.php');
require_once('../model/menu_add_model.php');

// メニュー登録画面コントローラ
class MenuAddController extends BaseController
{
    private $dbh;

    // 種目マスタ
    public $exerciseMaster;

    // メニューID
    public $menuId;

    // メニュー名称
    public $menuName;

    // メニュー種目テーブル
    public $menuExerciseInfo = [];

    // 画面表示
    public function load()
    {
        // 種目マスタ取得
        $this->exerciseMaster = $this->selectAllExercise();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->menuId = $_POST["menu_id"];
            $this->menuName = $_POST["menu_name"];

            if (isset($_POST["add"])) {
                // 登録ボタン押下時
                // 未入力チェック
                $validation = new ValidationManager();
                if ($validation->isNotEmpty(array($this->menuName))) {
                    $exerciseNo = $_POST["exercise_no"];
                    $exerciseId = $_POST["exercise_id"];
                    $sets = $_POST["sets"];
                    $menuExerciseRegisterInfo = [];

                    // メニュー種目テーブルの登録・更新データ作成
                    for ($i = 0; $i < count($exerciseNo); $i++) {
                        array_push($menuExerciseRegisterInfo, array(
                            "exercise_id" => $exerciseId[$i],
                            "exercise_no" => $exerciseNo[$i],
                            "sets" => $sets[$i]
                        ));
                    }

                    if (count($menuExerciseRegisterInfo) > 0 && !$this->menuId) {
                        // 新規登録
                        $result = $this->insertMenu($this->menuName, $menuExerciseRegisterInfo);
                    } elseif (count($menuExerciseRegisterInfo) > 0 && $this->menuId) {
                        // 更新
                        $result = $this->updateMenu($this->menuId, $this->menuName, $menuExerciseRegisterInfo);
                    }

                    if ($result) {
                        // メニュー管理画面へ遷移
                        header("Location: menu_management.php");
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
                if ($this->menuId) {
                    $this->menuExerciseInfo = $this->selectMenuExerciseByMenuId($this->menuId);
                }
            }
        }
    }

    // 種目マスタ取得
    private function selectAllExercise()
    {
        $model = new MenuAddModel();

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

    // メニューマスタ登録
    private function insertMenu($menuName, $menuExerciseRegisterInfo)
    {
        $result = false;
        $model = new MenuAddModel();

        try {
            $db = new DatabaseManager();
            $this->dbh = $db->connectDatabase();
            $this->dbh->beginTransaction();

            // メニューマスタ登録後のIDでメニュー種目テーブルに登録
            $insertedId = $model->insertMenu($this->dbh, $menuName);
            $model->insertMenuExercise($this->dbh, $insertedId, $menuExerciseRegisterInfo);
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

    // メニュー種目テーブル取得
    private function selectMenuExerciseByMenuId($menuId)
    {
        $model = new MenuAddModel();

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

    // メニューマスタ更新
    private function updateMenu($menuId, $menuName, $menuExerciseRegisterInfo)
    {
        $result = false;
        $model = new MenuAddModel();

        try {
            $db = new DatabaseManager();
            $this->dbh = $db->connectDatabase();
            $this->dbh->beginTransaction();

            // メニューマスタ更新 ⇒ メニュー種目テーブル削除 ⇒ メニュー種目テーブル登録
            $model->updateMenu($this->dbh, $menuId, $menuName);
            $model->deleteMenuExerciseByMenuId($this->dbh, $menuId);
            $model->insertMenuExercise($this->dbh, $menuId, $menuExerciseRegisterInfo);
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
