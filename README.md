# 序言

### 系统介绍
HPAdmin是一套渐进式开源后台，基于Webman进行开发为的后台框架，采用前后端分离技术，数据交互采用JSON格式，功能低耦合高内聚；核心模块支持系统设置、权限管理、管理员、权限菜单、快速构建CRUD功能等功能。

### 系统特性
*   后台多专题主题切换：支持颜色主题和深色模式，页面更加美观
*   PHP直接渲染页面，无需碰任何一行HTML代码即可完成后台开发
*   支持远程vue文件开发渲染页面，全后台支持Vue动态组件页面定制
*   支持多种图标库直接选择使用，可自定义引入图标库，可自定义引入全局JS，CSS等静态资源
*   支持ueditor、wangeditor编辑器
*   动态菜单渲染，直接获取后台权限菜单
*   默认支持百度地图、高德地图插件
*   支持附件库垃圾清理，支持多种附件库上传

### 关于我们
框架网站：<https://hpadmim.hangpu.net>  
官方网站：<https://www.hangpu.net>

### 交流讨论
HPAdmin群：592808248  
Webman官方群：260671135  
Form-create官方群：28963712  

### 步骤1：安装Webman
```sh
composer create-project workerman/webman
```
### 步骤2：安装HPAdmin
```sh
composer require hangpu8/admin
```

### 步骤3：设置站点访问目录
``` text
如是使用宝塔安装，请将站点运行目录设置为public
```

### 步骤4：nginx配置
```nginx
# 此处转发需要设置在server外层
upstream saisaitu888_dev {
  # HPAdmin HTTP Server 的 IP 及 端口
  server 127.0.0.1:39870;
}

# 配置Vue远程跨域访问组件
location ~ .*\.(vue)(.*){
	add_header "Access-Control-Allow-Origin"  *;
	add_header "Access-Control-Allow-Methods" "GET, POST, OPTIONS, HEAD";
	add_header "Access-Control-Allow-Headers" "Authorization, Origin, X-Requested-With, Content-Type, Accept";
	if ($request_method = 'OPTIONS'){
		return 204;
	}
}

# 允许静态资源转发
location ~ .*\.(js|css|jpg|jpeg|gif|png|ico|pdf|txt)$ {
	proxy_pass http://saisaitu888_dev;
}

# Http
location / {
	# 将客户端的 Host 和 IP 信息一并转发到对应节点
	proxy_set_header Host $http_host;
	proxy_set_header X-Real-IP $remote_addr;
	proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
	
	# 转发Cookie，设置 SameSite
	proxy_cookie_path / "/; secure; HttpOnly; SameSite=strict";
	# 关闭重试机制
	proxy_next_upstream off;
	
	# 跨域请求
	if ($request_method = OPTIONS) {
		add_header Access-Control-Allow-Origin $http_origin; # 必须要有
		add_header Access-Control-Allow-Headers *; # 必须要有
		add_header Access-Control-Allow-Methods "GET,POST,PUT, DELETE,OPTION"; # 不加也行
		#add_header Access-Control-Allow-Credentials true; # 不加也行
		return 200; # 204也可以，只要返回成功码即可
	}
	
	# 判断是否访问根域名
	if ( -e $request_uri) {
		proxy_pass http://saisaitu888_dev;
		break;
	}
	# 执行代理访问真实服务器
	if ( !-e $request_filename ){
		proxy_pass http://saisaitu888_dev;
		break;
	}
}
```

### Websocket（可选，如有需要自行添加）
``` nginx
# Websocket
location /tuziwebsocket {
	# 将客户端的 Host 和 IP 信息一并转发到对应节点
	proxy_set_header Host $http_host;
	proxy_set_header X-Real-IP $remote_addr;
	proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
	
	# 转发Cookie，设置 SameSite
	proxy_cookie_path / "/; secure; HttpOnly; SameSite=strict";
	# 关闭重试机制
	proxy_next_upstream off;
	
	proxy_pass http://tuziwebsocket_dev;
	break;
}
```
### 步骤5：设置目录权限
（Linux与mac权限需要设置）
```text
设置 public 目录权限为777
设置 runtime 目录权限为777
设置 vendor 目录权限为777
```
### 步骤6：访问域名，直接安装
```text
以上操作完成，直接访问域名进行安装  
安装完成后，可根据链接选择打开后台登录  
自此所有安装完成，可以进行开发之旅了  
```

### 系统演示
后台演示：<http://demo1.hpadmin.hangpu.net>  
登录账号：admin  
登录密码：123456

<!-- ### 安装页面1
![Image text](https://gitee.com/hangpu888/hpadmin/raw/master/preview/1.png)
### 安装页面2
![Image text](https://gitee.com/hangpu888/hpadmin/raw/master/preview/2.png)
### PHP生成一个表格页面
![Image text](https://gitee.com/hangpu888/hpadmin/raw/master/preview/3.png)
### PHP生成一个表单页面
![Image text](https://gitee.com/hangpu888/hpadmin/raw/master/preview/4.png)
### 后台首页
![Image text](https://gitee.com/hangpu888/hpadmin/raw/master/preview/5.png)
### 配置页面
![Image text](https://gitee.com/hangpu888/hpadmin/raw/master/preview/6.png) -->
