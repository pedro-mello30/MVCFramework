
// CUSTOM JAVASCRIPT
// ----------------------------------------------------------------------------

"use strict";

// DOM //
$(document).ready(function(){

    $("#data-table").DataTable({
        language: {
            search:        "Buscar:",
            lengthMenu:    "Exibir _MENU_ itens",
            info:          "Exibindo _START_ de _TOTAL_ itens",
            zeroRecords:   "Nenhum item encontrado",
            emptyTable:    "Nenhum item encontrado",
            paginate: {
                first:      "Primeira",
                previous:   "Anterior",
                next:       "Próxima",
                last:       "Última"
            },
        }
    });

    // Stuff to do as soon as the DOM is ready;


});


function estouTestando(a) {
    console.log("hi");
}
