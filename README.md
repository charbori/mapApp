# Laravel map application
# MapApp 서비스 요약

## 📋 프로젝트 개요
**Laravel 기반 수영 기록 관리 서비스**

수영장 위치 정보와 개인 수영 기록을 통합 관리하는 소셜 플랫폼으로, 사용자들이 수영장을 찾고, 기록을 남기며, 다른 사용자들과 소통할 수 있는 종합적인 스포츠 기록 서비스입니다.

## 🎯 핵심 서비스 기능

### 1. 지도 기반 수영장 관리 (`MapManager`)
- **수영장 위치 등록 및 검색**: GPS 좌표 기반 수영장 정보 등록
- **지도 시각화**: 사용자 주변 수영장 위치 표시
- **상세 정보 관리**: 수영장명, 주소, 설명, 태그, 이용자 수 등
- **첨부파일 지원**: 수영장 사진 및 관련 자료 업로드

### 2. 수영 기록 관리 (`RecordManager`)
- **개인 기록 등록**: 수영 타입별 기록 저장 (거리, 시간 등)
- **기록 조회 및 분석**: 개인 수영 기록 히스토리 관리
- **마이페이지**: 개인 기록 통계 및 성과 추적
- **그룹 기록**: 팀/그룹 단위 기록 관리

### 3. 랭킹 시스템 (`RankingController`)
- **전체 랭킹**: 사용자별 수영 기록 순위
- **기록별 랭킹**: 종목별, 거리별 세부 랭킹
- **실시간 데이터**: 외부 랭킹 서버 연동 (http://172.30.1.92:8080/ranking)

### 4. 소셜 네트워킹 기능
- **팔로우 시스템**: 다른 사용자 팔로우/언팔로우
- **게시글 작성**: 수영 관련 글 작성 및 공유
- **댓글 시스템**: 게시글에 대한 댓글 및 답글
- **알림 기능**: 팔로우, 댓글 등에 대한 실시간 알림

### 5. 사용자 관리
- **회원가입/로그인**: Laravel Breeze 기반 인증
- **프로필 관리**: 사용자 정보 및 프로필 사진 업로드
- **권한 관리**: 일반 사용자 및 관리자 구분

## 🛠 기술 스택

### Backend
- **Framework**: Laravel 9.x (PHP 8.0+)
- **Database**: MySQL (메인 DB) + Redis (캐싱/세션)
- **Authentication**: Laravel Sanctum + Laravel Breeze
- **File Storage**: MinIO (S3 호환)
- **Monitoring**: Prometheus + Grafana

### Frontend
- **JavaScript**: Alpine.js 3.x + jQuery
- **CSS Framework**: Bootstrap 5.3 + TailwindCSS 3.3
- **Build Tool**: Vite 3.x
- **Icons**: Bootstrap Icons + FontAwesome

### Infrastructure
- **Containerization**: Docker + Docker Compose
- **Web Server**: Nginx
- **SSL**: Certbot (Let's Encrypt)
- **Device Support**: 모바일/데스크톱 반응형 지원

## 📊 데이터 모델 구조

### 핵심 엔티티
1. **Users**: 사용자 정보 (이름, 이메일, 프로필 등)
2. **MapList**: 수영장 정보 (위치, 이름, 설명, 태그 등)
3. **SportsRecord**: 수영 기록 (사용자, 수영장, 기록, 종목)
4. **Articles**: 게시글 (제목, 내용, 첨부파일)
5. **Groups**: 그룹/팀 관리
6. **Follow**: 팔로우 관계
7. **Comments**: 댓글 시스템
8. **Alarms**: 알림 관리

### 관계형 구조
- 사용자 ↔ 수영기록 (1:N)
- 수영장 ↔ 수영기록 (1:N)
- 사용자 ↔ 게시글 (1:N)
- 게시글 ↔ 댓글 (1:N)
- 사용자 ↔ 팔로우 (M:N)

## 🚀 주요 API 엔드포인트

### 지도 관련
- `GET /map` - 지도 메인 페이지
- `POST /map/store` - 수영장 정보 등록
- `GET /api/search` - 수영장 검색

### 기록 관련
- `GET /record` - 기록 메인 페이지
- `POST /record/store` - 수영 기록 등록
- `GET /record/ranking` - 랭킹 조회
- `GET /record/mypage` - 개인 기록 페이지

### 소셜 기능
- `POST /api/follow` - 팔로우/언팔로우
- `POST /articles` - 게시글 작성
- `POST /reply/store` - 댓글 작성

## 📈 모니터링 및 성능

### Prometheus 메트릭스
- **API 호출 로깅**: URL, 상태코드, 실행시간 추적
- **시스템 모니터링**: CPU, 메모리 사용률
- **헬스체크**: 서비스 상태 모니터링
- **Redis 로그 수집**: 실시간 로그 데이터 저장

### 로그 관리
- **Log Viewer**: 관리자용 로그 조회 시스템
- **실시간 모니터링**: 에러 및 성능 지표 추적

## 🔧 배포 및 운영

### Docker 기반 배포
```bash
# 개발 환경
docker-compose -f docker-compose.dev.yml up -d

# 프로덕션 환경  
docker-compose -f docker-compose.laravel.yml up -d
```

### 주요 환경 설정
- **MySQL**: 메인 데이터베이스
- **Redis**: 캐싱 및 세션 저장소
- **MinIO**: 파일 저장소
- **Prometheus**: 메트릭 수집 서버

## 📱 특화 기능

### 모바일 최적화
- 모바일 전용 라우팅 (`route_mo.php`)
- 반응형 UI/UX 디자인
- 터치 기반 인터페이스

### 확장성
- 마이크로서비스 지향 아키텍처
- API 기반 통신
- 컨테이너 기반 배포로 확장 용이

---

 **주요 특징**: 수영 전문 기록 관리 + 소셜 네트워킹 + 지도 기반 위치 서비스 통합 플랫폼
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
