<?php

//files upload
if(!empty($_FILES['inputUpload'])){
    $files = $this->reArrayFiles($_FILES['inputUpload']);
    $errors = [];

    $fileExtensions = ['jpeg','jpg','png'];
    $uploadDir = '../uploads/';

    foreach ($files as $file) {
        $ext_arr = explode(".", $file['name']);
        $extr = end($ext_arr);
        if (!in_array($extr, $fileExtensions)) {
            $errors[] = [$file['name'] => 'this file # type of image'];
            continue;
        }
        if($file['size'] > 1000000){
            $errors[] = [$file['name'] => 'this file to large 1000000'];
            continue;
        }
        $uniqer = substr(md5(uniqid(rand(),1)),0,5);
        $file_name = $uniqer . '_' . $file['name'];

        $uploadPath = $uploadDir . $booking['id'] . '/' . USER_ID . '/';
        FileHelper::createDirectory($uploadPath);
        //     @mkdir($uploadPath, 0777);
        if(!move_uploaded_file($file['tmp_name'], $uploadPath . $file_name))
        {
            $errors[] = [$file['name'] => 'this file upload was error'];
        }
    }
}
function reArrayFiles(&$file_post) {

    $file_ary = [];
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }
    return $file_ary;
}
?>