#!/bin/sh

# 1. node_modules 및 composer 설치 (보통 빌드 때 처리했지만 안전용)
npm install

# 2. 프론트 dev 서버 실행 (백그라운드로)
npm run dev &

# 3. Laravel 서버 실행
php artisan serve --host=0.0.0.0 --port=8000
