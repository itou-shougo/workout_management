<?php

// 種目管理画面モデル
class ExerciseManagementModel
{
    // 種目マスタ取得
    public function selectAllExercise($dbh)
    {
        $sql = <<<SQL
            SELECT
                m_exercise.id as exercise_id
                , m_exercise.name as exercise_name
                , m_exercise_type.name as exercise_type_name
                , m_target.name as target_name
            FROM 
                m_exercise 
            JOIN 
                m_exercise_type ON m_exercise.exercise_type_id = m_exercise_type.id
            JOIN 
                m_target ON m_exercise.target_id = m_target.id
            ORDER BY 
                m_exercise.id ASC;
        SQL;

        $res = $dbh->query($sql);
        $response = $res->fetchAll(PDO::FETCH_ASSOC);
        return $response;
    }

    // 種目マスタ削除
    public function deleteExerciseById($dbh, $exerciseId)
    {
        $sql = <<<SQL
            DELETE FROM 
                m_exercise 
            WHERE 
                m_exercise.id = :exercise_id;
        SQL;

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':exercise_id', $exerciseId);
        $result = $stmt->execute();
        return $result;
    }

    // メニュー種目テーブル取得
    public function selectMenuExerciseByExerciseId($dbh, $exerciseId)
    {
        $sql = <<<SQL
            SELECT 
                t_menu_exercise.id
            FROM 
                t_menu_exercise 
            WHERE 
                t_menu_exercise.exercise_id = :exercise_id;
        SQL;
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':exercise_id', $exerciseId);
        $stmt->execute();
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $response;
    }
}
