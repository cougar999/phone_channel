<?php
/*
XML
$xml = new XmlDataBase("NewFile.xml","item");
$newarray = array(
	"id"   =>"0",
    "name"=>iconv("gb2312","utf-8","Ĭ�����"),
    "hits"=>"0",
    "cover "=>"sysimg/default.gif"
);
�޸�
$insert=$xml->xml_query('update','title,=,XML00','and',$newarray);
ɾ��
$array = array();
$insert=$xml->xml_query('update','title,=,XML,id,<,3','or',$array);//��������
������ѯ
$data=$xml->xml_query('select','title,=,XML,id,<,2','and');
��ѯȫ��
$data=$xml->xml_fetch_array('title,=,na,id,>=,3' , 'and');
����
$insert=$xml->xml_query('insert','title,=,na,id,>=,3' , 'and' ,$newarray);//�ڶ�������������λ�������������ձ�ʾ��������
print_r($data);exit;
*/

class XmlDataBase {
	var $dbase; //���ݿ�,Ҫ��ȡ��XML�ļ�
	var $dbname; //���ݿ�����,����Ԫ��,�����ݿ��ļ�����һ��
	var $dbtable; //���ݱ�,Ҫȡ�õĽڵ�
	var $parser; //������ 
	var $vals; //����
	var $index; //����
	var $dbtable_array; //�ڵ�����
	var $array; //�¼��ڵ������
	var $result; //���صĽ��
	var $querys;
	
    function checkFile()
    {
	    if(!file_exists($this->dbase) || !(file_get_contents ( $this->dbase )))
	    {
		    $xmlstr='<?xml version="1.0" encoding="UTF-8"?>';
		    $hd=fopen($this->dbase,'w');
		    fwrite($hd,$xmlstr);
		    fclose($hd);
	    }
    }
	
	function XmlDataBase($dbase, $dbtable) {
		$this->dbase = $dbase;
		$this->dbname = substr ( basename($dbase), 0, - 4 );
		$this->dbtable = $dbtable;
		$this->checkFile();
		$data = $this->ReadXml ( $this->dbase );
		if (! $data) {
			die ( "�޷���ȡ $this->dbname.xml" );
		}
		$this->parser = xml_parser_create ();
		xml_parser_set_option ( $this->parser, XML_OPTION_CASE_FOLDING, 0 );
		xml_parser_set_option ( $this->parser, XML_OPTION_SKIP_WHITE, 1 );
		xml_parse_into_struct ( $this->parser, $data, $this->vals, $this->index );
		xml_parser_free ( $this->parser );
		//����������ɸѡ��Ҫȡֵ�Ľڵ� �ڵ���:$dbtable
		foreach ( $this->index as $key => $val ) {
			if ($key == $this->dbtable) {
				//ȡ�ýڵ�����
				$this->dbtable_array = $val;
			} else {
				continue;
			}
		}
		for($i = 0; $i < count ( $this->dbtable_array ); $i += 2) {
			$offset = $this->dbtable_array [$i] + 1;
			$len = $this->dbtable_array [$i + 1] - $offset;
			//array_slice() ���ظ��� offset �� length ������ָ���� array �����е�һ�����С�
			//��ȡ�ڵ��¼�����
			$value = array_slice ( $this->vals, $offset, $len );
			//ȡ����Ч���飬�ϲ�Ϊ�������
			$this->array [] = $this->parseEFF ( $value );
		}
		return true;
	}
	//��XML�ļ����벢�����ַ���
	function ReadXml($file) {
		return file_get_contents ( $file );
	}
	//ȡ����Ч����
	function parseEFF($effective) {
		for($i = 0; $i < count ( $effective ); $i ++) {
			$effect [$effective [$i] ["tag"]] = $effective [$i] ["value"];
		}
		return $effect;
	}
	//xml_query(����,����,������ʱ�߼������and or or,�������µ�����)
	function xml_query($method, $condition, $if = 'and', $array = array()) {
		if (($method == 'select') || ($method == 'count')) {
			return $this->xml_select ( $method, $condition, $if );
		} elseif ($method == 'insert') {
			return $this->xml_insert ( $condition, $if, $array );
		} elseif ($method == 'update') {
			return $this->xml_update ( $condition, $if, $array );
		}
	}
	//ȡ��xml����
	function xml_fetch_array($condition, $if) {
		//$this->querys++;
		$row = $this->array; //��ʼ����������
		if ($condition) {
			//�Ƿ�������,�������������ɷ�������������
			//������������,������ʽ field,operator,match
			$condition = explode ( ",", $condition ); //��������
			$cs = count ( $condition ) / 3; //������
			for($i = 0; $i < $cs; $i ++) {
				$conditions [] = array ("field" => $condition [$i * 3], "operator" => $condition [$i * 3 + 1], "match" => $condition [$i * 3 + 2] );
			}
			//echo count($row);
			for($r = 0; $r < count ( $row ); $r ++) {
				for($c = 0; $c < $cs; $c ++) {
					//$i++;
					$condition = $conditions [$c]; //��ǰ����
					$field = $condition ['field']; //�ֶ�
					$operator = $condition ["operator"]; //�����
					$match = $condition ['match']; //ƥ�� 
					if (($operator == '=') && ($row [$r] [$field] == $match)) {
						$true ++; //����������,��������1 
					} elseif (($operator == '!=') && ($row [$r] [$field] != $match)) {
						$true ++; //����������,��������1
					} elseif (($operator == '<') && ($row [$r] [$field] < $match)) {
						$true ++; //����������,��������1
					} elseif (($operator == '<=') && ($row [$r] [$field] <= $match)) {
						$true ++; //����������,��������1
					} elseif (($operator == '>') && ($row [$r] [$field] > $match)) {
						$true ++; //����������,��������1
					} elseif (($operator == '>=') && ($row [$r] [$field] >= $match)) {
						$true ++; //����������,��������1
					}
				}
				//��������ȡֵ
				if ($if == 'and') {
					//���������Ϊand,������������������ʱ,��������
					if ($true == $cs) {
						$result [] = $row [$r];
					}
				} else {
					//���������Ϊor,���з��ϼ�¼ʱ,��������
					if ($true != 0) {
						$result [] = $row [$r];
					}
				}
				//echo $true;
				//echo "<pre style="font-size:12px;text-align:left">";
				//print_r($true);
				$true = 0; //��������������,������һ��ѭ��
			}
		} else {
			$result = $this->array;
		}
		//echo "<pre style="font-size:12px;text-align:left">";
		//print_r($this->result);
		return $result;
	}
	//ɸѡ��ͳ��
	function xml_select($method, $condition, $if) {
		$result = $this->xml_fetch_array ( $condition, $if );
		if ($method == 'select') {
			return $result;
		} else {
			return count ( $result );
		}
	
	}
	//��������
	function xml_insert($condition, $if, $array) {
		$data = $this->xml_fetch_array ( $condition, $if ); //����������
		$data [] = $array; //����������������
		$this->array = $data; //����������
		$this->WriteXml ( $data );
	}
	
