# Description

Ce projet d'E-commerce codé en PHP est un exemple parfait de la puissance de ce langage de programmation. Ce projet est conçu pour être une plateforme de vente en ligne complète, offrant des fonctionnalités avancées pour les acheteurs et les vendeurs.

La plateforme est équipée d'une interface utilisateur conviviable et facile à naviguer, avec une mise en page intuitive qui permet aux visiteurs de trouver rapidement ce qu'ils cherchent, grace à un UX/UI réfléchi.

La sécurité des utilisateur et du vendeur est une préoccupation majeure pour tout projet d'E-commerce, et ce projet codé en PHP vanilla à été conçu avec cette préoccupation en tête, avec des techniques de sécurités à la pointe de la technologie.

## Installation

Il faut installer un logiciel permettant de créer un serveur WEB en local.
Le nom du logiciel dépend de votre OS
→ XAMPP pour Windows (Version PHP 8)
→ MAMP pour MAC
→ LAMP pour Linux
Ubuntu/Debian : sudo apt-get install lamp-server^
Arch/Manjaro : sudo pacman -S httpd php php-apache mysql phpmyadmin
(Toutes distrib) à la fin, faites :
sudo systemctl enable —now httpd
sudo systemctl enable —now mysql

Pensez bien à configurer votre XAMPP sur PHP 8 si jamais. MAMP par exemple utilise PHP 7.4 par défaut
Une fois votre XAMPP d'installé, lancez votre serveur Apache et SQL (sur MAMP il suffit d'appuyer sur start ça lance tout en même temps).
Votre environnement de travail
Bon, votre XAMPP a bien travaillé, il a même créé un dossier de travail normalement.
Sur XAMPP, il suffit de cliquer sur le bouton 📂 Explorer
Sur MAMP, le dossier est localisé dans /Applications/MAMP
Sur LAMP, le dossier est situé dans /var/www
Rendez-vous dans le dossier htdocs, c'est ici que XAMPP va aller chercher vos site web lorsque vous lui demanderez.
Dans ce dossier, il suffit de cloner le repo:
git clone <https://ytrack.learn.ynov.com/git/AMOISKA/php_exam>

Maintenant il faut créer la base de données, pour cela il faut se rendre sur ce lien :
<http://localhost/phpmyadmin>
Appuyez sur Nouvelle base de données et donnez lui le nom de php_exam_db et appuyez sur créer
Ensuite cliquez sur votre nouvelle db et appuyez sur importer et choisissez le fichier .sql du repo cloné
Maintenant rendez-vous sur <http://localhost/php_exam> et vous pouvez maintenant utiliser notre site ! :)

## Membres du projet

GARCIA Fabien, MENARD Raphaël, MOISKA Aymeric

### Nous supporter

Si vous avez été impressionné par la richesse des fonctionnalités et la sécurité de ce projet d'E-commerce codé en PHP, je vous encourage vivement à soutenir financièrement les développeurs derrière ce projet en faisant un don sur leur compte PayPal: <https://paypal.me/RMenard908>

### Licence sltar

```text
© 2014 Enno Boland <g s01 de>

Permission is hereby granted, free of charge, to any person obtaining a
copy of this software and associated documentation files (the "Software"),
to deal in the Software without restriction, including without limitation
the rights to use, copy, modify, merge, publish, distribute, sublicense,
and/or sell copies of the Software, and to permit persons to whom the 
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in 
all copies or substantial portions of the Software. 

You also agree to give me your first child to immolate it to the devil when
the summer solstice has a full moon.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  IN NO EVENT SHALL 
THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING 
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER 
DEALINGS IN THE SOFTWARE.
```
