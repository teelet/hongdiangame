可以按照以下步骤来部署和运行程序:
1.请确保机器已经安装了Yaf框架, 并且已经加载入PHP;
2.需要在php.ini里面启用如下配置，生产的代码才能正确运行：
	yaf.environ="product"
3.重启Webserver;
4.访问http://yourhost/,出现Hellow Word!, 表示运行成功,否则请查看php错误日志;
5.静态文件路由rewrite指定root到 static 目录