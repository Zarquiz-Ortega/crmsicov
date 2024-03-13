window.addEventListener('DOMContentLoaded', event => {
    const datatablesSimple = document.getElementById('simple_table');
    const datatablesSeguimiento = document.getElementById('simple_seguimientos');
    const datatablesVentas = document.getElementById('simple_Ventas');
    const datatablesCancelacion = document.getElementById('simple_Cancelacion');

    if (datatablesSimple) {
        new simpleDatatables.DataTable(datatablesSimple, { ...datatablesOption, language: datatablesLanguage });
    }

    if (datatablesSeguimiento) {
        new simpleDatatables.DataTable(datatablesSeguimiento, { ...datatablesOption, language: datatablesLanguage });
    }
    if (datatablesVentas) {
        new simpleDatatables.DataTable(datatablesVentas, { ...datatablesOption, language: datatablesLanguage });
    }

    if (datatablesCancelacion) {
        new simpleDatatables.DataTable(datatablesCancelacion, { ...datatablesOption, language: datatablesLanguage });
    }
});

const datatablesOption = {
    pageLength: 3,
    destroy: true,
};

const datatablesLanguage = {
    lengthMenu: "Mostrar _MENU_ registros por página",
    zeroRecords: "Cliente no encontrado",
    info: "Mostrando de _START_ a _END_ de un total de _TOTAL_ registros",
    infoFiltered: "(Filtrados desde un total de _MAX_ registros)",
    search: "Buscar:",
    loadingRecords: "Cargando..."
};

console.log("Opciones de DataTables:", datatablesOption);