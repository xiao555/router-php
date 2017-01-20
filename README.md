## 带简单的Router功能的PHP注册登录Demo

### 环境

  1. MacOS
  2. XAMPP

### 配置数据库和安装依赖

1. 执行sql脚本 `init.php` 创建所需的数据库和表
2. 配置 `/Models/database.json` 填上root密码
3. 安装依赖 `composer install`

### 配置服务器通过域名访问

1. 修改hosts

```bash
sudo nano /etc/hosts
```

在最后添加: `127.0.0.1      xiao555.com # 你想设置的域名`

2. 开启虚拟主机

修改xampp配置，编辑`/Applications/XAMPP/xamppfiles/etc/httpd.conf`
搜索`httpd-vhosts.conf`，把这行前面的注释去掉，在最后加上这样一段：

```conf
<Directory "/Applications/XAMPP">
    #Options Indexes FollowSymLinks ExecCGI Includes #don't >permission see list
    Options All
    AllowOverride All
    Order allow,deny
    Allow from all
</Directory>
```

3. 配置虚拟主机

编辑`/Applications/XAMPP/xamppfiles/etc/extra/httpd-vhosts.conf`

```conf
<VirtualHost *:80>
    DocumentRoot  "/Applications/XAMPP/xamppfiles/htdocs/php-login-register/"
    ServerName www.xiao555.com
</VirtualHost>

<VirtualHost *:80>
    DocumentRoot "/Applications/XAMPP/xamppfiles/htdocs/"
    ServerName localhost
</VirtualHost>
```


### 运行

重启xampp，在本地服务器上，浏览器打开[localhost](localhost) 和 [xiao555.com](xiao555.com) 看看效果。

### 说明

前身是 [简单的PHP注册登录Demo](https://github.com/xiao555/PHP-register-login) 在此基础上进行改造。

首先，要实现路由功能，网站的入口只有一个，index.php
这就需要配置一下`.htaccess`伪静态，关于`.htaccess`的可以参考[.htaccess基本语法和应用](http://blog.sina.com.cn/s/blog_6e8b46e701014drc.html)

router逻辑是用的[phroute](https://github.com/mrjgreen/phroute).

目前逻辑都写在`index.php`里，模板放在`Views`目录下，封装了两个自定义函数`render`和 `redirect`，实现模板分离已经够用了。

密码采用hash加密，增强了安全性(==!).

不足：

  1. `phroute` 还不是很熟悉，需要研究
  2. `.htaccess`的配置需要学习一下
  3. 还需要加个`/Public`存放静态资源



