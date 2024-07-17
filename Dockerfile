FROM bitnami/laravel:latest
ADD .env.prod /app/.env.prod
RUN cp .end.prod .env
EXPOSE 9012