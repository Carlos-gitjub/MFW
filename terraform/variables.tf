variable "aws_access_key" {
  type      = string
  sensitive = true
}

variable "aws_secret_key" {
  type      = string
  sensitive = true
}

variable "aws_region" {
  type    = string
  default = "eu-west-1"
}

variable "ami_id" {
  type    = string
  default = "ami-0fc5d935ebf8bc3bc" # Ubuntu 22.04 en EU-WEST-1
}

variable "instance_type" {
  type    = string
  default = "t2.micro"
}

variable "key_name" {
  type = string
}

variable "private_key_path" {
  type = string
}
