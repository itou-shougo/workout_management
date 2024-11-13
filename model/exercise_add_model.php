<?php

// 種目登録画面モデル
class ExerciseAddModel
{
    // 種目区分マスタ取得
    public function selectAllExerciseType($dbh)
    {
        $sql = <<<SQL
            SELECT 
                m_exercise_type.id
                , m_exercise_type.name 
            FROM 
                m_exercise_type;
        SQL;
        $res = $dbh->query($sql);
        $response = $res->fetchAll(PDO::FETCH_ASSOC);
        return $response;
    }

    // 対象筋マスタ取得
    public function selectAllTarget($dbh)
    {
        $sql = <<<SQL
            SELECT 
                m_target.id
                , m_target.name
            FROM
                m_target;
        SQL;
        $res = $dbh->query($sql);
        $response = $res->fetchAll(PDO::FETCH_ASSOC);
        return $response;
    }

    // 種目マスタ取得
    public function selectExerciseById($dbh, $exerciseId)
    {
        $sql = <<<SQL
                SELECT 
                    m_exercise.id as exercise_id
                    , m_exercise.name as exercise_name
                    , m_exercise.exercise_type_id as exercise_type_id 
                    , m_exercise.target_id as target_id 
                FROM 
                    m_exercise
                WHERE 
                    m_exercise.id = :exercise_id 
                ORDER BY 
                    m_exercise.id ASC;
            SQL;
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':exercise_id', $exerciseId);
        $stmt->execute();
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
        return $response;
    }

    // 種目マスタ登録
    public function insertExercise($dbh, $exerciseInfo)
    {
        $sql = <<<SQL
            INSERT INTO 
                m_exercise 
                ( 
                    name
                    , exercise_type_id
                    , target_id 
                ) 
            VALUES 
                ( 
                    :exercise_name
                    , :exercise_type_id
                    , :target_id
                );
        SQL;
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':exercise_name', $exerciseInfo["exercise_name"]);
        $stmt->bindParam(':exercise_type_id', $exerciseInfo["exercise_type_id"]);
        $stmt->bindParam(':target_id', $exerciseInfo["target_id"]);
        $stmt->execute();
    }

    // 種目マスタ更新
    public function updateExercise($dbh, $exerciseInfo)
    {
        $sql = <<<SQL
            UPDATE 
                m_exercise 
            SET 
                name = :exercise_name
                , exercise_type_id = :exercise_type_id
                , target_id = :target_id 
            WHERE 
                id = :exercise_id;
        SQL;
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':exercise_id', $exerciseInfo["exercise_id"]);
        $stmt->bindParam(':exercise_name', $exerciseInfo["exercise_name"]);
        $stmt->bindParam(':exercise_type_id', $exerciseInfo["exercise_type_id"]);
        $stmt->bindParam(':target_id', $exerciseInfo["target_id"]);
        $stmt->execute();
    }
}
