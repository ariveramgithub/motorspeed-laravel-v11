FROM bitnami/laravel:latest
COPY .env.prod /app/.env.prod
EXPOSE 9012