	//�õ����µ�XML����д
	function xml_update($condition, $if, $array) {
		$datas = $this->array; //����������
		$subtract = $this->xml_fetch_array ( $condition, $if ); //Ҫ���µ�����
		//echo "<pre style="font-size:12px;text-align:left">";
		//print_r($data);
		//print_r($datas);
		//echo "ÿ����¼����".count($datas[0])."��ֵ<br>";
		for($i = 0; $i < count ( $datas ); $i ++) {
			$data = $datas [$i];
			//echo "ԭʼ��¼�еĵ�".$i."��<br>";
			foreach ( $data as $k => $v ) {
				//echo "-��".$i."����".$k."ֵΪ".$v."<br>";
				//echo "--Ҫ���ҵ�����".$k."ֵΪ".$subtract[0][$k]."<br>";
				if ($v == $subtract [0] [$k]) {
					$is ++;
				}
			}
			if ($is == count ( $data )) {
				//echo "----���".$i."������<br>";
				$datas [$i] = $array;
				if(empty($array))
				array_splice($datas,$i,1);
			}
			//echo "ԭʼ��¼�еĵ�".$i."����Ҫ���ҵ���".$is."ƥ��<br>"; 
			//echo "ԭʼ��¼�еĵ�".$i."������<br>";
			$is = 0;
		}
		//array_splice($datas,2,2+1,$array);
		//echo "<pre style="font-size:12px;text-align:left">";
		//print_r($datas);
		$this->array = $datas;
		$this->WriteXml ( $datas );
	
	}
	//д��XML�ļ�(ȫ��д��)
	function WriteXml($array) {
		if (! is_writeable ( $this->dbase )) {
			die ( "�޷�д��" . $this->dbname . ".xml" );
		}
		$xml .= "<?xml version='1.0' encoding='utf-8'?>\n";
		$xml .= "<$this->dbname>\n";
		for($i = 0; $i < count ( $array ); $i ++) {
			$xml .= "\t<$this->dbtable>\n";
			foreach ( $array [$i] as $k => $s ) {
				$xml .= "\t\t<$k>$s</$k>\n";
			}
			$xml .= "\t</$this->dbtable>\n";
		}
		$xml .= "</$this->dbname>";
		$fp = @fopen ( $this->dbase, "w" );
		flock ( $fp, LOCK_EX );
		rewind ( $fp );
		fputs ( $fp, $xml );
		fclose ( $fp );
	}
	//����д��xml(������д��10000��,�о�ûһ��д��죬����û������д�뷽ʽ)
	function WriteLine($array) {
		if (! is_writeable ( $this->dbase )) {
			die ( "�޷�д��" . $this->dbname . ".xml" );
		}
		$fp = @fopen ( $this->dbase, "w" );
		rewind ( $fp );
		flock ( $fp, LOCK_EX );
		fputs ( $fp, "<?xml version='1.0' encoding='utf-8'?>\n" );
		fputs ( $fp, "<$this->dbname>\n" );
		for($i = 0; $i < count ( $array ); $i ++) {
			fputs ( $fp, "<$this->dbtable>\n" );
			$xml .= "<$this->dbtable>\n";
			foreach ( $array [$i] as $k => $s ) {
				fputs ( $fp, "<$k>$s</$k>\n" );
			}
			fputs ( $fp, "</$this->dbtable>\n" );
		}
		fputs ( $fp, "</$this->dbname>" );
		fclose ( $fp );
	}
}
?>