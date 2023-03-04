# taxi-stand-system-backend
タクシー乗り場の可視化・効率化システムのバックエンド。  
## URL  
ローカル:[http://localhost:8000/](http://localhost:8000/)
## 環境構築
1.Dockerコンテナのビルド
```
docker-compose up -d --build
```
2.コンテナに入る
```
docker container exec -it taxi-stand-system-backend-php-1 bash
```
3.ライブラリのインストール
```
composer install
```
4.マイグレーションの実行(.envを作成して情報を書き換える)
```
php artisan migrate
```
