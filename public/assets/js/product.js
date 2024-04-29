$(document).ready(function() {
    $('#searchInput').on('input', function() {
        var searchTerm = $(this).val().trim();
        if (searchTerm.length > 0) {
            searchProduct(searchTerm);
        } else {

            $("#mainAllProductDiv").empty();
        }
    });
});

function searchProduct(searchTerm) {
    let url = "/searchProducts"; 
    $.ajax({
        type: "GET",
        url: url,
        data: { search: searchTerm },
        success: function (data) {
            if (data) {
                console.log(data);
                $("#mainAllProductDiv").empty(); 
                $.each(data, function (i, obj) {
                    let imageSrc = "/contents/uploads/products/" + obj.image;
                    let productUrl = "/addevaluationproduct/" + obj.idproduct;
                    let buyNowUrl = "/buy_now/" + obj.idproduct;

                    let productHtml = `
                        <div class="col-lg-4 col-md-6">
                            <div class="single-courses">
                                <div class="courses-images">
                                    <a href="${productUrl}">
                                        <img src="${imageSrc}" alt="Courses" style="width:330px;height:300px;">
                                    </a>
                                </div>
                                <div class="courses-content">
                                    <h4 class="title"><a href="#">${obj.name}</a></h4>
                                    <div class="courses-meta">
                                        <span>Quantity : ${obj.quantite}</span>
                                    </div>
                                    <div class="courses-author">
                                        <div class="author">
                                            <div class="author-name"></div>
                                        </div>
                                        <div class="tag">
                                            <a href="${buyNowUrl}">Buy Now</a>
                                        </div>
                                    </div>
                                    <div class="courses-price-review">
                                        <div class="courses-price">
                                            <span class="sale-parice">${obj.price} DT</span>
                                        </div>
                                        <div class="courses-review">
                                            <span class="rating-count">4.9</span>
                                            <span class="rating-star">
                                                <span class="rating-bar" style="width: 80%;"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    $("#mainAllProductDiv").append(productHtml);
                });
            } else {
                console.log("Aucun produit trouvé.");
            }
        },
        error: function (xhr, status, error) {
            console.log("Erreur lors de la recherche de produits : " + error);
        },
    });
}

//filter product
$(document).ready(function() {
    // Lorsque vous cliquez sur le bouton "Newest"
    $('#newestTabBtn').click(function() {
        $('#newestTabBtn').addClass('active');
        $('#oldestTabBtn').removeClass('active');
        filterProduct('Newest');
    });

    // Lorsque vous cliquez sur le bouton "Oldest"
    $('#oldestTabBtn').click(function() {
        $('#oldestTabBtn').addClass('active');
        $('#newestTabBtn').removeClass('active');
        filterProduct('Oldest');
    });
});

function filterProduct(filter) {
    let url = "/filterProduct?filter=" + filter; // Inclure le paramètre filter dans l'URL
    $.ajax({
        type: "GET",
        url: url,
        success: function (data) {
            if (data) {
                console.log(data);
                $("#mainAllProductDiv").empty(); 
                $.each(data, function (i, obj) {
                    let imageSrc = "/contents/uploads/products/" + obj.image;
                    let productUrl = "/addevaluationproduct/" + obj.idproduct;
                    let buyNowUrl = "/buy_now/" + obj.idproduct;

                    let productHtml = `
                        <div class="col-lg-4 col-md-6">
                            <div class="single-courses">
                                <div class="courses-images">
                                    <a href="${productUrl}">
                                        <img src="${imageSrc}" alt="Courses" style="width:330px;height:300px;">
                                    </a>
                                </div>
                                <div class="courses-content">
                                    <h4 class="title"><a href="#">${obj.name}</a></h4>
                                    <div class="courses-meta">
                                        <span>Quantity : ${obj.quantite}</span>
                                    </div>
                                    <div class="courses-author">
                                        <div class="author">
                                            <div class="author-name"></div>
                                        </div>
                                        <div class="tag">
                                            <a href="${buyNowUrl}">Buy Now</a>
                                        </div>
                                    </div>
                                    <div class="courses-price-review">
                                        <div class="courses-price">
                                            <span class="sale-parice">${obj.price} DT</span>
                                        </div>
                                        <div class="courses-review">
                                            <span class="rating-count">4.9</span>
                                            <span class="rating-star">
                                                <span class="rating-bar" style="width: 80%;"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    $("#mainAllProductDiv").append(productHtml);
                });
            } else {
                console.log("Aucun produit trouvé.");
            }
        },
        error: function (xhr, status, error) {
            console.log("Erreur lors de la filter  de produits : " + error);
        },
    });
}