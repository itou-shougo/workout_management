<?php

// トレーニング登録画面モデル
class WorkoutAddModel
{
    // メニューマスタ取得
    public function selectAllMenu($dbh)
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
}
