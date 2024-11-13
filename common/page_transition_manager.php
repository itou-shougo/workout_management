<?php
// 画面遷移管理クラス
class PageTransitionManager
{
    // URL手打ちによる直アクセスを許可しない画面リスト
    public $notAllowdDirectTransitionPageList = [
        "workout_add.php",
        "workout_detail_add.php"
    ];

    // URL手打ちによる画面遷移チェック
    public function isAllowedDirectTransition()
    {
        $result = true;

        foreach ($this->notAllowdDirectTransitionPageList as $page) {
            if (strpos($_SERVER['REQUEST_URI'], $page)) {
                // URL手打ちによる直アクセスを許可しない画面の場合、リファラチェックを行う
                $referer = @$_SERVER["HTTP_REFERER"];

                if (empty($referer)) {
                    echo "遷移が正しく行われませんでした。";
                    exit;
                }
            }
        }
        return $result;
    }
}
