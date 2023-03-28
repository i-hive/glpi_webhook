# GLPI_WEBHOOK工单生成器
## 缘由:GLPI自带的api,流程是先根据app_token和user_token获取一个session_token(有时效性,时间长了会失效),然后再使用这个session_token和app_token来对系统进行操作.使用起来虽然是安全,但是非常不方便.刚好之前做了微信webhook中转,有感而发,做了这个GLPI_WEBHOOK工单生成器(单个PHP文件版)

## 功用:直接输入网址即可新增工单.预存了访问GLPI-API所需的token,直接发送新增工单信息到该网页,无需写任何token,会自动中转工单信息到GLPI-API.

## 安装方法:
### 在GLPI目录下新建一个webhook目录,然后将send.php这个单文件放入

![image](https://user-images.githubusercontent.com/129045365/228146228-2e4bc39b-beb6-497d-a70e-8dee66df8096.png)

## 使用:
### 一 : 预配置token信息:打开send.php,修改其中的app_token和user_token和url为自己GLPI的信息

![image](https://user-images.githubusercontent.com/129045365/228146284-943dbed8-ea81-481d-98b8-3794040c61bf.png)

- API_URL 来自设置-常规-API

![image](https://user-images.githubusercontent.com/129045365/228146335-c91618ee-77da-42c0-9fee-88d02e216bfc.png)

- app_token来自设置-常规-API-添加API客户端-勾选重建-添加,会自动生成.

![image](https://user-images.githubusercontent.com/129045365/228146360-2d1ee526-70e8-4cba-a793-5d14043157be.png)

- user_token 来自登录账户里我的设置

![image](https://user-images.githubusercontent.com/129045365/228146409-c071e2bf-4d38-45aa-8fed-abc2b05f5290.png)

### 二:使用方法
#### 第一种方法: GET方法,直接在浏览器里输入网址使用

`http://网址/glpi/webhook/send.php?name=工单名称&content=工单内容&type=2`

type=2表示工单类型为请求,如果不写,默认类型为事件,即type=1

![image](https://user-images.githubusercontent.com/129045365/228146538-7ba6ac6e-6f8e-4bef-acd5-4c76daad5bbb.png)

我这里使用这个为例,输入网址后,如果新增工单成功,会出现添加成功字样

![image](https://user-images.githubusercontent.com/129045365/228146594-6d5a52cb-2eae-4225-84c8-663aa13ab454.png)

- 如果命令行的话,也可以使用curl 加"网址"的方式来进行批处理添加.
`curl "http://127.0.0.1:8000/glpi/webhook/send.php?name=gongdanmingcheng&content=neirong&type=2"`

![image](https://user-images.githubusercontent.com/129045365/228146757-c94d145d-1a5a-47a0-9f7c-bab9bf88b76e.png)

- curl 后网址如果含有中文,必须经过百分号重编码才会被识别
随便找一个URL编码网站https://www.matools.com/code-convert

![image](https://user-images.githubusercontent.com/129045365/228146787-0b897c2e-99d5-43da-b7fb-4d70e8ed8d1a.png)

![image](https://user-images.githubusercontent.com/129045365/228146801-36d6bea5-ed8a-4420-8312-36bdb29f4c96.png)

#### 第二种办法:POST方法
- 所用的json格式为
{"input":{"name":"工单名称","content":"工单内容","type":"2"}}
- Content-Type 为application/json

![image](https://user-images.githubusercontent.com/129045365/228146847-f6fa4723-621f-40d6-85e6-fe3a412b55f7.png)

- 命令行 curl使用POST方法如下
`curl -X POST -H "Content-Type: application/json" -d "{\"input\":{\"name\":\"gongdanbiaoti\",\"content\":\"gongdanneirong\",\"type\":\"2\"}}" "http://127.0.0.1:8000/glpi/webhook/send.php"`

![image](https://user-images.githubusercontent.com/129045365/228146914-3acad1d8-2001-4f50-9d21-bf05c4ffa391.png)

注意,JSON对象必须用双引号括起来，对象内的双引号必须用反斜杠进行转义。
此处CURL同样无法识别中文内容,如有中文需要Unicode转义序列对其进行编码

## 扩展应用
有了webhook工单生成器作为中转,就可以更简单地链接一切支持webhook接口的应用.
比如zabbix,Prometheus,各种云函数,各种告警方式,IFTTT之类地自动化流程
## 未来可能会添加的功能
- 记录IP,给它增加一个日志功能,每次访问后根据访问IP记录存到各自的log文件中去
- 限制次数,比如同一五分钟内只能发一次,每天限制十次.
- 工单除了标题和内容外增加更多参数细节 点赞到20星开整
- 扫码盘点功能,无需安装插件,无需登录,扫码之后立刻变更设备盘点状态 50星开整
## 各位的点星支持是我为爱发电的动力,请动动手指,右上角点个免费的星,一键三连

![image](https://user-images.githubusercontent.com/129045365/228146954-ca0d2b7c-b61f-4638-8f43-9ed0319b6640.png)

项目网址:https://github.com/i-hive/glpi_webhook

