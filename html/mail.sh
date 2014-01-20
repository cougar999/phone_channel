#!/bin/bash
LDIR=`cd $(dirname $0);pwd`
NDATE=`date +'%Y%m%d'`
VERSION=`cat $LDIR/tmp/version.txt`
NVERSION=`echo $VERSION + 1 |bc`

biaoti=`echo -n IOS授权文件测试环境已同步|base64`
Subname="=?UTF-8?B?${biaoti}?="
sendmail -t <<EOF
From: support@appdear.com
To: lizhendong@appdear.com
Bcc: lizhendong@appdear.com
Subject: $Subname
Content-Type: text/html ; charset=utf-8
你好<br>
 IOS授权文件测试环境已同步，可以测试<br>
 测试过测试环境IOS授权的爱皮下载，在验证线上授权功能时，需要删除爱皮下载目录下的update.txt.

EOF
