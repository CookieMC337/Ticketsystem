<?php
if (!file_exists("mysql.php")) {
    header("Location: setup/index.php");
    exit;
}
session_start();
require("datamanager.php");
require('assets/languages/lang_' . getSetting("lang") . '.php');
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
} else if (getAccountRank($_SESSION["username"]) < 1) {
    header("Location: mytickets.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title><?php echo OPEN_TICKETS; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
          integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
</head>
<body>
<div class="flex">
    <div class="flex-item">
        <?php
        function shorter($text, $chars_limit)
        {
            if (strlen($text) > $chars_limit) {
                $new_text = substr($text, 0, $chars_limit);
                $new_text = trim($new_text);
                return $new_text . "...";
            } else {
                return $text;
            }
        }

        ?>
        <h1><?php echo OPEN_TICKETS; ?></h1>
        <?php
        require("mysql.php");
        $stmt = $mysql->prepare("SELECT * FROM tickets WHERE STATUS = 0 ORDER BY CREATIONDATE DESC");
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count != 0) {
            ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th><?php echo TITLE; ?></th>
                    <th><?php echo CATEGORY; ?></th>
                    <th><?php echo CREATED_AT; ?></th>
                    <th><?php echo LAST_ANSWER_AT; ?></th>
                    <th>Status</th>
                    <th><?php echo ACTION; ?></th>
                </tr>
                <?php
                while ($row = $stmt->fetch()) {
                    ?>
                    <tr>
                        <td><?php echo $row["ID"]; ?></td>
                        <td><?php echo shorter($row["TITLE"], 25); ?></td>
                        <td><?php echo getCategory($row["CATEGORY"]); ?></td>
                        <td><?php echo displayTimestamp($row["CREATIONDATE"]); ?></td>
                        <td><?php
                            if ($row["LASTANSWERDATE"] == null) {
                                echo NONE;
                            } else {
                                echo displayTimestamp($row["LASTANSWERDATE"]);
                            }
                            ?></td>
                        <td><?php
                            if ($row["STATUS"] == 0) {
                                echo OPEN;
                            }
                            if ($row["STATUS"] == 1) {
                                echo CLOSED;
                            }
                            ?></td>
                        <td><a href="ticket.php?id=<?php echo $row["ID"]; ?>" class="btn"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
        } else {
            ?>
            <p><?php echo NO_OPEN; ?></p><br>
            <?php
        }
        ?>
    </div>
    <div class="flex-item sidebar">
        <?php require('assets/inc/sidebar.inc.php'); ?>
    </div>
</div>
</body>
</html>
