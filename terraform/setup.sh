#!/bin/bash

sudo apt update
sudo apt install -y docker.io docker-compose git unzip curl

# Clona tu repositorio (reemplaza con tu URL real)
git clone https://github.com/Carlos-gitjub/MFW-App.git

cd MFW-App

# Opcional: crear .env si no existe
touch .env

# Construir y arrancar la app
sudo docker compose --file docker-compose.yml up -d --build
