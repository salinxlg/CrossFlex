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


}
