# GLPI
GLPI_WEBHOOK msg send
## 缘由:GLPI自带的api,流程是先根据app_token和user_token获取一个session_token(有时效性,时间长了会失效),然后再使用这个session_token和app_token来对系统进行操作.使用起来虽然是安全,当时非常不方便.刚好之前做了微信webhook中转,有感而发,做了这个GLPI_WEBHOOK工单生成器(单个PHP文件版)
## 功用:预存了访问GLPI-API所需的token,直接发送新增工单信息到该网页,无需写任何token,它会自动中转工单信息到GLPI-API
## 安装方法:
### 在GLPI目录下新建一个webhook目录,然后将send.php这个单文件放入
