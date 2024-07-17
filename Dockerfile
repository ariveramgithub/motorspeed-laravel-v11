FROM bitnami/laravel:latest
ADD .env.prod /app/.env.prod
EXPOSE 9012