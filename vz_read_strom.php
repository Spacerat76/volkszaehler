<?php // program "vz_read_strom.php", 2014-05-09 RudolfReuter 
// Version Zaehler EMH eHZ
?>
<h3>Stromzaehler auslesen</h3>
<?php
if (isset($_GET['month']) && isset($_GET['year'])) {
    $month = $_GET['month'];
    $year = $_GET['year'];
}
else {
    $month = date("m");
    //echo "actual date \n";
    $year = date("Y");
}
?>
<form id="user_form" action="vz_read_strom.php" method="get">
    <fieldset>
        <select name="month">
            <option value="01">Januar</option>
            <option value="02">Februar</option>
            <option value="03">Maerz</option>
            <option value="04">April</option>
            <option value="05">Mai</option>
            <option value="06">Juni</option>
            <option value="07">Juli</option>
            <option value="08">August</option>
            <option value="09">September</option>
            <option value="10">Oktober</option>
            <option value="11">November</option>
            <option value="12">Dezember</option>
        </select>
        <select name="year">
            <option value="2019">2019</option>
            <option value="2020">2020</option>
            <option value="2021">2021</option>
            <option value="2022">2022</option>
            <option value="2023">2023</option>
            <option value="2024">2024</option>
            <option value="2025">2025</option>
            <option value="2026">2026</option>
            <option value="2027">2027</option>
            <option value="2028">2028</option>
        </select>
        <input type="submit" name="submit" value="submit">
    </fieldset>
</form>
<?php
    $str_from = "$year-$month-01 08:00:00";
    $date1 = new DateTime($str_from);
    //echo $date1->format('Y-m-d H:i:s') . "\n";
    $time_from = $date1->getTimestamp() * 1000;
    //echo $time_from . "\n";

    $str_to = "$year-$month-01 08:15:00";
    $date2 =  new DateTime($str_to);
    $time_to = $date2->getTimestamp() * 1000;
    //echo $time_to . "\n";

    $username="root";
    $password="raspberry";
    $channel_id="11";
    $str_sql = "SELECT value FROM data WHERE channel_id=$channel_id AND timestamp BETWEEN $time_from AND $time_to LIMIT 0, 1";
    //echo $str_sql . "\n";
    $db = mysql_connect('localhost', $username, $password);
    if (!$db) {
        die('Verbindung schlug fehl: ' . mysql_error());
    }
    $db_vz = mysql_select_db('volkszaehler', $db);
    $sql_res = mysql_query($str_sql);
    $row = mysql_fetch_assoc($sql_res);
    echo ($row["value"]/1000) . " KWh, ";
    echo "$year-$month-01 08:00 \n";
?>
