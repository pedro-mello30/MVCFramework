
// CUSTOM JAVASCRIPT
// ----------------------------------------------------------------------------
"use strict";

// DOM //
$(document).ready(function(){
    // Stuff to do as soon as the DOM is ready;
});

// $('.data-table').DataTable({
//     language: {
//         search:         "Buscar:",
//         lengthMenu:    "Exibir _MENU_ itens",
//         info:           "Exibindo _START_ de _TOTAL_ itens",
//         zeroRecords:    "Nenhum item encontrado",
//         emptyTable:    " Nenhum item encontrado",
//         paginate: {
//             first:      "Primeira",
//             previous:   "Anterior",
//             next:       "Próxima",
//             last:       "Última"
//         },
//     }
// });

function del (id)
{
    if(confirm('Tem certeza que deseja excluir?')) {
        console.log($("#" + id).attr("data"));
        window.location.href = $("#" + id).attr("data");
    }
}

function estouTestando(a) {
    console.log("hi");
}
