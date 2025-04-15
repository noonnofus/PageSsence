# PageSsence: The Book Review Platform

![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/vuejs-%2335495e.svg?style=for-the-badge&logo=vue.js&logoColor=%234FC08D)
![PrimeVue](https://img.shields.io/badge/primevue-%23007396.svg?style=for-the-badge&logo=prime&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-%2300758F.svg?style=for-the-badge&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)

## 🌐 Demo  
🔗 **Live Website:** 

## 📜 Description
**PageSsence** is a full-featured book review platform I built to help readers share their thoughts and discover new books.  
Users can:
- 📖 Explore a wide range of books  
- 📝 Read and post authentic reviews  
- ⭐ Rate books using a star-based system  
- 🔖 Bookmark their favorite books for easy access later

The app was developed using **Laravel** on the backend and **Vue + PrimeVue** on the frontend. It focuses on clean UI, smooth UX, and a community-first reading experience.  
🛳️ The entire application is containerized using **Docker**, making it easy to set up and run in any environment.

### 🚀 **Planned Features**  
- Integrate MinIO file browser for managing uploaded book covers and media 

## 🛠️ Tech Stack  
- **Backend:** Laravel, MySQL  
- **Frontend:** Vue.js, PrimeVue  
- **Auth:** Laravel Breeze  
- **UI & Components:** PrimeVue, TailwindCSS  
- **Infrastructure:** Docker

## 📦 Installation  
### Prerequisites
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

### Steps
```bash
# Clone the repository
git clone https://github.com/noonnofus/PageSsence.git

# Navigate to the project directory
cd PageSsence

# update the .env file
cp .env.example .env

# Build and start the containers
docker-compose up -d --build

# Run Laravel migrations inside the container
docker exec -it app php artisan migrate

# (Optional) Seed the database
docker exec -it app php artisan db:seed

# (Optional) To seed the database
php artisan thinker
```
