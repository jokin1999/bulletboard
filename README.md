# Bullet Board

子弹板，用于快速传递简要数据，实现无登录、无授权、无需下载的三无产品。

Demo: [bbdemo.990521.xyz](http://bbdemo.990521.xyz)


## 快速安装 / Quick Start

### 配置 / Settings

|设置项 / Key|环境变量 / Env Key|默认值 / Value|
|:-:|:-:|:-:|
|$base_url|BB_BASE_URL|http://bbdemo.990521.xyz|
|$save_path|BB_SAVE_PATH|_tmp|

在部署子弹板前，需要对`index.php`文件进行设置，设置`$base_url`变量的值为您的站点地址，或者通过**环境变量**`BB_BASE_URL`设置（环境变量设置优先级高于变量设置，**推荐使用环境变量设置**）。

请确保`BB_SAVE_PATH`环境变量或`$save_path`变量指向的目录路径可读可写。

### 重定向 / REWRITE

#### Apache 设置

需要开启 `mod_rewrite` 并对站点设置 `.htaccess` 文件.
详见 [How To Set Up mod_rewrite for Apache](https://www.digitalocean.com/community/tutorials/how-to-set-up-mod_rewrite-for-apache-on-ubuntu-14-04).

#### Nginx 设置

请参考下方案例并设置Nginx配置文件：

如果子弹板位于网站根目录：
```
location / {
    rewrite ^/([a-zA-Z0-9_-]+)$ /index.php?note=$1;
}
```

如果子弹板位于网站子目录（以note子目录为例）:
```
location ~* ^/notes/([a-zA-Z0-9_-]+)$ {
    try_files $uri /notes/index.php?note=$1;
}
```

### GearHost 一键部署

**以下教程基于已经Fork本项目的基础上进行！**

**Fork this project first!**

#### 中文
- 注册/登录 [GearHost](https://gearhost.com)
- 创建一个`CloudSite`并进入控制台
- 点击`Domain`并绑定域名
- `Config`设置中
  - `PHP VERSION` -> `7.0`或更新版本
  - `APP SETTINGS`新增以下设置
    - `BB_BASE_URL` => 你的网站域名（**必须设置**）
    - `BB_SAVE_PATH` => 数据存储文件夹
- `Publish`中
  - 选择 `GitHub`
  - 选择 `Authorize` 并填写相关信息
  - 选择 `bulletboard` 项目（Project）， `master` 分支（Branch）
  - 点击 `Activate`

#### English
- Register / Login on [GearHost](https://gearhost.com)
- Create an `CloudSite` and enter the console of it
- Click `Domain` and bind your domain
- Click `Config`
  - `PHP VERSION` -> `7.0` or newer version
  - add the following settings to `APP SETTINGS`
    - `BB_BASE_URL` => your domain（**necessary**）
    - `BB_SAVE_PATH` => data folder
- Click `Publish`
  - Click `GitHub`
  - Click `Authorize` and follow the guide
  - Click `bulletboard` Project， `master` Branch
  - Click `Activate`

## 关于 / About

This project is **forked** from [pereorga/minimalist-web-notepad](https://github.com/pereorga/minimalist-web-notepad)

Changes can be checked in commits.

If there is any problem about copyright and license, please email me (327928971@qq.com) and let me know.
