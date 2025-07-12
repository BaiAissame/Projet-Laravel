#!/bin/bash

echo "🏠 Test de la nouvelle page d'accueil..."

# Compiler les assets
echo "🔨 Compilation des assets..."
./vendor/bin/sail npm run build

# Vider les caches
echo "🧹 Vidage des caches..."
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan view:clear
./vendor/bin/sail artisan config:clear

# Optimiser l'application
echo "⚡ Optimisation..."
./vendor/bin/sail artisan optimize

echo "✅ Page d'accueil prête !"
echo "🌐 Accédez à : http://localhost"
echo ""
echo "📋 Fonctionnalités de la nouvelle page d'accueil :"
echo "   ✅ Design moderne avec gradients"
echo "   ✅ Navigation avec boutons Connexion/Inscription"
echo "   ✅ Section Hero avec CTA"
echo "   ✅ Section Features avec 6 fonctionnalités"
echo "   ✅ Section CTA finale"
echo "   ✅ Footer complet"
echo "   ✅ Mode sombre/clair"
echo "   ✅ Animations fluides"
echo "   ✅ Responsive design" 