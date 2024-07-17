FROM bitnami/laravel:latest
COPY .env.prod .env
EXPOSE 9012