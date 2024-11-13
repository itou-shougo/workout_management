<?php

// トレーニング詳細登録画面モデル
class WorkoutDetailAddModel
{
    // メニュー種目テーブル取得
    public function selectMenuExerciseByMenuId($dbh, $menuId)
    {
        $sql = <<<SQL
                SELECT
                    t_menu_exercise.exercise_no as exercise_no
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

    // トレーニングテーブル取得
    public function selectWorkoutByDate($dbh, $workoutDate)
    {
        $sql = <<<SQL
                SELECT
                    t_workout.id as workout_id
                    , t_workout.menu_name as menu_name
                    , t_workout_exercise.exercise_no as exercise_no
                    , t_workout_exercise.exercise_name as exercise_name
                    , t_workout_exercise.set as set
                    , t_workout_exercise.weight as weight
                    , t_workout_exercise.reps as reps
                FROM 
                    t_workout
                JOIN
                    t_workout_exercise ON t_workout.id = t_workout_exercise.workout_id
                WHERE
                    t_workout.workout_date = :workout_date
                ORDER BY
                t_workout_exercise.exercise_no ASC;
        SQL;

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':workout_date', $workoutDate);
        $stmt->execute();
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $response;
    }

    // メニュー名称取得
    public function selectMenuNameById($dbh, $menuId)
    {
        $sql = <<<SQL
                    SELECT
                        m_menu.name
                    FROM 
                        m_menu
                    WHERE
                        m_menu.id = :menu_id
            SQL;

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':menu_id', $menuId);
        $stmt->execute();
        $response = $stmt->fetchAll(PDO::FETCH_COLUMN)[0];
        return $response;
    }

    // トレーニングテーブル登録
    public function insertWorkout($dbh, $workoutDate, $menuName)
    {
        $sql = <<<SQL
                INSERT INTO 
                    t_workout
                    ( 
                        workout_date
                        , menu_name
                    ) 
                    VALUES 
                    ( 
                        :workout_date
                        , :menu_name 
                    ) 
            SQL;
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':workout_date', $workoutDate);
        $stmt->bindParam(':menu_name', $menuName);
        $stmt->execute();
        $response = $dbh->lastInsertId();
        return $response;
    }

    // トレーニング種目テーブル登録
    public function insertWorkoutExercise($dbh, $workoutId, $workoutExerciseRegisterInfo)
    {
        $values = "";
        for ($i = 0; $i < count($workoutExerciseRegisterInfo); $i++) {
            $values .= "( :workout_id, ";
            $values .= $workoutExerciseRegisterInfo[$i]["exercise_no"] . ", ";
            $values .= "'" . $workoutExerciseRegisterInfo[$i]["exercise_name"] . "', ";
            $values .= $workoutExerciseRegisterInfo[$i]["set"] . ", ";
            $values .= $workoutExerciseRegisterInfo[$i]["weight"] . ", ";
            $values .= $workoutExerciseRegisterInfo[$i]["reps"] . ") ";
            if ($i < count($workoutExerciseRegisterInfo) - 1) {
                $values .= ",";
            }
        }
        $sql = <<<SQL
                INSERT INTO 
                    t_workout_exercise 
                    ( 
                        workout_id
                        , exercise_no
                        , exercise_name
                        , set
                        , weight
                        , reps
                        ) 
                        VALUES 
            SQL;
        $sql .= $values . ";";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':workout_id', $workoutId);
        $stmt->execute();
    }

    // トレーニング種目テーブル削除
    public function deleteWorkoutExerciseByWorkoutId($dbh, $workoutId)
    {
        $sql = <<<SQL
                DELETE FROM 
                    t_workout_exercise 
                WHERE 
                    t_workout_exercise.workout_id = :workout_id;
            SQL;

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':workout_id', $workoutId);
        $stmt->execute();
    }

    // トレーニングテーブル更新
    public function updateWorkout($dbh, $workoutId, $workoutDate, $menuName)
    {
        $sql = <<<SQL
                    UPDATE 
                        t_workout
                    SET 
                        workout_date = :workout_date
                        , menu_name = :menu_name
                    WHERE 
                        id = :workout_id;
                SQL;
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':workout_date', $workoutDate);
        $stmt->bindParam(':menu_name', $menuName);
        $stmt->bindParam(':workout_id', $workoutId);
        $stmt->execute();
    }

    // トレーニングテーブル削除
    public function deleteWorkoutById($dbh, $workoutId)
    {
        $sql = <<<SQL
                DELETE FROM 
                    t_workout 
                WHERE 
                    t_workout.id = :workout_id;
            SQL;

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':workout_id', $workoutId);
        $stmt->execute();
    }
}
