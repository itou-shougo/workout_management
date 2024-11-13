<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>ホーム画面</title>
  <link href="css/share.css" rel="stylesheet" />
</head>

<body>
  <?php
  require_once('../controller/home_controller.php');
  $controller = new HomeController();
  $controller->load();
  ?>
  <div id="calendar">
    <div class="calendar-upper-wrap">
      <input type="button" onclick="prevMonth()" value="<">
      <h1 id="calendarYearAndMonth"></h1>
      <input type="button" onclick="nextMonth()" value=">">
    </div>
    <div class="calendar-wrap">
      <table id="calendarTbl">
        <!-- カレンダーはJavaScriptで作成 -->
      </table>
    </div>
  </div>
  <div class="home-btn-wrap">
    <input type="button" onclick="location.href='./exercise_management.php'" value="種目を登録">
    <input type="button" onclick="location.href='./menu_management.php'" value="メニューを登録">
    <!-- 本日のトレーニングが記録されているかどうかで遷移先を変える -->
    <?php if ($controller->isWorkoutToday) { ?>
      <form method="POST" action="./workout_detail_add.php">
        <input type="submit" value="本日のトレーニングを登録">
        <input type="hidden" name="workout_date" value="<?= date("Y-m-d") ?>">
      </form>
    <?php } else { ?>
      <form method="POST" action="./workout_add.php">
        <input type="submit" value="本日のトレーニングを登録">
        <input type="hidden" name="workout_date" value="<?= date("Y-m-d") ?>">
      </form>
    <?php } ?>
  </div>

  <script>
    window.addEventListener('DOMContentLoaded', function() {
      const calendarYearAndMonth = document.getElementById("calendarYearAndMonth");
      const today = new Date();
      const currentYear = today.getFullYear();
      const currentMonth = today.getMonth() + 1;

      calendarYearAndMonth.innerHTML = currentYear + "/" + currentMonth;
      createCalendar(currentYear, currentMonth);
    });

    // カレンダー作成
    function createCalendar(year, month) {
      const calendarTbl = document.getElementById("calendarTbl");
      calendarTbl.innerHTML = "";

      // 曜日作成
      const weeks = ["日", "月", "火", "水", "木", "金", "土"];
      let weeksTr = document.createElement("tr");
      for (let i = 0; i < weeks.length; i++) {
        let weeksTh = document.createElement("th");
        weeksTh.innerHTML = weeks[i];
        weeksTr.appendChild(weeksTh);
      }
      calendarTbl.appendChild(weeksTr);

      // 日付作成
      const startDate = new Date(year, month - 1, 1);
      const endDate = new Date(year, month, 0);
      const startDay = startDate.getDay();
      const endDayCount = endDate.getDate();

      // カレンダー作成
      let dayCount = 1;
      for (let i = 0; i < 6; i++) {
        let dayTr = document.createElement("tr");
        for (let j = 0; j < weeks.length; j++) {
          let dayTd = document.createElement("td");
          if (!(i == 0 && j < startDay || dayCount > endDayCount)) {
            // データベースとの比較のためにフォーマット
            let formattedMonth = String(month);
            let formattedDay = String(dayCount);

            if (formattedMonth.length == 1) {
              formattedMonth = "0" + month;
            }

            if (formattedDay.length == 1) {
              formattedDay = "0" + dayCount;
            }

            let date = year + "-" + formattedMonth + "-" + formattedDay;
            dayTd.classList.add("calendar-td");
            dayTd.dataset.date = date;

            // トレーニングが記録されている日付にクラス付与
            let workoutInfo = JSON.parse('<?= json_encode($controller->workoutInfo) ?>');
            if (workoutInfo.length > 0) {
              for (let k = 0; k < workoutInfo.length; k++) {
                if (workoutInfo[k]["workout_date"] == date) {
                  dayTd.innerHTML = '<form method="POST" action="./workout_detail_add.php"><input type="submit" value="' + dayCount + '"><input type="hidden" name="workout_date" value="' + date + '"></form>'
                  dayTd.classList.add("trained");
                  break;
                } else {
                  dayTd.innerHTML = '<form method="POST" action="./workout_add.php"><input type="submit" value="' + dayCount + '"><input type="hidden" name="workout_date" value="' + date + '"></form>'
                }
              }
            } else {
              dayTd.innerHTML = '<form method="POST" action="./workout_add.php"><input type="submit" value="' + dayCount + '"><input type="hidden" name="workout_date" value="' + date + '"></form>'
            }

            // 本日日付にクラス付与(sv-SEロケールはYYYY-MM-DD形式の日付文字列)
            if (date == new Date().toLocaleDateString('sv-SE')) {
              dayTd.classList.add("today");
            }

            dayCount++;
          } else {
            dayTd.classList.add("not-this-month");
          }
          dayTr.appendChild(dayTd);
        }
        calendarTbl.appendChild(dayTr);
      }
    }

    // 次月押下時
    function nextMonth() {
      const calendarYearAndMonth = document.getElementById("calendarYearAndMonth");
      let year = parseInt(calendarYearAndMonth.innerHTML.split("/")[0]);
      let month = parseInt(calendarYearAndMonth.innerHTML.split("/")[1]);

      if (month == 12) {
        year++;
        month = 1;
      } else {
        month++;
      }

      calendarYearAndMonth.innerHTML = year + "/" + month;
      createCalendar(year, month);
    }

    // 前月押下時
    function prevMonth() {
      const calendarYearAndMonth = document.getElementById("calendarYearAndMonth");
      let year = parseInt(calendarYearAndMonth.innerHTML.split("/")[0]);
      let month = parseInt(calendarYearAndMonth.innerHTML.split("/")[1]);

      if (month == 1) {
        year--;
        month = 12;
      } else {
        month--;
      }

      calendarYearAndMonth.innerHTML = year + "/" + month;
      createCalendar(year, month);
    }
  </script>
</body>

</html>