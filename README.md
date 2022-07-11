# <p align="center">Larashop</p>

## 关于

> 考虑到有些客户端只支持 `GET` 和 `POST` 请求，所以没有完全遵守 RESTful 设计风格。

依托 [Laravel Template](https://github.com/zhanbai/laravel-template) 模板实现的开源电商系统。

- 用户模块
- 管理后台
- 商品模块
- 购物车
- 订单模块
- 支付模块
- 优惠券模块

## 使用

```bash
# 克隆项目
$ git clone https://github.com/zhanbai/larashop.git project-name

# 进入项目目录
$ cd project-name

# 安装依赖
$ composer install

# 创建并修改 .env 文件内容，主要是数据库信息
$ cp .env.example .env

# 执行数据库迁移
$ php artisan migrate

# 启动服务
$ php artisan serve
```

浏览器访问 http://127.0.0.1:8000

## 协议

本项目开源，基于 [MIT 开源协议](https://opensource.org/licenses/MIT)。
