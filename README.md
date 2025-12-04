
<div align=center>

  <img src="docs/com.logo.png" name="exemple" style="width:80px; height:80px;">
  
</div>

<h1 align=center>CrossFlex</h1>
<div align=center>


<br>
<b>CrossFlex</b> es un  framework diseñado para facilitar las operaciones con MySQL desde proyectos JavaScript usando un ejecutor PHP robusto. Permite realizar consultas, inserciones, actualizaciones y eliminaciones mediante una sintaxis limpia y orientada al desarrollador. Incluye manejo de errores, validación interna y modo seguro (safeMode) para proteger datos en producción.<br>
Este proyecto fue creado por <a href="https://instagram.com/salinxlg">Alejandro Salinas</a>.<br>

<br>

</div>

<br><br>

## Inicio Rápido e instalación
Descarga de los archivos
```bash
npm install crossflex
```


##  Estructura y distribución del proyecto

```bash
CrossFlex/
├── bin/
│   ├── com.execute.php
│   └── com.connection.php
│
├── docs/
│   ├── com.logo.png
│   └── Sign.png
│
├── bundle.js                     //archivo principal
├── com.settings.json            // archivo de configuración
├── package.json
└──

```


## Ver información y versión de CrossFlex

Puedes ejecutar cualquiera de estos comandos para ver la versión o información de tu instalación

```bash

npm view crossflex

```

```bash

npm view crossflex version

```

## Importación en JavaScript

``` js

  import { DatabaseConnection, Table } from 'crossflex'

```
<br>

## Información y configuración de CrossFlex
A continuación se detallan las opciones y detalles para configurar
` CrossFlex `:

El ` com.settings.json ` ya está listo para funcionar, pero puedes modificar cualquier parte del archivo para adaptarlo a tus necesidades:

``` json
{

    "mode": "local",
    "location": "bin/com.execute.php",
    "method": "POST",
    "safeMode": true,
    "logs": {

        "enabled": false,
        "mode": "console"

    },

    "enveroiment": "development"


}
```

CrossFlex requiere ejecutarse en un servidor (Apache/NGINX).
Si trabajas desde un entorno local como Electron o Node, deberás servir el ejecutor PHP desde un servidor HTTPS, usar ` mode: "remote" ` y configurar CORS para permitir POST. Sin esto, las consultas no funcionarán.

Estos son los métodos aceptados para enviar las consultas

``` json

    "method": "POST",

```

``` json

    "method": "GET",

```


se recomienda más usar POST para mantener la información más segura.

<br>

` Safe Mode ` está diseñado para evitar la ejecución accidental de operaciones destructivas, como ` remove ` (DELETE).
Este modo acepta dos parámetros:

``` json

    "safeMode": true,

```

Eso significa que la protección está activa, y que se debe enviar una confirmación para eliminar los datos.

``` json

    "safeMode": false,

```

Esto significa que la protección está desactivada, y que se ejecutará remove sin necesidad de solicitar una confirmación.

## Información de versiones del proyecto:

<details>

  <summary>Versión de Node.js</summary>
  
  <br>
  
  Use Node.js ` v24.6.0 `

</details>

<details>
  <summary>Versión de npm</summary>
  
  <br>
  
  Use npm ` v11.5.1 `

</details>

<details>
  <summary>Versión de electron</summary>
  
  <br>
  
  Use electron ` v37.1.0 `

</details>

<br>


## Información final del proyecto:

- [Alejandro Salinas](https://instagram.com/salinxlg) es el creador de este proyecto
- La versión actual de LineX es `v7.1.0`
- Puedes instalar el proyecto clonando el repositorio o con `npm install dexline`
- © 2025 Alejandro Salinas, Dexly Studios, Todos los derechos Reservados.

<br><br>
<div align=center>

<img src="docs/Sign.png" width="205px">

</div>
