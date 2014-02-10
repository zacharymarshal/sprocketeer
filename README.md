Sprocketeer
===========

Sprocketeer is a PHP library for getting a simple list of files from a sprocket-like manifest.

===========

a.js.coffee
//= require b

# Dev

```
$this->addJsManifestFile('application');
 -> Parser->paths()->getJsUrls()
  -> array('lidsys/a.js', 'lidsys/b.js')
   -> View Helper
    -> <script src="asset.php?url=lidsys/a.js"> <script src="asset.php?url=lidsys/b.js">
```

# Prod

```
$this->addJsManifestFile('application');
 -> array('asset.php?url=application.js');


asset.php
 // We have a development option already in a config, reuse asset.php
 // Need an option to not parse directives while in dev mode, just return the file
 -> Parser->paths()->getJsPaths()
  -> array('/var/ww/html/assets/lidsys/a.js.coffee', '/var/ww/html/assets/lidsys/b.js.coffee')
   -> Assetic
```
