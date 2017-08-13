<?php
/******************************************************************************
** database backup for sae, code from xhhjin (http://xuhehuan.com/1897.html) **
******************************************************************************/
define('DB_DOMAIN','dbbackup');		//备份域
define('DB_BACKUP_PATH','backup');	//备份路径和文件名
define('DB_BACKUP_NUMBER', 20);		//备份数目限制，不超过900
define('DB_MAIL_SMTP', 'smtp.163.com');			//smtp服务器
define('DB_MAIL_PORT', 25);						//smtp端口
define('DB_MAIL_SENDEMAIL', 'egirlasm@163.com');	//发送邮件帐号
define('DB_MAIL_PASSWORD', 'enlong881107');			//发送邮件密码
define('DB_MAIL_TOEMAIL', 'egirlasm@qq.com');	//收信邮件帐号
