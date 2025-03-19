# WPEnhancer - WordPress Snippets & Shortcodes Plugin

## 📌 Übersicht
WPEnhancer ist ein WordPress-Plugin, das benutzerdefinierte PHP-Snippets und Shortcodes bereitstellt. Zusätzlich bietet es eine **Kompatibilitätsprüfung** für Elementor und Simple Membership, um sicherzustellen, dass das Plugin mit diesen Erweiterungen korrekt funktioniert.

## ✨ Funktionen
- ✅ Benutzerdefinierte **PHP-Snippets**
- ✅ Eigene **Shortcodes**
- ✅ **Admin-Menü** zur Verwaltung der Snippets & Shortcodes
- ✅ **Kompatibilitätsprüfung** für **Elementor** und **Simple Membership**

## 🛠 Installation
1. Lade das Plugin als ZIP-Datei herunter oder klone das Repository:
   ```bash
   git clone https://github.com/dein-username/WPEnhancer.git
   ```
2. Kopiere den **WPEnhancer**-Ordner nach `wp-content/plugins/`.
3. Aktiviere das Plugin im WordPress-Adminbereich unter **Plugins**.

## 🚀 Verwendung
### **Shortcodes nutzen**
Füge die folgenden Shortcodes in Beiträge oder Seiten ein:
```html
[my_shortcode] <!-- Gibt HTML-Text aus -->
```

### **PHP-Snippets nutzen**
Du kannst die folgenden PHP-Funktionen in deinen Themes oder Plugins aufrufen:
```php
<?php echo WPEnhancer::my_custom_snippet(); ?>
```

## 🔍 Kompatibilitätsprüfung
Das Plugin überprüft automatisch, ob Elementor und Simple Membership installiert und kompatibel sind.
- **Elementor**: Mindestversion **3.0.0**
- **Simple Membership**: Mindestversion **4.0.0**

Wenn ein Plugin fehlt oder nicht kompatibel ist, wird eine **Warnung** im WPEnhancer-Adminbereich angezeigt.

## 📝 Entwicklung
Falls du das Plugin anpassen möchtest:
1. Öffne `class-wpenhancer.php` für die Hauptlogik.
2. Ergänze eigene Snippets in `includes/`.
3. Prüfe neue Funktionen mit `debug.log`.

## 🎯 To-Do / Verbesserungen
- [ ] Weitere Shortcodes und Snippets hinzufügen
- [ ] Unterstützung für weitere Plugins (z. B. WooCommerce)
- [ ] Benutzerdefinierte Snippets im WP-Admin verwalten

## 🤝 Mitwirken
Falls du Fehler findest oder das Plugin erweitern möchtest, öffne einfach einen **Pull-Request** oder erstelle ein **Issue**.

**Autor**: Jonas Zauner  
**Website**: [swissbarbecue.ch](https://swissbarbecue.ch)  
**Lizenz**: MIT

