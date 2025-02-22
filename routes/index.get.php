<?php
if ($_GET['dadan'] == "gelo") {
    $data = ["dadan" => "GELO"];
} else {
    $data = ["dadan" => "TIDAK GELO"];
}
$data['title'] = "aktif";


//render view
view('home.index',$data);