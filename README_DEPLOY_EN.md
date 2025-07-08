# MFW App – Automated Deployment with GitHub Actions and Terraform

This document describes the full deployment pipeline for your MFW App using:
- **Terraform**: to provision the EC2 instance.
- **GitHub Actions**: to automatically deploy the app after each push to the main branch.

---

## 🌍 1. Infrastructure Deployment with Terraform (one-time setup)

We use Terraform to launch and configure an EC2 instance.

### 📁 Files involved
```
terraform/
├── main.tf                  # Defines the EC2 instance and startup script (user_data)
├── variables.tf             # Defines required variables (SSH key name, region, etc.)
├── terraform.tfvars         # Stores sensitive values like AMI ID, key name, etc.
├── outputs.tf               # (Optional) Outputs instance details
├── setup.sh                 # (Optional) Manual deployment script (not used in pipeline)
```

### 🚀 Deployment steps
1. Launch from your terminal:
```bash
terraform init
terraform apply -var-file="terraform.tfvars"
```

2. This will:
   - Create an EC2 instance with Docker and Git installed.
   - Clone your repo.
   - Launch your app with `docker compose`.

---

## 🔐 2. GitHub Secrets Configuration

In your GitHub repository, go to **Settings > Secrets > Actions** and create the following secrets:

| Secret name           | Description                              |
|-----------------------|------------------------------------------|
| `PEM_PRIVATE_KEY`     | Content of your `.pem` private key       |
| `EC2_USER`            | Usually `ubuntu`                         |
| `EC2_HOST`            | Public IP of your EC2 instance           |
| `ENV_FILE_CONTENT`    | Contents of your `.env` file             |

---

## ⚙️ 3. GitHub Actions Workflows

### `deploy.yml` – Auto deploy on `main` push

Path: `.github/workflows/deploy.yml`

Triggers on every push to the `main` branch. Steps:
1. Check out the repo
2. Write the SSH key and connect to EC2
3. Upload the `.env` file
4. Connect to EC2 and:
   - Pull the latest changes
   - Rebuild and restart the containers

### `test-ssh.yml` – Manual SSH connection test

Path: `.github/workflows/test-ssh.yml`

Triggered manually via GitHub UI. Verifies SSH connectivity:
```yaml
on:
  workflow_dispatch:
```

---

## ✅ How to Test the Pipeline

1. Make a small change (e.g. change a heading) and run:
```bash
git add .
git commit -m "Test deploy workflow"
git push origin main
```

2. Go to GitHub → Actions → `Deploy MFW-App to EC2` should run.

3. Visit your EC2 IP at:  
   **http://<EC2_PUBLIC_IP>:8000**

---

## 🛠️ Troubleshooting

- Make sure port **8000** is open in your EC2 Security Group.
- The SSH key must match the one used in Terraform (`key_name`).
- Confirm your `.env` file contents are valid and match your app.

---

## 📎 Notes

- You can customize the workflow to include other branches or actions (e.g., staging).
- Consider rotating keys and using AWS IAM Roles for better security.