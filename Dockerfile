FROM bitnami/laravel:latest
COPY . .
RUN php /app/artisan migrate
EXPOSE 9012