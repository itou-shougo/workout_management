<?php

// ホーム画面モデル
class HomeModel
{
    // トレーニングテーブル取得
    public function selectAllWorkout($dbh)
    {
        $sql = <<<SQL
                    SELECT
                        t_workout.id
                        , t_workout.workout_date
                    FROM 
                        t_workout
            SQL;

        $res = $dbh->query($sql);
        $response = $res->fetchAll(PDO::FETCH_ASSOC);
        return $response;
    }
}
