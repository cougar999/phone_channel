<?php
/**
* ʹ��google api���ɶ�ά��
* http://n.appdear.com/api/common/charQr.php?u=cc&s=70x70
*/

// cht������qrΪ��ά��
$data['cht'] = 'qr';
// chs���������ɶ�ά��ĳߴ磬width*height
$data['chs'] = !!empty($_REQUEST['s'])?'100x100':$_REQUEST['s'];
// chl���������ɶ�ά���������Ϣ�����ܻ���������ͣ������Ӱ��PHP�������뾭��urlencode���룬��С����2kb��ʹ��POST�ύ
$data['chl'] = $_REQUEST['u'];

// choe��������ѡ������Ĭ��UTF-8
// chld��������ѡ����������ȼ���L-(Ĭ��)����ʶ������ʧ7%�����ݣ�M-����ʶ������ʧ15%�����ݣ�Q-����ʶ������ʧ25%�����ݣ�H-����ʶ������ʧ30%�����ݡ�margin ��ָ���ɵĶ�ά����߿�ľ��롣
$data['chld'] = !!empty($_REQUEST['l'])?'L|0':$_REQUEST['l']; // ����margin=2
$query = http_build_query($data);

// �����ά��ͼƬ
echo file_get_contents("http://chart.apis.google.com/chart?".$query);
?>