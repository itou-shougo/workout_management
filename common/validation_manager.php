<?php
// バリデーション管理クラス
class ValidationManager
{
    public $errMsg;

    // 未入力チェック
    public function isNotEmpty($targets)
    {
        $result = true;

        foreach ($targets as $target) {
            if (!$target && $target != 0) {
                $result = false;
                $this->errMsg = "未入力項目があります。";
                break;
            }
        }

        return $result;
    }

    // 重量チェック
    public function isValidWeight($targets)
    {
        $result = true;

        foreach ($targets as $target) {
            $targetFloat = (float)$target;
            if ($targetFloat < 0 || $targetFloat > 999.99) {
                $result = false;
                $this->errMsg = "重量が不正です。";
                break;
            }
        }

        return $result;
    }

    // 回数チェック
    public function isValidReps($targets)
    {
        $result = true;

        foreach ($targets as $target) {
            $targetInt = (int)$target;

            if ($targetInt < 0 || $targetInt > 99) {
                $result = false;
                $this->errMsg = "回数が不正です。";
                break;
            }
        }

        return $result;
    }
}
