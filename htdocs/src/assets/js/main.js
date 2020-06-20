//
// CUSTOM JAVASCRIPT
// ----------------------------------------------------------------------------

"use strict";

$(document).ready(function(){
    //
    // // Accessibility - WAI-ARAI Roles
    // $("nav").attr("role","navigation");
    // $("nav ul li a").attr("role","menuitem");
    // $("#header").attr("role","banner");
    // $("#footer").attr("role","contentinfo");
    // $("section").attr("role","region");
    // $(".content").attr("role","main");
    // $(".sidebar").attr("role","complementary");
    // $(".alert").attr("role","alert");
    // $("a.btn").attr("role","button");
    // $("details, figure").attr("role", "group");
    // $(".tabs").attr("role","tablist");
    // $(".search-form").attr("role","search");
    //

    $("#data-table").DataTable({
        language: {
            search:         "Buscar:",
            lengthMenu:    "Exibir _MENU_ itens",
            info:           "Exibindo _START_ de _TOTAL_ itens",
            zeroRecords:    "Nenhum item encontrado",
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
    //


});
