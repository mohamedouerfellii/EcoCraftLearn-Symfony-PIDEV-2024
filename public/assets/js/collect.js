$(document).ready(function() {
    $('#searchInput').on('input', function() {
        var searchTerm = $(this).val().trim();
        if (searchTerm.length > 0) {
            searchCollect(searchTerm);
        } else {

            $("#mainAllCollectsDiv").empty();
        }
    });
});

function searchCollect(searchTerm) {
    let url = "/searchCollects"; 
    $.ajax({
        type: "GET",
        url: url,
        data: { search: searchTerm },
        success: function (data) {
            if (data) {
                console.log(data);
                $("#mainAllCollectsDiv").empty(); 
                $.each(data, function (i, obj) {
                 
                    
                    let productHtml = `
                    <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Id complet: ${ obj.id }</h5>
                        
                       <p <h7 class="card-subtitle mb-2 text-muted">Nom de votre ptscollecte: ${ obj.name }</h7></p>
                        
                        <p class="card-subtitle mb-2 text-muted">Type de matériel: ${ obj.materialType }</p>
                        <p class="card-subtitle mb-2 text-muted">Quantité : ${ response.quantity }</p>
                
                         <p class="card-text">Date de collect: ${ obj.collectDate|date('d-m-Y') }</p>
                        
                        <div class="btn-group" role="group" aria-label="Actions">
                            <a class="btn cur-p btn-danger" href="{{ path('delete_adh', {'id': response.id}) }}">Supprimer</a>
                            <a class="btn cur-p btn-success" href="{{ path('update_collects', {'id': response.id}) }}">Mettre à jour</a>
                        </div>
                    </div>
                </div>
                    `;
                    $("#mainAllCollectsDiv").append(productHtml);
                });
            } else {
                console.log("Aucun Collect trouvé.");
            }
        },
        error: function (xhr, status, error) {
            console.log("Erreur lors de la recherche de produits : " + error);
        },
    });
}
