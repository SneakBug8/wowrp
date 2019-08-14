<?php
    final class edit extends AbstractPicoPlugin
    {
        public function onRequestUrl(&$url)
        {
            if ($url != "edit") {
                return;
            } ?>
            <head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit</title>
</head>
            Файл: <?php echo $_GET["file"]; ?>
            <?php

            $filepath = __DIR__  . "/../../content/" . $_GET["file"] . ".md";

            if (isset($_POST['text']) && $_POST['password'] == "1122") {
                file_put_contents($filepath, $_POST['text']);
                $this->logmsg("Edited " . $_GET["file"]);

                if ($_POST['text'] == "") {
                    unlink($filepath); ?>
                    <b>Файл удален</b>
                    <?php
                } else {
                    ?>
                    <b>Успешно сохранено</b>
                    <?php
                }
            } elseif (isset($_POST['password']) && $_POST['password'] !== "1122") {
                ?>
                <b>Неверный пароль</b>
                <?php
            }
            if (isset($_POST['text'])) {
                $text = $_POST['text'];
            } elseif (file_exists($filepath)) {
                $text = file_get_contents($filepath);
            } else {
                $text = "";
            } ?>
            <style>
                form, textarea {
                    width: 100%;
                }
            </style>
                <form action="" method="post">
                    <textarea name="text" rows="20"><?php echo htmlspecialchars($text) ?></textarea>
                    <br>
                    Пароль: <input type="password" name="password" />
                    <br>
                    <input type="submit" value="Сохранить"/>
                    <input type="reset" />
                    <a href="/<?php echo $_GET["file"] ?>">Смотреть страницу</a>
                </form>
            <?php

            exit();
        }

        public function logmsg($message)
        {
            $filename = $this->getPico()->getConfig('content_dir') . "edits/log" . date("d-m-Y") . ".md";

            if (file_exists($filename)) {
                $logs = file_get_contents($filename);
            } else {
                $logs = "/*\nTitle: Логи от " . date('d-m-Y') . "\n*/";
            }

            $logs .= "\n\n[" . date("H:i") . "] " . $message;
            file_put_contents($filename, $logs);
        }
    }
?>
