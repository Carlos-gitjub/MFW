provider "aws" {
  region     = var.aws_region
  access_key = var.aws_access_key
  secret_key = var.aws_secret_key
}

resource "aws_instance" "mfw_app" {
  ami                         = var.ami_id
  instance_type               = var.instance_type
  associate_public_ip_address = true
  key_name                    = var.key_name

  user_data = <<-EOT
    #!/bin/bash
    sudo apt update
    sudo apt install -y git unzip curl

    # Instalar Docker (script oficial)
    curl -fsSL https://get.docker.com -o get-docker.sh
    sh get-docker.sh

    # Añadir usuario ubuntu al grupo docker (opcional)
    sudo usermod -aG docker ubuntu

    # Instalar docker-compose plugin
    sudo apt install -y docker-compose-plugin

    # Clonar el repositorio (reemplaza con tu repo real)
    git clone https://github.com/Carlos-gitjub/MFW-App.git
    cd MFW-App

    # Crear .env si es necesario
    touch .env

    # Arrancar el contenedor
    sudo docker compose up -d --build
  EOT

  tags = {
    Name = "mfw-app-server"
  }

}
