<?php
	//响应头信息,之前不能有输出
	header ('Content-type: application/vnd.ms-excel');
	header ('Content-Disposition:attachment; filename=活动名单.xls');
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-type" content="text/html;charset=gbk" />
</head>
<body>
	<table border=1 cellpadding=0 cellspacing=0 width=100% style="border-collapse: collapse">
	<?php
		echo '<tr>';
			/*<meta 中设置了charset为 gbk> 所以要转码*/
			echo '<td align="left"><b>'.iconv ( 'utf-8', 'gbk', '活动名称').'</b></td>';
			echo '<td align="left"><b>'.iconv ( 'utf-8', 'gbk', '工号').'</b></td>';
			echo '<td align="left"><b>'.iconv ( 'utf-8', 'gbk', '姓名').'</b></td>';
			echo '<td align="left"><b>'.iconv ( 'utf-8', 'gbk', '性别').'</b></td>';
			echo '<td align="left"><b>'.iconv ( 'utf-8', 'gbk', '单位').'</b></td>';
			echo '<td align="left"><b>'.iconv ( 'utf-8', 'gbk', '职称').'</b></td>';
			echo '<td align="left"><b>'.iconv ( 'utf-8', 'gbk', '手机').'</b></td>';
			echo '<td align="left"><b>'.iconv ( 'utf-8', 'gbk', '邮箱').'</b></td>';
			echo '<td align="left"><b>'.iconv ( 'utf-8', 'gbk', '出生年月').'</b></td>';
			echo '<td align="left"><b>'.iconv ( 'utf-8', 'gbk', '入校时间').'</b></td>';
			echo '<td align="left"><b>'.iconv ( 'utf-8', 'gbk', '学校').'</b></td>';
			echo '<td align="left"><b>'.iconv ( 'utf-8', 'gbk', '院系').'</b></td>';
		echo '</tr>';


		echo '<tr>';
			echo '<td align="left">' . iconv ( 'utf-8', 'gbk', '1') . '</td>';
			echo '<td align="left">' . iconv ( 'utf-8', 'gbk', '2') . '</td>';
			echo '<td align="left">' . iconv ( 'utf-8', 'gbk', '3') . '</td>';
			echo '<td align="left">' . iconv ( 'utf-8', 'gbk', '4') . '</td>';
			echo '<td align="left">' . iconv ( 'utf-8', 'gbk', '5') . '</td>';
			echo '<td align="left">' . iconv ( 'utf-8', 'gbk', '6') . '</td>';
			echo '<td align="left">' . iconv ( 'utf-8', 'gbk', '7') . '</td>';
			echo '<td align="left">' . iconv ( 'utf-8', 'gbk', '8') . '</td>';
			echo '<td align="left">' . iconv ( 'utf-8', 'gbk', '9') . '</td>';
			echo '<td align="left">' . iconv ( 'utf-8', 'gbk', '10') . '</td>';
			echo '<td align="left">' . iconv ( 'utf-8', 'gbk', '11') . '</td>';
			echo '<td align="left">' . iconv ( 'utf-8', 'gbk', '12') . '</td>';
		echo '</tr>';	
	?>
	</table>
</body>
</html>