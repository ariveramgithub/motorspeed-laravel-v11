FROM bitnami/laravel:latest
ADD .env.prod /app/.env.prod
RUN cp .end.exmple .env
EXPOSE 9012