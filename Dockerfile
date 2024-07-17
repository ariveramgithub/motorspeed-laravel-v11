FROM bitnami/laravel:latest
COPY . .
RUN php artisan migrate
EXPOSE 9012