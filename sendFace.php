<?php

include('connection.php');

$base64_string = $_POST['image'];
$username = $_POST['username'];
$password = $_POST['password'];
$sql = "select username, password from user where username ='".$username."'and password ='".$password."'";
$query = mysqli_query($conn, $sql);
$cek = mysqli_num_rows($query);
// var_dump($query);
if($cek > 0){

    $image_name = "C:\\xampp\\htdocs\\uploadFace\\uploadFace\\".$username;

    if (!file_exists($image_name)) {
        if (!mkdir($image_name)) {
            $m=array('msg' => "REJECTED, cant create folder");
            echo json_encode($m);
            return;}
    }

    $fi = new FilesystemIterator($image_name, FilesystemIterator::SKIP_DOTS);
    $fileCount = iterator_count($fi)+1;
    $data = explode(',', $base64_string);
    $fullName = $image_name."\\X__".$fileCount."_". date("YmdHis") .".png";
    $ifp = fopen($fullName, "wb");
    fwrite($ifp, base64_decode($data[1]));
    fclose($ifp);
    if (!$ifp){
        $m=array('msg' => "REJECTED, ".$fullName."not saved");
        echo json_encode($m);
        return;}

    // $command = escapeshellcmd("python checkFace.py ".$fullName);
    // $output = shell_exec($command);

    $fi = new FilesystemIterator($image_name, FilesystemIterator::SKIP_DOTS);
    $fileCount = iterator_count($fi);
    $m = array('msg' => "Berhasil Mengirim"." total(".$fileCount.")");
    echo json_encode($m);

    $sql = "insert into log (username) values('$username')";
    $query = mysqli_query($conn, $sql);
}
else {
    echo "Username atau Password salah, silahkan mencoba kembali.";
}
?>
