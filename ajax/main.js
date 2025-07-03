// main.js

const SERVERURL = 'http://localhost/wispManager/';
//array de js
const scriptsToLoad = [
    'redireccionar_dashboard.js',
    'usuario.js',
    'cliente.js',
    'plan.js',
    'servicio.js',
    'conexion.js',
    'tipo_planes.js',
    'tipo_servicio.js',
    'pago.js',
    'facturas.js',
    'empresa.js',
    'apertura_caja.js',
    'cierre_caja.js',
    'verificarEstadoCaja.js',
];


function loadScripts(scripts, callback) {
    let loadedCount = 0;

    scripts.forEach(scriptName => {
        const script = document.createElement('script');
        script.src = SERVERURL + 'ajax/' + scriptName;
        script.onload = () => {
            loadedCount++;
            if (loadedCount === scripts.length && typeof callback === 'function') {
                callback();
            }
        };
        script.onerror = () => {
            console.error(`Error loading script: ${scriptName}`);
        };
        document.head.appendChild(script);
    });
}

// Usas la función para cargar todos los scripts
loadScripts(scriptsToLoad, () => {
    console.log('Todos los scripts cargados');
});
