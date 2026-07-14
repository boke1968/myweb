<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if($_SERVER['REQUEST_METHOD']==='OPTIONS'){exit(0);}

 $accion=$_GET['accion']??'';

if($accion==='leer'){
    $f='datos.json';
    echo file_exists($f)?file_get_contents($f):json_encode([
        'hero'=>['subtitle'=>'Desarrollo de Software con I.A','title'=>'Aplicaciones para Windows y Android','desc'=>'Creamos soluciones de software inteligentes y a medida, potenciadas por inteligencia artificial. De Málaga para el mundo.'],
        'philosophy'=>'No solo escribimos código. Diseñamos experiencias que transforman la forma en que trabajas. Cada proyecto es único, cada solución está pensada para durar, y cada línea de código lleva nuestra firma de calidad.',
        'apps'=>[]
    ]);
}
elseif($accion==='guardar'){
    file_put_contents('datos.json',file_get_contents('php://input'));
    echo json_encode(['ok'=>true]);
}
elseif($accion==='subir'){
    $dir='imagenes/';
    if(!is_dir($dir))mkdir($dir,0755,true);
    if(isset($_FILES['imagen'])&&$_FILES['imagen']['error']===0){
        $tipos=['image/jpeg','image/png','image/gif','image/webp'];
        if(!in_array($_FILES['imagen']['type'],$tipos)){echo json_encode(['error'=>'Tipo no válido']);exit;}
        if($_FILES['imagen']['size']>5*1024*1024){echo json_encode(['error'=>'Máximo 5MB']);exit;}
        $nombre=uniqid().'_'.preg_replace('/[^a-zA-Z0-9.\-]/','',basename($_FILES['imagen']['name']));
        if(move_uploaded_file($_FILES['imagen']['tmp_name'],$dir.$nombre)){
            echo json_encode(['url'=>$dir.$nombre]);
        }else{echo json_encode(['error'=>'Error al guardar']);}
    }else{echo json_encode(['error'=>'No se recibió imagen']);}
}
elseif($accion==='borrar'){
    $input=json_decode(file_get_contents('php://input'),true);
    $arc=$input['archivo']??'';
    if(strpos($arc,'imagenes/')===0&&file_exists($arc)){unlink($arc);}
    echo json_encode(['ok'=>true]);
}
else{echo json_encode(['error'=>'Acción no válida']);}
?>