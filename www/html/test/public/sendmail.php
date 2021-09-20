<?php

mb_language("Japanese");
mb_internal_encoding("UTF-8");

if (mb_send_mail("example@example.com", "テストメール", "これはテストです。", "From: from@example.com")) {
  echo "メールが送信されました。";
} else {
  echo "メールの送信に失敗しました。";
}
?>
<a href="http://test.localhost:1080">表示</a>