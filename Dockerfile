FROM php:8.4-apache

# ติดตั้ง system libraries สำหรับ GD (รองรับ jpeg/png/webp)
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev libjpeg62-turbo-dev libwebp-dev \
 && docker-php-ext-configure gd --with-jpeg --with-webp \
 && docker-php-ext-install gd pdo_mysql \
 && rm -rf /var/lib/apt/lists/*

# เปิด mod_rewrite สำหรับ .htaccess (front controller routing)
RUN a2enmod rewrite

# อนุญาตให้ .htaccess override ได้ (default คือ None)
RUN sed -ri 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

# คัดลอกโค้ดทั้งหมด (.dockerignore กันไฟล์ที่ไม่ต้องการ เช่น config.php จริง)
COPY . /var/www/html

# สร้าง config.php จาก example (อ่านค่าจาก env vars), เตรียมโฟลเดอร์ uploads, ตั้ง permission
RUN cp config/config.example.php config/config.php \
 && mkdir -p uploads \
 && chown -R www-data:www-data /var/www/html

EXPOSE 80
