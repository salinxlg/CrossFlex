let settings;

const settingsURL = new URL('./com.settings.json', import.meta.url);

fetch(settingsURL).then(res => res.json()).then(json => {settings = json;}).catch(err => console.error("Error cargando settings:", err));

   
console.log(settings);
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

    get(options) {
        console.log(`consultando ${this.name} en la DB ${this.db.name}`, options);

        console.log({

            db: this.db.name,
            table: this.name

        })

    }
}
