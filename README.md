# 介绍
因本人家里有nas，需要做外网访问。但是家庭宽带IP每次拨号都会变化。因第三方内网穿透工具都会限速。所以定时检查IP与阿里云解析是否一致。利用阿里云的接口解析域名。
[阿里云文档](https://www.alibabacloud.com/help/zh/doc-detail/34271.htm?spm=a2c63.p38356.b99.34.311e5c0eOy6YHf)
# 使用
配置config.php文件中的全局参数

```
define('ACCESS_KEYID',''); //阿里云access——key
define('ACCESS_SECRET','');//阿里云access-secret
define('DOMAIN','example.com');//要解析的域名
define('IP_URL','https://api.ioser.net/api/get_ip'); // 请求获取公网IP的地址
define('DOMAIN_RECORDS',''); // 要解析的二级域名 格式 'a,b,c,d'
```

#####简单使用
```
php ali_dns.php
```

# 执行定时任务

#####  使用 CRONJOB
1. 下载项目随便放个目录, 例: /root/aliyun_ddns
2. 编辑config.php
3. 创建定时任务执行  php ali_dns.php:

```
*/5 * * * * cd /root/aliyun_ddns && php ddns.py
```
