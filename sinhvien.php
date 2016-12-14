<?php
	include '../mylib.php';
	$conn = mysql_connect('localhost','root','minh96');
	mysql_query('create database if not exists sinhvien');
	mysql_select_db('sinhvien');
	mysql_query('SET character_set_result=utf8');
	mysql_query('SET NAMES UTF8');
	$rb = $_POST['rb'];
	echo "<center>";
			echo "<h1>Chương trình quản lý sinh viên</h1>";
			echo formHtOpen('Quản lý sinh viên','myform','POST','#');
			if(isset($_POST['rb']) && $_POST['rb']!=''){
				$ma = $_POST['rb'];
			}
				echo "Mã sinh viên ".textbox('ma',$ma)."<br/><br/>";
				echo "Họ và tên ".textbox('ten','').'<br/><br/>';
				echo cmd('Nhập').cmd('Tìm').cmd('Xóa').'<br/>';
				if(isset($_POST["cmd"])){
					switch($_POST["cmd"]){
						case "Xóa":
							$count = 0;
							foreach ($_POST as $key => $value) {
								IF($key!="" && is_numeric($value)){
									$sql = "DELETE FROM sinhvien WHERE ma = '$value'";
									if(mysql_query($sql,$conn)){
										$count++;
									}
								}
							}
							if($count>0){
								echo "Bạn đã xóa ".$count." bản ghi";
							}
							break;
						case "Nhập":
							$ma = $_POST["ma"];
							$ten = $_POST["ten"];
							if($ma!="" && $ten!=""){
								if($rb==''){
									$sql = "INSERT INTO sinhvien VALUES('$ma','$ten')";
									if(mysql_query($sql,$conn)){
										echo "Bạn đã thêm thành công một bản ghi";
									}
								}else{
									$sql = "UPDATE sinhvien SET hoten='$ten' WHERE ma='$ma'";
									if(mysql_query($sql,$conn)){
										echo "Bạn vừa update thành công sinh viên có mã số ".$ma;
									}
								}
							}
							break;
					}
				}
				$sql = "SELECT * FROM sinhvien";
				$sinhvien = array();
				$index=0;
				$ma = trim($_POST["ma"]);
				$ten = trim($_POST["ten"]);
				
				if(isset($_POST["cmd"]) && $_POST["cmd"]=="Tìm"){
						$ma = $_POST["ma"]==""?"":(int)$_POST["ma"];
						$ten = $_POST["ten"]==""?"":trim($_POST["ten"]);
						if($ma!="" && $ten=="") $sql = "SELECT * FROM sinhvien WHERE ma=$ma";
						if($ma=="" && $ten!="") $sql = "SELECT * FROM sinhvien WHERE hoten LIKE '%$ten%' ";
						if($ma!="" && $ten!="") $sql = "SELECT * FROM sinhvien WHERE ma='$ma' AND hoten LIKE '%$ten%' ";
				}
				$result = mysql_query($sql,$conn);

				while($row=mysql_fetch_array($result)){
					$sinhvien[$index++] = $row;
				}

				echo tblOpen(1,'50%');

					echo "<br/>".tr(td('Số thứ tự').td().td().td('Mã sinh viên').td('Họ và tên'));
					for($i=0;$i<$index;$i++){
						$ma = $sinhvien[$i][0];
						$ten = $sinhvien[$i][1];
						echo tr(td($i+1).td(cb($ma,'',$ten)).td(rb($ma,$rb,'rb','1')).td($ma).td($ten));

				}
				echo tblClose();

			echo formHtClose();
		echo htClose();
	echo "</center>";
?>