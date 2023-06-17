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
3.モジュールのインストール
```
composer install
```
4.マイグレーションの実行(※事前に.envを作成して情報を書き換える)
```
php artisan migrate
```
5.Octaneをインストール(swoole[1]を選択)
```
php artisan octane:install
```
6.Octaneを起動
```
php artisan octane:start --host=0.0.0.0 --port=8000
```
