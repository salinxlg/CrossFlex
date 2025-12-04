
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

<br>

## Archivo de configuración de conexión

Para mantener la seguridad y privacidad las credenciales de acceso a las bases de datos y las tablas se alojan en el archivo ` com.connection.php `, dentro de la carpeta ` Bin `, deben ser reemplazadas antes de ejecutar el script para evitar errores de conexión.

``` php

    $host = "host.example.com";
    $username = "salinxlg";
    $password = "ABC123";


```

<br><br>

## Conexión con base de datos y con tablas

CrossFlex admite múltiples conexiones de bases de datos, la forma de establecer conexión con una base de datos es:

``` javascript

  const nombre = new DatabaseConnection("nombre_de_la_db");

```

los parámetros esperados son un ` string ` con el nombre de la base de datos.

<br>

Asímismo admite múltiples conexiónes a diferentes tablas simultaneamente de la siguiente forma:

``` javascript

  const tabla = new Table("nombre_de_la_tabla", base_de_datos);

```

los parámetros esperados aqui son ` string ` ` instance ` con el nombre de la tabla y la conexión a la base de datos.

<br>

## Ejemplo de conexión:

``` javascript

  const store = new DatabaseConnection("store");
  const stock = new Table("stock", store)

```

<br>

## Formas de uso y métodos

CrossFlex cuenta con 4 funcionalidades principales para gestion de registros: ` new `, `get`, `change` y `remove`, a continuación se especifican sus usos, parámetros e información adicional:

<br>

## Método New
El método `new` permite crear registros en una tabla específica de la base de datos.
Se utiliza para insertar nuevos datos, especificando los valores de cada columna que se desea agregar. Este método recibe un objeto con los datos a insertar y se asegura de manejar la información de forma segura, evitando conflictos o errores de conexión.

La estructura de `new` es:

``` javascript

  stock.new({data: {

        column: "value",
        column: "value",
        column: "value",

    }})

```

## Ejemplo de uso de ` new `

``` javascript

  //Se debe especificar la tabla donde se trabajará y crear el objeto data, luego asignar el nombre del la columna y su valor

    const crear = await stock.new({data: {

      //Columna
        SKU: "ABC123", //Valor
        ProductName: 'Airpods Pro',
        Price: 250,
        Currency: "$",

    }})

    console.log(crear) // salida


```

CrossFlex funciona de manera asíncrona, por lo que se debe usar `await`, o `.then()` para obtener el estado de la query

Al resolverse la promesa, en caso de una adición exitosa deberías obtener un json asi:

``` json

{"execute": true, "insert_id": 0}

```

en caso de error deberías ver: 

``` json

{"execute": false, "error": "Información sobre el error"}
```

la forma de saber el estado de la ejecución es de la siguiente:

``` javascript

const crear = await stock.new({data: {

  SKU: "ABC123",
  ProductName: 'Airpods Pro',
  Price: 250,
  Currency: "$",

}})

const status = crear.execute;

if(status){

  console.log("ok");

}else{

  console.log("!ok")

}

```


<br>

## Método Get
El método `get` permite consultar registros de una tabla.
Se pueden especificar condiciones (`where`), columnas a seleccionar (`select`), límite de resultados (`limit`) y orden (`order`). Si no se especifica ninguna condición, el método devuelve todos los registros disponibles. Esta función devuelve los datos en formato JSON listo para ser procesado por la aplicación.

La estructura de `get` es:

``` javascript

    stock.get({
        select: ["column", "column"], 
        where: { column: "name", value: "value"},
        order: { column: "name", data: "ASC" },
        limit: 1
    });

```

## Ejemplo de uso de ` get `

``` javascript

    const get = stock.get({

      select: ["name", "position"],
      where: { column: "name", value: "Alejandro Salinas"},
      order: { column: "name", data: "ASC" },
      limit: 1

    });

    console.log(get) // salida


```

Este proceso obtiene únicamente las columnas solicitadas y aplica, si se especifican, las condiciones de filtrado, ordenamiento y límite en la consulta. Los parámetros `where`, `order` y `limit` son opcionales:

Si no se define `where`, se devuelven todos los registros disponibles.

Si no se establece `order`, no se aplicará ningún tipo de ordenamiento.

Si no se indica `limit`, la consulta retornará la totalidad de los registros coincidentes.

<br>

## Ejemplo para obtener todos los registros de la base de datos

``` javascript

    stock.get({

      select: ["name", "position"],

    });

    console.log(crear) // salida


```

## La forma de obtener el resultado de la solicitud es de la siguiente:

``` javascript

const get = await stock.get({

  select: ["name", "position"],

  });

const status = crear.execute;

if(status){

  const resultado = get.result.dato //en caso de ser un unico registro
  const resultado = get.result.forEach(element => {}); //en caso de ser un múltiples registros

  console.log(resultado);

}else{

  console.log("!ok")

}

```

Al resolverse la promesa, en caso de una salir todo deberías obtener un json asi:

``` json

{
  "execute":true,
  "result":[
      {"columna":"valor"},
      {"columna":"valor"}
      {"columna":"valor"},
    ]
        
}

```
si tu consulta tiene varias respuestas se devuelve un `array`, si es una sola se devuelve como objeto único.

en caso de error deberías ver: 

``` json

{"execute": false, "error": "Información sobre el error"}

```



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
