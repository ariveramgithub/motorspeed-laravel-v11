FROM bitnami/laravel:latest
RUN cp .env.prod .env
EXPOSE 9012