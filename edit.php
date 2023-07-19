<?php

if (!(isset($_POST['edit_lsa']) || isset($_POST['edit_lda']))) {
    die();
    header('location: index.php');
}
print_r($_POST);
$con = new mysqli("localhost", "root", "", "project_uas_nlp");
if ($con->connect_errno) {
    die("DATABASE ERROR");
}

if (isset($_POST['edit_lsa'])) {
    $name = $_POST['edit_lsa'];
    $id = $_POST['id'];
    $sql = "UPDATE lsa SET name='$name' WHERE id=$id";
} else if (isset(($_POST['edit_lda']))) {
    $name = $_POST['edit_lda'];
    $id = $_POST['id'];
    $sql = "UPDATE lda SET name='$name' WHERE id=$id";
}

// $con->query($sql);

if ($con->query($sql) === TRUE) {
    echo "Record updated successfully";
  } else {
    echo "Error updating record: " . $con->error;
  }
$con->close();

header('location: index.php');
?>