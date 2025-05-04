# projet-esgi-final-laravel
# Laravel + Sail + Breeze + Docker - Guide d'installation

Ce projet est une application Laravel utilisant **Sail** pour la gestion des conteneurs Docker et **Breeze** pour l'authentification.

## 📌 Prérequis

Avant de commencer, assurez-vous d'avoir installé :
- **Docker** ([Télécharger Docker](https://www.docker.com/get-started))
- **Git** ([Télécharger Git](https://git-scm.com/downloads))

## 🚀 Installation

### 1️⃣ **Cloner le projet**
```bash
git clone https://github.com/Ferolt/projet-esgi-final-laravel.git
cd projet-final-esgi
```

### 2️⃣ **Copier le fichier d'environnement et configurer**
```bash
cp .env.example .env
```
🔹 **(Optionnel)** Modifier le fichier `.env` selon les besoins (base de données, mail, etc.).

---

### 3️⃣ **Installer les dépendances (si `vendor/` n'existe pas encore)**  
```bash
docker run --rm -v $(pwd):/app composer install
```
Cela installe Composer **sans utiliser Sail** (car `vendor/` n'est pas encore présent).

---

### 4️⃣ **Démarrer Sail et les services Docker**
```bash
./vendor/bin/sail up -d
```
Cela lance les conteneurs en arrière-plan.

---

### 5️⃣ **Installation des dépendances PHP et Node.js**
```bash
# Installer les dépendances Laravel via Sail
./vendor/bin/sail composer install

# Installer les dépendances front-end (nécessaire pour Breeze)
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

---

### 6️⃣ **Générer la clé de l'application et configurer la base de données**
```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```

---

## 🌍 Accès à l'application

- **Frontend** : [http://localhost](http://localhost)
- **PHPMyAdmin** (si configuré) : [http://localhost:8080](http://localhost:8080)

---

## 📌 Commandes utiles

🔹 **Arrêter les conteneurs** :
```bash
./vendor/bin/sail down
```

🔹 **Accéder au terminal du conteneur Laravel** :
```bash
./vendor/bin/sail shell
```

🔹 **Lancer les tests** :
```bash
./vendor/bin/sail artisan test
```

🔹 **Regénérer les assets après modification** :
```bash
./vendor/bin/sail npm run dev
```

🔹 **Compiler les assets en production** :
```bash
./vendor/bin/sail npm run build
```

**crée un lien symbolique vers le dossier storage/public (photo)**
```bash
./vendor/bin/sail artisan storage:link
```
---

## 🔧 Dépannage

❌ **Problème de permissions (`vendor` ou `storage/framework/cache` inaccessible)** :
```bash
sudo chown -R $USER:$USER .
```

❌ **Dépendances Composer non installées correctement** :
```bash
./vendor/bin/sail composer update
```

❌ **Docker ne fonctionne pas** :
Vérifier que Docker est bien démarré avant d'exécuter `sail up`.

---

## 📢 Contributions

1. Créez une branche : `git checkout -b feature/ma-fonctionnalité`
2. Faites vos modifications et committez : `git commit -m "Ajout d'une fonctionnalité"`
3. Poussez votre branche : `git push origin feature/ma-fonctionnalité`
4. Ouvrez une **Pull Request** sur GitHub.

---

## 📜 Licence

Ce projet est sous licence **MIT**. Voir le fichier `LICENSE` pour plus de détails.

## PHP my admin

Au cas ou votre phpmyadmin ne marche pas essayer cette commande `docker network create sail`
