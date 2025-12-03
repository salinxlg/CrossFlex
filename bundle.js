let settings;
let route;

const settingsURL = new URL('./com.settings.json', import.meta.url);

async function loadSettings() {const res = await fetch(settingsURL);settings = await res.json(); route = res.mode === 'remote' ? res.location : new URL('./bin/com.execute.php', import.meta.url) }await loadSettings();

export const crossflex = {
    databases: {},
    tables: {},

    registerDB(name, instance) {
        this.databases[name] = instance;
    },

    registerTable(name, instance) {
        this.tables[name] = instance;
        this[name] = instance;
    }
};

export class DatabaseConnection {
    constructor(name) {
        this.name = name;
        crossflex.registerDB(name, this);
    }
}

export class Table {

    constructor(tableName, dbConnection) {
        this.name = tableName;
        this.db = dbConnection;

        crossflex.registerTable(tableName, this);
    }

/**
 * Obtiene uno o varios registros de la tabla según las condiciones especificadas.
 * @param {Object} options
 * @param {Object} [options.where] 
 * @param {string} [options.where.column]
 * @param {string} [options.where.operator]
 * @param {string} [options.where.value]
 * @param {string[]|string} [options.select]
 * @param {number} [options.limit]
 * @param {Object} [options.order]
 * @param {string} [options.order.column]
 * @param {"ASC"|"DESC"} options.order.data - Tipo de orden.
 * @example
 * const users = await users.get({
 *     select: ["name", "position"],
 *     where: { column: "name", value: "Alejandro Salinas"},
 *     order: { column: "name", data: "ASC" },
 *     limit: 1
 * });
 *
 * // Ejemplo obteniendo todos los registros sin límite ni orden
 * const all = await users.get({
 *     select: ["name", "position"]
 * });
 */


async get({where = null, select = null, limit = null, order = null} = {}) {

    const payload = {action: "get", db: this.db.name, table: this.name, where, select, limit, order};

    if (payload.where && !Array.isArray(payload.where)) {
        payload.where = [payload.where];
    }

    const res = await fetch(route, {
        method: settings.method,
        headers: {"content-type": "application/x-www-form-urlencoded"},
        body: `action=${encodeURIComponent(payload.action)}&database=${encodeURIComponent(payload.db)}&table=${encodeURIComponent(payload.table)}&columns=${payload.select.join(', ')}&condition=${encodeURIComponent(JSON.stringify(payload.where))}&order=${encodeURIComponent(payload.order != null ? `ORDER BY ${payload.order.column} ${payload.order.data}`: '')}&limit=${encodeURIComponent(payload.limit != null ? `LIMIT ${payload.limit}` : '')}`
    });

    const data = await res.json();
    return data;

}


/**
 * Crea un nuevo registro en la tabla con los valores especificados.
 *
 * @param {Object} options - Configuración del registro a crear.
 * @param {Object} options.data - Objeto que contiene las columnas y valores del nuevo registro.
 * @example
 * const createUser = await users.new({
 *     data: {
 *         name: "Alejandro Salinas",
 *         age: 20,
 *         position: "Developer"
 *     }
 * });

 */


async new({ data = null } = {}) {

    if (!data || typeof data !== "object") {
        throw new Error("Es necesario enviar un objeto con las columnas y valores para crear un registro.");
    }

    const payload = {
        action: "new",
        db: this.db.name,
        table: this.name,
        data
    };

    const res = await fetch(route, {
        method: settings.method,
        headers: {
            "content-type": "application/x-www-form-urlencoded"
        },
        body:`action=${encodeURIComponent(payload.action)}&database=${encodeURIComponent(payload.db)}&table=${encodeURIComponent(payload.table)}&data=${encodeURIComponent(JSON.stringify(payload.data))}&condition=${encodeURIComponent('none')}`
    });

    const json = await res.json();
    return json;
}

/**
 * Cambia valores de uno o varios registros en la tabla.
 *
 * @param {Object} options - Configuración del cambio.
 * @param {string} options.data - Objeto con las columnas y sus nuevos valores.
 * @param {Object} [options.where] 
 * @param {string} [options.where.column]
 * @param {string} [options.where.value]
 * @param {string} [options.order.column]
 * @param {string} [options.order.data]
 *
 * @example
 *const update = await users.change({
 *    data: { name: "Alejandro", position: "Developer" },
 *    where: { column: "name", value: "Roger" }
*});
 */


async change({ data = null, where = null } = {}) {
    if (!data || !where) throw new Error("Es necesario especificar la columna y el where para actualizar un registro.");

    const payload = {
        action: "change",
        db: this.db.name,
        table: this.name,
        data,
        where
    };

    if (payload.where && !Array.isArray(payload.where)) {
        payload.where = [payload.where];
    }

    const res = await fetch(route, {
        method: settings.method,
        headers: { "content-type": "application/x-www-form-urlencoded" },
        body: `action=${encodeURIComponent(payload.action)}&database=${encodeURIComponent(payload.db)}&table=${encodeURIComponent(payload.table)}&data=${encodeURIComponent(JSON.stringify(payload.data))}&condition=${encodeURIComponent(JSON.stringify(payload.where))}`
    });

    const result = await res.json();
    return result;
}


/**
 * Elimina uno o varios registros de la tabla según la condición especificada.
 *
 * @param {Object} options
 * @param {Object} [options.where] 
 * @param {string} [options.where.column]
 * @param {string} [options.where.operator]
 * @param {string} [options.where.value]
 * @param {string[]|string} [options.select]
 * @param {number} [options.limit]
 * @param {Object} [options.order]
 * @param {string} [options.order.column]
 * @param {string} [options.order.data]

 *
 * @param {boolean} [options.permission=false] - Permiso obligatorio cuando `safeMode` está habilitado.
 * Si `safeMode: true` en la configuración global, es necesario enviar `permission: true` para ejecutar la eliminación.

 * @example
 * const removeUser = await users.remove({
 *     where: { column: "name", value: "Alejandro Salinas" },
 *     permission: true
 * });

 */


async remove({ where = null, permission = false } = {}) {
    if (!where) throw new Error("Es necesario especificar un where para eliminar un registro.");

    if(settings.safeMode == true && permission == false){

        return {execute: false, error: 'Safe Mode está activo. Para permitir la eliminación bajo esta configuración, es necesario enviar permission: true en la solicitud.'}

    }else{

         const payload = {
            action: "remove",
            db: this.db.name,
            table: this.name,
            where
        };

        if (!Array.isArray(payload.where)) {
            payload.where = [payload.where];
        }

        const res = await fetch(route, {
            method: settings.method,
            headers: { "content-type": "application/x-www-form-urlencoded" },
            body: `action=${encodeURIComponent(payload.action)}&database=${encodeURIComponent(payload.db)}&table=${encodeURIComponent(payload.table)}&condition=${encodeURIComponent(JSON.stringify(payload.where))}`
        });

        const result = await res.json();
        return result;

    }
   
}


}
