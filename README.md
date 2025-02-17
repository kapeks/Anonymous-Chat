# Anonymous Chat

## Description

**Anonymous Chat** is a web application for chatting with strangers, developed using **PHP, Laravel, WebSockets, and Redis**.  
The application operates in real-time, allowing instant message exchange.  
It also provides filtering options to find chat partners based on **gender** and **age**.

## Features

✔️ Real-time messaging  
✔️ Anonymous communication without registration  
✔️ Search for chat partners based on criteria  

## Technologies

- **Backend**: PHP 8.1, Laravel 10.10, Redis 7.0.4, WebSockets  
- **Frontend**: Node.js 20.17, Vite, Vue.js   

---

## Installation

### **Prerequisites**
Before installing, make sure you have the following installed:  

- Apache 2.4  
- PHP 8.1  
- Redis 7.0.4  
- Laravel 10.10  
- Node.js 20.17  

### **Installation Guide**

1. **Clone the repository:**
    ```bash
    git clone https://github.com/kapeks/Anonymous-Chat.git
    ```

2. **Install Composer dependencies:**
    ```bash
    composer install
    ```
    This command will install all PHP dependencies specified in `composer.json`.

3. **Install NPM dependencies:**
    ```bash
    npm install
    ```
    This command will install all frontend dependencies specified in `package.json`.

4. **Create and configure the `.env` environment file**  
    Copy `.env.example` to `.env` and configure the environment variables:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    **Example configuration for local development:**
    ```env
    CACHE_DRIVER=redis
    SESSION_DRIVER=redis
    QUEUE_CONNECTION=redis     

    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379

    BROADCAST_DRIVER=pusher
    PUSHER_APP_ID=local
    PUSHER_APP_KEY=local
    PUSHER_APP_SECRET=local
    PUSHER_HOST=127.0.0.1
    PUSHER_PORT=6001
    PUSHER_SCHEME=http
    PUSHER_APP_CLUSTER=mt1
    ```

---

## Running the Application

1. **Start Redis:**
    ```bash
    redis-server
    ```

2. **Start the Laravel server:**
    ```bash
    php artisan serve
    ```

3. **Start the WebSocket server:**
    ```bash
    php artisan websockets:serve
    ```

4. **Start the queue worker:**
    ```bash
    php artisan queue:work
    ```

5. **Run Vite for frontend development or production build:**
    - For **development**:
        ```bash
        npm run dev
        ```
    - For **production**:
        ```bash
        npm run build
        ```

---

## License

This project is licensed under the [MIT](LICENSE) license.

## Contact

📧 **Email:** scalp.profit@gmail.com
