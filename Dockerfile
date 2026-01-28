FROM php:8.4-cli

# Instalujemy zależności systemowe dla FFI i SDL2
RUN apt-get update && apt-get install -y \
    libffi-dev \
    libsdl2-2.0-0 \
    libsdl2-image-2.0-0 \
    && docker-php-ext-install ffi

# Ustawiamy konfigurację FFI na "zawsze włączone"
RUN echo "ffi.enable=true" > /usr/local/etc/php/conf.d/ffi.ini

WORKDIR /app
