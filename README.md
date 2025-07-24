# 📋 Projet Kanban Laravel

Application de gestion de projets et de tâches en mode Kanban, développée avec **Laravel**, **Sail**, **Breeze** et **Docker**.

---

## 🚀 Fonctionnalités principales

- Authentification (Laravel Breeze)
- Gestion de projets (création, édition, suppression)
- Gestion des membres de projet (invitation, suppression)
- Gestion des tâches (Kanban, listes, calendrier)
- Attribution de tâches à des membres
- Priorités, catégories, tags, dates d’échéance sur les tâches
- Commentaires sur les tâches
- Gestion des rôles et permissions (admin, membre, via Laratrust)
- Export Excel de la liste des projets (maatwebsite/excel)
- Interface responsive et moderne


---

## 🛠️ Packages principaux utilisés

- **laravel/framework** : Framework principal
- **laravel/breeze** : Authentification simple
- **maatwebsite/excel** : Export Excel
- **santigarcor/laratrust** : Gestion des rôles et permissions
- **barryvdh/laravel-debugbar** : Debugbar pour le développement
- **laravel/sail** : Environnement Docker prêt à l’emploi

---

## 📦 Installation

### 1. Cloner le projet

```bash
git clone <url-du-repo>
cd projet-final
```

### 2. Copier le fichier d’environnement

```bash
cp .env.example .env
```
Configurer la base de données dans `.env` si besoin.

### 3. Installer les dépendances

```bash
# Installer les dépendances PHP
docker run --rm -v $(pwd):/app composer install

# Installer les dépendances front-end
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

### 4. Démarrer les services Docker

```bash
./vendor/bin/sail up -d
```

### 5. Générer la clé d’application et lancer les migrations

```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```

---

## 🌍 Accès à l’application

- **Frontend** : [http://localhost](http://localhost)
- **PHPMyAdmin** (si activé) : [http://localhost:8080](http://localhost:8080)

---



## 📝 Commandes utiles

| Commande                                 | Description                                 |
|-------------------------------------------|---------------------------------------------|
| `./vendor/bin/sail up -d`                 | Démarrer les conteneurs Docker              |
| `./vendor/bin/sail down`                  | Arrêter les conteneurs                      |
| `./vendor/bin/sail artisan migrate`       | Lancer les migrations                       |
| `./vendor/bin/sail artisan key:generate`  | Générer la clé d’application                |
| `./vendor/bin/sail npm install`           | Installer les dépendances front-end         |
| `./vendor/bin/sail npm run dev`           | Compiler les assets en mode dev             |
| `./vendor/bin/sail artisan test`          | Lancer les tests                            |
| `./vendor/bin/sail artisan storage:link`  | Lier le dossier storage/public              |

---