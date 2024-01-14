# Laravel map application
## 수영 기록 서비스
+ 보드 생성
+ 수영 데이터 기록

## Docker setup
```
docker-compose -f docker-compose.laravel.yml up -d
docker exec laravel_app npm run build
docker exec laravel_app npm run build-css
docker exec laravel_app composer dump-autoload
```
### docker-compose 파일 분리
+ https 지원 webroot certbot, nginx 서버 conf 설정 
+ laravel, redis 컨테이너 설정

## Test
```
docker exec laravel_app php artisan cache:clear
docker exec laravel_app php artisan config:clear
docker exec laravel_app php artisan migrate --env=testing
docker exec laravel_app php artisan migrate
docker exec laravel_app php artisan test
```

## Deploy command
```
docker exec laravel_app php artisan db:seed
docker exec laravel_app php artisan cache:clear
docker exec laravel_app php artisan config:clear
docker exec laravel_app php artisan config:cache
docker exec laravel_app php artisan storage:link
```

## DB setup .env
+ mysql DB 설정필수
+ redis db 설정필수 
+ prometheus host 설정

## Prometheus, Grafana
+ Uri call logger
  application middleware request data log create
  [url, status, execution_time, time] data redis log exporting 
+ heart beat
+ cpu, memory tracking 
