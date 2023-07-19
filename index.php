<?php
//for long time execution
 ini_set('max_execution_time', 9999999999999999);
 $done = true; // <------------ kalo mau lihat tabel langsung di set true aja gais
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NLP</title>
</head>
<body>
    <form action="" method="post">
        <input type="date" name="startDate" required>
        <input type="date" name="endDate" required>
        <input type="submit" value="Learn Topics" name="submit">
    </form>
    <?php
        if(isset($_POST['submit'])) {        
            $startDate = str_replace("-", " ", $_POST['startDate']);
            $endDate = str_replace("-", " ", $_POST['endDate']);
            $output = shell_exec("python main.py ".$startDate .' '.$endDate." 2>&1");
            $output = explode(" ", $output);
            if($output[0] == 'success') {
                $time = intval($output[1])/60;
                $done = true;
                echo 'Waktu Proses = '.$time.' menit';
            }
        }
    ?>

    <br>
    <h3>Topik LDA</h3>
    <table border="1" id="lda">
    <tr>
        <th>Nama Topik</th>
        <th>Keyword</th>
    </tr>
    <?php   
        if($done) {
            $con = new mysqli("localhost","root","","project_uas_nlp");
            if ($con->connect_errno) { die("DATABASE ERROR"); }
            $sql = "SELECT * FROM lda";
            $res = $con->query($sql); 
            while($row = $res->fetch_assoc())
            {
                echo "<tr>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['keyword']."</td>";
                echo "</tr>";
            }
            $con->close();
        }
    ?>
    </table>

    <br>
    <h3>Topik LSA</h3>
    <table border="1" id="lda">
    <tr>
        <th>Nama Topik</th>
        <th>Keyword</th>
    </tr>
    <?php   
        if($done) {
            $con = new mysqli("localhost","root","","project_uas_nlp");
            if ($con->connect_errno) { die("DATABASE ERROR"); }
            $sql = "SELECT * FROM lda";
            $res = $con->query($sql); 
            while($row = $res->fetch_assoc())
            {
                echo "<tr>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['keyword']."</td>";
                echo "</tr>";
            }
            $con->close();
        }
    ?>
    </table>

    <br>
    <h3>Data Detik News</h3>
    <table border="1" id="lda">
    <tr>
        <th>Tanggal</th>
        <th>Judul</th>
        <th>Topik LDA</th>
        <th>Topik LSA</th>
    </tr>
    <?php   
        if($done) {
            $con = new mysqli("localhost","root","","project_uas_nlp");
            if ($con->connect_errno) { die("DATABASE ERROR"); }
            $sql = "SELECT data.tanggal, data.judul, lda.name as lda, lsa.name as lsa FROM data LEFT JOIN lda on data.lda_id = lda.id LEFT JOIN lsa on data.lsa_id = lsa.id";
            $res = $con->query($sql); 
            while($row = $res->fetch_assoc())
            {
                echo "<tr>";
                echo "<td>".$row['tanggal']."</td>";
                echo "<td>".$row['judul']."</td>";
                echo "<td>".$row['lda']."</td>";
                echo "<td>".$row['lsa']."</td>";
                echo "</tr>";
            }
            $con->close();
        }
    ?>
    </table>
</body>
</html>