
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

## Comandos y forma de uso:
A continuación se detallan los comandos y salidas esperadas al usar
` CrossFlex `:

``` bash
npm install electron-builder -dev
```

El package.json ya está listo para empaquetar, puedes modificar esta información:

``` json
  "build": {

    "appId": "com.dexly.linex",
    "productName": "Dexly LineX PlayGround",
    "files": [

      "srx/**/*",
      "error/**/*",
      "assx/**/*",
      "public/**/*",
      "package.json"

    ],
    "win": {

      "target": "nsis",
      "icon": "assx/img/logo.png"

    }

  },
```

Es importante mencionar que electron, y electron-builder no pueden estar dentro de ` dependencies `, deben estar dentro de ` devDependencies ` de lo contrario no podrás compilar.

Cuando todo esté listo para compilar ejecutas:

```bash

npm run dist

```

esto creará una carpeta llamada dist en la raiz del proyecto, dentro estará el instalador de tu app, y tu app descomprimida, lista para ejecutar, solo te faltará firmar el exe, para eso puedes usar mi proyecto 
[`Dexly CA`](https://github.com/salinxlg/dexlyca):

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
