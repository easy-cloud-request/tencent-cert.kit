# dsnpod 证书管理命令

## 使用

### 1. 初始化项目
```
git clone https://github.com/easy-cloud-request/tencent-cert.kit.git
cd tencent-cert.kit
composer install -vvv
```

### 使用命令
#### 查看证书
```bash
php cli.php list
```

#### 申请证书
```bash
php cli.php apply your-domain-name
```

#### 下载证书
```bash
# 证书ID 可从 cli.php list 命令的输出获取
php cli.php download 证书ID
```
