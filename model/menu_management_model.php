<?php

// メニュー管理画面モデル
class MenuManagementModel
{
    // メニューマスタ取得
    function selectAllMenu($dbh)
    {
        $sql = <<<SQL
            SELECT 
                m_menu.id
                , m_menu.name
            FROM
                m_menu 
            ORDER BY 
                m_menu.id ASC;

        SQL;
        $res = $dbh->query($sql);
        $response = $res->fetchAll(PDO::FETCH_ASSOC);
        return $response;
    }

    // メニューマスタ削除
    public function deleteMenuById($dbh, $menuId)
    {
        $sql = <<<SQL
            DELETE FROM 
                m_menu 
            WHERE 
                m_menu.id = :menu_id;
        SQL;

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':menu_id', $menuId);
        $stmt->execute();
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
}
