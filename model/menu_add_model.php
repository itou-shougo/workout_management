<?php

// メニュー登録画面モデル
class MenuAddModel
{
    // 種目マスタ取得
    public function selectAllExercise($dbh)
    {
        $sql = <<<SQL
            SELECT
                m_exercise.id
                , m_exercise.name
            FROM 
                m_exercise 
            ORDER BY 
                m_exercise.id ASC;
        SQL;

        $res = $dbh->query($sql);
        $response = $res->fetchAll(PDO::FETCH_ASSOC);
        return $response;
    }

    // メニューマスタ登録
    public function insertMenu($dbh, $menuName)
    {
        $sql = <<<SQL
            INSERT INTO 
                m_menu 
                ( 
                    name 
                ) 
                VALUES 
                ( 
                    :menu_name 
                ) 
        SQL;
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':menu_name', $menuName);
        $stmt->execute();
        $response = $dbh->lastInsertId();
        return $response;
    }

    // メニュー種目テーブル登録
    public function insertMenuExercise($dbh, $menuId, $menuExerciseRegisterInfo)
    {
        $values = "";
        for ($i = 0; $i < count($menuExerciseRegisterInfo); $i++) {
            $values .= "( :menu_id, " . join(', ', $menuExerciseRegisterInfo[$i]) . ")";
            if ($i < count($menuExerciseRegisterInfo) - 1) {
                $values .= ",";
            }
        }
        $sql = <<<SQL
            INSERT INTO 
                t_menu_exercise 
                ( 
                    menu_id
                    , exercise_id
                    , exercise_no
                    , sets 
                    ) 
                    VALUES 
        SQL;
        $sql .= $values . ";";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':menu_id', $menuId);
        $stmt->execute();
    }

    // メニュー種目テーブル取得
    public function selectMenuExerciseByMenuId($dbh, $menuId)
    {
        $sql = <<<SQL
            SELECT 
                m_exercise.id as exercise_id
                , t_menu_exercise.exercise_no as exercise_no
                , m_exercise.name as exercise_name
                , t_menu_exercise.sets as sets 
            FROM 
                t_menu_exercise
            JOIN
                m_exercise ON t_menu_exercise.exercise_id = m_exercise.id
            WHERE 
                t_menu_exercise.menu_id = :menu_id 
            ORDER BY 
                t_menu_exercise.exercise_no ASC;
        SQL;
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':menu_id', $menuId);
        $stmt->execute();
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $response;
    }

    // メニュー種目テーブル削除
    public function deleteMenuExerciseByMenuId($dbh, $menuId)
    {
        $sql = <<<SQL
            DELETE FROM 
                t_menu_exercise 
            WHERE 
                t_menu_exercise.menu_id = :menu_id;
        SQL;

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':menu_id', $menuId);
        $stmt->execute();
    }

    // メニューマスタ更新
    public function updateMenu($dbh, $menuId, $menuName)
    {
        $sql = <<<SQL
                UPDATE 
                    m_menu 
                SET 
                    name = :menu_name
                WHERE 
                    id = :menu_id;
            SQL;
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':menu_name', $menuName);
        $stmt->bindParam(':menu_id', $menuId);
        $stmt->execute();
    }
}
