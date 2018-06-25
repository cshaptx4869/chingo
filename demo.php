<?php
    header('content-type:text/html;charset=utf8');
    require_once 'Page.classs.php';
    //echo '<link href="page.css" rel="stylesheet">';
    try{
        $pdo = new PDO('mysql:host=localhost;dbname=labexam','root','123456');
    }catch (\Exception $e){
        die ('pdo connect error');
    }
    $pdo->exec('set names utf8');
    $sql = 'select tihao from exam_shiti';
    $state = $pdo->query($sql);
    $data1 = $state->fetchAll(PDO::FETCH_ASSOC);
    $count = count($data1);

    $page = isset($_GET['page'])?$_GET['page']:1;
    $pageSize = 10;
    $offset = ($page-1)*$pageSize;
    $sql = "select tihao,tikubh,CONCAT(SUBSTRING(tigan,1,30),'...') as tigan,ctime from exam_shiti limit $offset,$pageSize";
    $state = $pdo->query($sql);
    $data = $state->fetchAll(PDO::FETCH_ASSOC);

    $p = new Page($count,$pageSize,5);
    //$p->startStr = 'start';
    //$p->endStr = 'end';
    //$p->lastPageStr= ' < ';
    //$p->nextPageStr= ' > ';
    $p->setOtherKey(['cmd'=>'exam_list','flag'=>1]);
    //$p->endNeed = false;
    //$p->startNeed = false;
    //$p->numShow = false;
    $links = $p->links();

    ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <caption>
            题库表
        </caption>
        <tr>
            <th>题号</th>
            <th>题库编号</th>
            <th>题干</th>
            <th>创建时间</th>
        </tr>
        <?php
            foreach ($data as $k=>$v) {
                echo '<tr>';
                echo '<td width="10%">' . $v['tihao'] . '</td>';
                echo '<td width="10%">' . $v['tikubh'] . '</td>';
                echo '<td width="60%">' . $v['tigan'] . '</td>';
                echo '<td width="20%">' . $v['ctime'] . '</td>';
                echo '</tr>';
            }
            echo '<tr><td colspan="4" align="center">'.$links.'</td></tr>'
        ?>
    </table>
</body>
</html>
