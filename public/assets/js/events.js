// search event by title

$(document).ready(function() {
    $('#searchForm').submit(function(event) {
        event.preventDefault();
        var searchTerm = $('#searchInput').val();
        searchProduct(searchTerm);
    });





});
function searchProduct(search) {
    let url = "/searchevents"; 
    $.ajax({
        type: "GET",
        url: url,
        data: { search: search },
        success: function (data) {
            if (data) {
                console.log(data);
                $("#eventResults").empty(); 
                $.each(data, function (i, obj) {
                    let imageSrc = "/contents/uploads/events/" + obj.attachment;
                    let detailUrl = "/eventsDetailsfront/" + obj.idevent;
                    let authorImageSrc  = "/assets/images/author/author-01.jpg";


                    const startDate = new Date(obj.startdate);
                    const year = startDate.getFullYear();
                    const month = String(startDate.getMonth() + 1).padStart(2, '0'); 
                    const day = String(startDate.getDate()).padStart(2, '0'); 
                    const formattedDate = `${day}/${month}/${year}`;
                    
                    let eventHtml = `
                        <div class="col-lg-4 col-md-6">
                            <!-- Single Blog Start -->
                            <div class="single-blog">
                                <div class="blog-image">
                                    <a href=""><img src="${imageSrc}" alt="Blog" width="300" height="200"></a>
                                </div>
                                <div class="blog-content">
                                    <div class="blog-author">
                                        <div class="author">
                                            <div class="author-thumb">
                                                <a href="#"><img src="${authorImageSrc}" alt="Author"></a>
                                            </div>
                                            <div class="author-name">
                                                <a class="name" href="#">${obj.owner.firstname} ${obj.owner.lastname}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="title"><a href="">${obj.title}</a></h4>
                                    <div class="blog-meta">
                                        <span><i class="icofont-calendar"></i> ${formattedDate}</span>
                                        <span><i class=""></i>${ obj.placenbr }</span>
                                    </div>
                                    <a href="${detailUrl}" class="btn btn-secondary btn-hover-primary">Read More</a>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#eventResults').append(eventHtml);
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


// filter Newest et Oldest 
$(document).ready(function() {
    $('#newestTabBtn').click(function() {
        $('#newestTabBtn').addClass('active');
        $('#oldestTabBtn').removeClass('active');
        $('#ExpensiveTabBtn').removeClass('active');
        $('#ShippingTabBtn').removeClass('active');
        filterEvent('Newest');
    });

    $('#oldestTabBtn').click(function() {
        $('#oldestTabBtn').addClass('active');
        $('#newestTabBtn').removeClass('active'); 
        $('#ExpensiveTabBtn').removeClass('active');
        $('#ShippingTabBtn').removeClass('active');
        filterEvent('Oldest');
    });

    $('#ExpensiveTabBtn').click(function() {
        $('#oldestTabBtn').removeClass('active');
        $('#newestTabBtn').removeClass('active'); 
        $('#ExpensiveTabBtn').addClass('active');
        $('#ShippingTabBtn').removeClass('active');
        filterEvent('Expensive');
    });
    $('#ShippingTabBtn').click(function() {
        $('#oldestTabBtn').removeClass('active');
        $('#newestTabBtn').removeClass('active'); 
        $('#ExpensiveTabBtn').removeClass('active');
        $('#ShippingTabBtn').addClass('active');
        filterEvent('Shipping');
    });

 
});




function filterEvent(filter) {
    let url = "/filterEventBydate?filter=" + filter;
    $.ajax({
        type: "GET",
        url: url,
        success: function (data) {
            if (data) {
                console.log(data);
                $("#eventResults").empty(); 
                $.each(data, function (i, obj) {
                    let imageSrc = "/contents/uploads/events/" + obj.attachment;
                    let detailUrl = "/eventsDetailsfront/" + obj.idevent;
                    let authorImageSrc  = "/assets/images/author/author-01.jpg";


                    const startDate = new Date(obj.startdate);
                    const year = startDate.getFullYear();
                    const month = String(startDate.getMonth() + 1).padStart(2, '0'); 
                    const day = String(startDate.getDate()).padStart(2, '0'); 
                    const formattedDate = `${day}/${month}/${year}`;
                    
                    let eventHtml = `
                        <div class="col-lg-4 col-md-6">
                            <!-- Single Blog Start -->
                            <div class="single-blog">
                                <div class="blog-image">
                                    <a href=""><img src="${imageSrc}" alt="Blog" width="300" height="200"></a>
                                </div>
                                <div class="blog-content">
                                    <div class="blog-author">
                                        <div class="author">
                                            <div class="author-thumb">
                                                <a href="#"><img src="${authorImageSrc}" alt="Author"></a>
                                            </div>
                                            <div class="author-name">
                                                <a class="name" href="#">${obj.owner.firstname} ${obj.owner.lastname}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="title"><a href="">${obj.title}</a></h4>
                                    <div class="blog-meta">
                                        <span><i class="icofont-calendar"></i> ${formattedDate}</span>
                                        <span><i class=""></i>${ obj.placenbr }</span>
                                    </div>
                                    <a href="${detailUrl}" class="btn btn-secondary btn-hover-primary">Read More</a>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#eventResults').append(eventHtml);
                });
            } else {
                console.log("No event found.");
            }
        },
        error: function (xhr, status, error) {
            console.log("Error while fetching events: " + xhr.responseText);
        },
    });
}



//script map


