<?php


function imagePost($pathImage,$file){
    
    if (!file_exists($pathImage)) {
        mkdir($pathImage, 0777, true);
        $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
        $file->move($pathImage.'/', $fileName);
        $fileName = $pathImage.'/' . $fileName;
        $name =  $fileName;
    } else {
        $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
        $file->move($pathImage.'/', $fileName);
        $fileName = $pathImage.'/' . $fileName;
        $name =  $fileName;
    }
    return $name;
}