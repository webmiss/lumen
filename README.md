# WebMIS
WebMIS is just a development idea.<br>
Home: http://lumen.webmis.vip/<br>
Admin: http://lumen.webmis.vip/admin/<br>
uanme: admin  passwd: admin

# Install
```bash
Database : public/db/mvc.sql
```

# Configuration
### 1) Apache（public/.htaccess）
```bash
# 编码
AddDefaultCharset UTF-8
<IfModule mod_rewrite.c>
    # 目录浏览
    Options Indexes FollowSymLinks
    # 重写
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php?_url=/$1 [QSA,L]
</IfModule>
```

### 2) Nginx
```bash
listen 80;
server_name lumen.webmis.cn;

set $root_path '/home/www/lumen/public/';
root $root_path;
index index.php index.html;

try_files $uri $uri/ @rewrite;
location @rewrite {
    rewrite ^/(.*)$ /index.php?_url=/$1;
}

location ~* ^/(webmis|upload|themes|favicon.png)/(.+)$ {
    root $root_path;
}
```

### Url
```bash
Home: http://localhost/
Admin: http://localhost/admin/Index/index
```