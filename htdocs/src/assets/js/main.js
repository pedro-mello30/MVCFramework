
// CUSTOM JAVASCRIPT
// ----------------------------------------------------------------------------
"use strict";
var $ = require( "jquery" );
require( 'datatables.net-bs4' )($);




// DOM //
$(document).ready(function(){
    // Stuff to do as soon as the DOM is ready;
});



$('.data-table').DataTable({
    language: {
        search:         "Buscar:",
        lengthMenu:    "Exibir _MENU_ itens",
        info:           "Exibindo _START_ de _TOTAL_ itens",
        zeroRecords:    "Nenhum item encontrado",
        emptyTable:    " Nenhum item encontrado",
        paginate: {
            first:      "Primeira",
            previous:   "Anterior",
            next:       "Próxima",
            last:       "Última"
        },
    }
});


function estouTestando(a) {
    console.log("hi");
}
