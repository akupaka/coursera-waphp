<!DOCTYPE html>
<html>
    <head>
        <title>MD5 PIN cracking game by Roman Kozar</title>
    </head>
    <body>
        <h1>Welcome to MD5 PIN cracking game by Roman Kozar!</h1>
        <?php
        $url_md5 = filter_input(INPUT_GET, 'md5', FILTER_SANITIZE_STRING);
        $url_dic = filter_input(INPUT_GET, 'dic', FILTER_SANITIZE_STRING);
        $url_min = filter_input(INPUT_GET, 'min', FILTER_SANITIZE_NUMBER_INT);
        $url_max = filter_input(INPUT_GET, 'max', FILTER_SANITIZE_NUMBER_INT);
        ?>
        <form method="GET">
            <p><label>MD5: <input type="text" name="md5" size="40" value="<?php echo $url_md5 ? $url_md5 : 'b6fb522815d06fed82b0140be4c74680' ?>"/></label></p>
            <p><label>Dictionary: <input type="text" name="dic" size="40" value="<?php echo $url_dic ? $url_dic : '0123456789' ?>" /></label></p>
            <p><label>Min: <input type="text" name="min" size="40" value="<?php echo $url_min ? $url_min : '4' ?>" /></label></p>
            <p><label>Max: <input type="text" name="max" size="40" value="<?php echo $url_max ? $url_max : '4' ?>" /></label></p>
            <p>
                <input type="submit" />
                <input type="reset" onclick="location.href = 'index.php'; return false;" />
            </p>
        </form>
        <?php
        if ($url_md5) {
            //echo '<strong>Output log</strong><br/>';
            $dictionary = $url_dic ? str_split($url_dic) : str_split("0123456789");
            $min_length = $url_min ? (int) $url_min : 4;
            $max_length = $url_max ? (int) $url_max : 4;

            // Brute-forcing
            $time_pre = microtime(true);
            $pin = "";
            for ($pin_length = $min_length; $pin_length <= $max_length; $pin_length++) {
                // Initializing indexes
                init_indexes($indexes, $pin_length);
                $pin = cycle_dictionary($dictionary, $indexes, 0, $url_md5);
                if ($pin) break;
            }
            if ($pin) {
                echo "<p><strong>PIN: $pin </strong></p>";
            } else {
                echo "<p><strong>PIN: not found </strong></p>";
            }
            $time_elapsed = microtime(true) - $time_pre;
            echo "<p>Elapsed time: $time_elapsed</p>";
        } else if ($url_md5 !== NULL) {
            echo '<strong>Error: input parameters are incorrect</strong><br/>';
        }

        function init_indexes(&$indexes, $length) {
            $indexes = [];
            for ($i = 0; $i < $length; $i++) {
                $indexes[$i] = 0;
            }
        }

        function cycle_dictionary($dictionary, &$indexes, $ind, $md5) {
            for ($di = 0; $di < count($dictionary); $di++) {
                //echo "di: $di , ind: $ind <br/>";
                $indexes[$ind] = $di;
                //return;
                if ($ind < count($indexes) - 1) {
                    $pin = cycle_dictionary($dictionary, $indexes, $ind + 1, $md5);
                    if ($pin != "") return $pin;
                } else {
                    $pin = get_pin($dictionary, $indexes);
                    if (hash('md5', $pin) == $md5) return $pin;
                }
            }
        }

        function get_pin($dictionary, $indexes) {
            $pin = '';
            for ($i = 0; $i < count($indexes); $i++) {
                $pin .= $dictionary[$indexes[$i]];
            }
            return $pin;
        }
        ?>
    </body>
</html>
