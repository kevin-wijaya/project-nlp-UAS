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
    <title>NLP - Topic Modelling</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link rel="stylesheet" href="./src/style.bundle.css">
    <link rel="stylesheet" href="./src/plugins.bundle.css">
    <link rel="stylesheet" href="./src/datatables.bundle.css">
</head>

<body>
    <div class="container">

        <h1 align="center" class="mt-5">Topic Modelling with LSA and LDA</h1>
        <h3 align="center">Using data from DetikNews</h3>

        <form action="" method="post">
            <div class="card p-5 shadow-sm mt-5">
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <div>
                            <label class="required form-label">Start Date</label>
                            <input type="date" name="startDate" class="form-control form-control-solid" required>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div>
                            <label class="required form-label">End Date</label>
                            <input type="date" name="endDate" class="form-control form-control-solid" required>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <input type="submit" value="Learn Topics" name="submit" class="btn btn-info hover-elevate-down my-5">
                </div>
            </div>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            $startDate = str_replace("-", " ", $_POST['startDate']);
            $endDate = str_replace("-", " ", $_POST['endDate']);
            $output = shell_exec("python main.py " . $startDate . ' ' . $endDate . " 2>&1");
            $output = explode(" ", $output);
            if ($output[0] == 'success') {
                $time = intval($output[1]) / 60;
                $done = true;
                echo '<div class="alert alert-dismissible bg-primary d-flex flex-column flex-sm-row p-5 mb-10">' .
                    '<div class="d-flex flex-column text-light pe-0 pe-sm-10">' .
                    '<h4 class="mb-2 light">Run Successful!</h4>' .
                    '<span>Run took ' . $time . ' minutes</span>' .
                    '</div>' .
                    '<button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">' .
                    'X' .
                    '</button>' .
                    '</div>';
            }
        }
        ?>

        <br>

        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Insight</h3>
            </div>
            <div class="card-body d-flex justify-content-center">
                <img src="./img/top-word.jpg" alt="" class="w-75 p-5">
            </div>
        </div>

        <br>
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Topik LDA</h3>
            </div>

            <div class="card-body">
                <table id="lda" class="table table-row-bordered gy-5">
                    <tr>
                        <th>Nama Topik</th>
                        <th>Keyword</th>
                        <th>Edit Nama Topik</th>
                    </tr>
                    <?php
                    if ($done) {
                        $con = new mysqli("localhost", "root", "", "project_uas_nlp");
                        if ($con->connect_errno) {
                            die("DATABASE ERROR");
                        }
                        $sql = "SELECT * FROM lda";
                        $res = $con->query($sql);
                        while ($row = $res->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['keyword'] . "</td>";
                            echo '<td><button type="button" class="btn btn-primary btn-lda" lda-idx='.$row['id'].
                                ' data-bs-toggle="modal" data-bs-target="#kt_modal_1">' .
                                'Edit' .
                                '</button>' .
                                '</td>';
                            echo "</tr>";
                        }
                        $con->close();
                    }
                    ?>
                </table>
            </div>
        </div>
        <br>
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Topik LSA</h3>
            </div>

            <div class="card-body">
                <table id="lsa" class="table table-row-bordered gy-5">
                    <tr>
                        <th>Nama Topik</th>
                        <th>Keyword</th>
                        <th>Edit Nama Topik</th>
                    </tr>
                    <?php
                    if ($done) {
                        $con = new mysqli("localhost", "root", "", "project_uas_nlp");
                        if ($con->connect_errno) {
                            die("DATABASE ERROR");
                        }
                        $sql = "SELECT * FROM lsa";
                        $res = $con->query($sql);
                        while ($row = $res->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['keyword'] . "</td>";
                            echo '<td><button type="button" class="btn btn-primary btn-lsa" lsa-idx='.$row['id'].
                                ' data-bs-toggle="modal" data-bs-target="#kt_modal_1">' .
                                'Edit' .
                                '</button>' .
                                '</td>';
                            echo "</tr>";
                        }
                        $con->close();
                    }
                    ?>
                </table>
            </div>
        </div>
        <br>
        <div class="card shadow-sm mb-5">
            <div class="card-header">
                <h3 class="card-title">Data Detik News</h3>
            </div>

            <div class="card-body">
                <!-- <h3>Data Detik News</h3> -->
                <table id="data" class="table table-row-bordered table-striped gy-5">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Judul</th>
                            <th>Topik LDA</th>
                            <th>Topik LSA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($done) {
                            $con = new mysqli("localhost", "root", "", "project_uas_nlp");
                            if ($con->connect_errno) {
                                die("DATABASE ERROR");
                            }
                            $sql = "SELECT data.tanggal, data.judul, lda.name as lda, lsa.name as lsa FROM data LEFT JOIN lda on data.lda_id = lda.id LEFT JOIN lsa on data.lsa_id = lsa.id";
                            $res = $con->query($sql);
                            while ($row = $res->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['tanggal'] . "</td>";
                                echo "<td>" . $row['judul'] . "</td>";
                                echo "<td>" . $row['lda'] . "</td>";
                                echo "<td>" . $row['lsa'] . "</td>";
                                echo "</tr>";
                            }
                            $con->close();
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="kt_modal_1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="edit.php" method="post">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Topic Name</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        X
                    </div>
                </div>

                <div class="modal-body">
                    <input type="text" name="lsaName" class="form-control form-control-solid">
                    <p></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Save Changes">
                </div>
                </form>
            </div>
        </div>
    </div>

    <script src="./src/plugins.bundle.js"></script>
    <script src="./src/scripts.bundle.js"></script>
    <script src="./src/datatables.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $("#data").DataTable();

            $(".btn-lda").on('click', function() {
                var nama = $(this).closest('tr').find('td:nth-child(1)').text();
                var keyword = $(this).closest('tr').find('td:nth-child(2)').text();
                var id = $(this).attr('lda-idx');

                $(".modal-body").html(
                    '<input type="text" name="edit_lda" class="form-control form-control-solid" placeholder="Nama Topik LDA" value="' + nama + '">'+
                    '<input type="hidden" name="id" value="' +id + '">'+
                    '<p class="mt-5">Keywords: ' + keyword +'</p>'
                );
            });

            $(".btn-lsa").on('click', function() {
                var nama = $(this).closest('tr').find('td:nth-child(1)').text();
                var keyword = $(this).closest('tr').find('td:nth-child(2)').text();
                var id = $(this).attr('lsa-idx');

                $(".modal-body").html(
                    '<input type="text" name="edit_lsa" class="form-control form-control-solid" placeholder="Nama Topik LSA" value="' + nama + '">'+
                    '<input type="hidden" name="id" value="' +id + '">'+
                    '<p class="mt-5">Keywords: ' + keyword +'</p>'
                );
            });
        });
    </script>
</body>
</html>