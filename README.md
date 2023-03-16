## Installation

### 1 : Composer
Ajouter le repo et l'installer

````yaml
#composer.json
"repositories": [
#...
  {
    "type": "git",
    "url": "https://github.com/webetdesign/cookie-consent-bundle.git"
  }
]
````

```bash
#terminal
composer require webetdesign/cookie-consent-bundle
```

### 2 : Activer le bundle
```php
<?php
// config/bundles.php
return [
// ...
    WebEtDesign\CookieConsentBundle\WDCookieConsentBundle::class => ['all' => true],
// ...
];
```

### 3 : Activer le routing
```yaml
# config/routes/wd_cookie_consent.yaml
wd_cookie_consent:
    resource: "@WDCookieConsentBundle/Resources/config/routing.yaml"
```

### 4 : Configuration des cookies
Créer un catégorie pour chaque demande de consentement désirer 
```yaml
#config/packages/wd_cookie_consent.yaml
wd_cookie_consent:
    categories:
        - 'analytics'
```

### 5 : Activer ESI
````yaml
#config/packages/framework.yaml
framework:
  #...
    esi: true
````
### 6 : Assets configuration
*Pour ajouter des assets en admin vous appelez vos assets via  extra_javascripts et extra_stylesheets dans config/packages/sonata/sonata_admin.yaml  
Il faut faire pareil mais en front dans notre application.*

*Pour cela, il faut d'abord configurer l'entrée webpack*

```yaml
#config/packages/assets.yaml
framework:
  assets:
    packages:
     #...
      cookie:
        json_manifest_path: '%kernel.project_dir%/public/bundles/wdcookieconsent/build/manifest.json'
```

````yaml
#config/packages/webpack_encore.yaml
webpack_encore:
  builds:
    #...
    cookie: '%kernel.project_dir%/public/bundles/wdcookieconsent/build'
````

### Assets implémentation

````php
//templates/base.html.twig
//...
{% block stylesheets %}
    {% for file in encore_entry_css_files('cookie', 'cookie') %}
        <link rel="stylesheet" href="{{ preload(file, { as: 'style', importance: 'high' }) }}">
    {% endfor %}
    {% for file in encore_entry_css_files('base', 'main') %}
        <link rel="stylesheet" href="{{ preload(file, { as: 'style', importance: 'high' }) }}">
    {% endfor %}
{% endblock %}
//...
{% block javascripts %}
    {{ encore_entry_script_tags('cookie', null, 'cookie') }}
    {{ encore_entry_script_tags('base', null, 'main') }}
{% endblock %}
````

### Rendu
Charger le module de cookie via render_esi (pour ne pas mettre en cache ) 

```php
//templates/base.html.twig
{{ render_esi(path('wd_cookie_consent.show', { 'locale' : 'en' })) }}
```

### Cookies
Lorsqu'un utilisateur soumet le formulaire, les préférences sont enregistrées sous forme de cookies.  
Les cookies ont une durée de vie de 1 an.  
Les cookies suivants sont enregistrés :  
- **Cookie_Consent**: date de soumission
- **Cookie_Consent_Key**: Clé générée en tant qu'identifiant du consentement de cookie soumis de l'utilisateur
- **Cookie_Category_[CATEGORY]**: valeur (*true* ou *false*)

### Logging
RGPD exige que toutes les préférences de cookies des utilisateurs soient explicables par les webmasters.
Pour cela, nous enregistrons toutes les préférences de cookies dans la base de données.
Les adresses IP sont anonymisées.

![Database logging](https://raw.githubusercontent.com/WebEtDesign/cookie-consent-bundle/master/Resources/doc/log.png)



### TwigExtension
Les fonctions TwigExtension suivantes sont disponibles :

**WDCookieConsent_isCategoryAllowedByUser**
vérifier si l'utilisateur a donné son autorisation pour une categorie de cookie
```twig
{% if WDCookieConsent_isCategoryAllowedByUser('analytics') == true %}
    ...
{% endif %}
```

**WDCookieConsent_isCookieConsentSavedByUser**
vérifier si l'utilisateur a enregistré des préférences
```twig
{% if WDCookieConsent_isCookieConsentSavedByUser() == true %}
    ...
{% endif %}
```


## Customization
### Categories
Vous pouvez ajouter ou supprimer n'importe quelle catégorie en modifiant la configuration et en **vous assurant que des traductions sont disponibles** pour ces catégories.
```yaml
#config/packages/wd_cookie_consent.yaml
wd_cookie_consent:
    categories:
        - 'analytics'
        - 'youtube'
        - 'facebook'
```
### Translations

Pour chaque catégorie crée, ajouter un block de catégorie dans le fichier de trad

````yaml
#translations/WDCookieConsentBundle.fr.yml
wd_cookie_consent:
  analytics: #catégorie
    title: 'Google Analytics'
    description: "Permet d'analyser les statistiques de consultation de notre site"
    link: "https://support.google.com/analytics/answer/6004245"
    link_label: "En savoir plus"
````

### Styling
Pour changer la couleur principale du module, surcharger la varriable css *--cookie-primary*

````scss
:root {
    --cookie-primary: #00abdf;
}
````
### Events
Si un cookie est accepté un événement est automatiquement envoyé à googleTagManager :
````js
dataLayer.push({
  event: `rgpd_activate_${cat}`
});
````
il faut donc avoir préalablement installé GTM pour pouvoir gérer ce qu'il se passe une fois le consentement donné.
