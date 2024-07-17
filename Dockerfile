FROM bitnami/laravel:latest
ADD .env.prod /app/.env.prod
RUN cp .env.prod .env
EXPOSE 9012