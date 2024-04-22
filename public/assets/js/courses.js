// Courses back
function filterCourse(filter) {  
    let url = $("#selectFilter").data("url");
    $.ajax({
        type: "GET",
        url: url,
        data: { filter: filter },

        success: function (courses) {
        if (courses) {
            console.log(courses);
            $("#mainAllCourseDiv").empty();
                let courseDetailUrl = "/tutorCourseDetails";

                $.each(JSON.parse(courses), function (i, obj) {           
                let imageSrc = "../../contents/uploads/courses/" + obj.image;
                let courseUrl = courseDetailUrl + obj.idcourse;
                // course img
                let $courseImgDiv = $("<div></div>").addClass("item-thumb");
                let $courseImgAL = $("<a></a>").attr("href", courseUrl);
                let $courseImg = $("<img />").attr("src",imageSrc)
                $courseImgAL.append($courseImg);
                $courseImgDiv.append($courseImgAL);
                // course title
                let $courseTitleDiv = $("<div></div>").addClass("content-title");
                let $courseTitleH = $("<h3></h3>");
                let $courseTitleAL = $("<a>"+obj.title+"</a>").attr("href", courseUrl);
                $courseTitleH.append($courseTitleAL);
                $courseTitleDiv.append($courseTitleH);
                // course posted date
                let $coursePostedDateDiv = $("<div></div>").addClass("content-title");
                let $coursePostedDateSpan = $("<span>"+obj.posteddate+"</span>").addClass("title");
                $coursePostedDateDiv.append($coursePostedDateSpan);
                // other course details
                let $parentDiv = $("<div></div>").addClass("content-wrapper");
                // price
                let $priceDiv = $("<div></div>").addClass("content-box");
                let $priceP = $("<p>Price</p>");
                let $priceSpan = $("<span>"+obj.price+" TND</span>").addClass("count");
                $priceP.append($priceSpan);
                $priceDiv.append($priceP);
                // NBR Registered
                let $nbrRegisteredDiv = $("<div></div>").addClass("content-box");
                let $nbrRegisteredP = $("<p>Registered</p>");
                let $nbrRegisteredSpan = $("<span>"+obj.nbrregistred+"</span>").addClass("count");
                $nbrRegisteredDiv.append($nbrRegisteredP);
                $nbrRegisteredDiv.append($nbrRegisteredSpan);
                // rating
                let $ratingDiv = $("<div></div>").addClass("content-box");
                let $ratingP = $("<p>Course Rating</p>");
                let $ratingSpan = $("<span>"+obj.rate+"</span>").addClass("count");
                let $ratingBarStar = $("<span></span>").addClass("rating-star");
                let $ratingBarLine = $("<span>"+obj.rate+"</span>").addClass("rating-bar");
                let styleBR = "width: "+(obj.rate/5)*100+"%;"
                $ratingBarLine.attr("style", styleBR);
                $ratingBarStar.append($ratingBarLine);
                $ratingSpan.append($ratingBarStar);
                $ratingDiv.append($ratingP);
                $ratingDiv.append($ratingSpan);
                // append all to other details div
                $parentDiv.append($priceDiv);
                $parentDiv.append($nbrRegisteredDiv);
                $parentDiv.append($ratingDiv);
                // append all to main div
                let $mainDiv = $("<div></div>").addClass("courses-item");
                $mainDiv.append($courseImgDiv);
                $mainDiv.append($courseTitleDiv);
                $mainDiv.append($coursePostedDateDiv);
                $mainDiv.append($parentDiv);
                $("#mainAllCourseDiv").append($mainDiv);
                });
            }
            },
        error: function (xhr, status, error) {
        console.log("Error: " + error);
        },
    });
}

function searchCourse(search){
    let url = $("#searchCourse").data("url");
    $.ajax({
        type: "GET",
        url: url,
        data: { search: search },

        success: function (courses) {
            if (courses) {
                console.log(courses);
                $("#mainAllCourseDiv").empty();
                    let courseDetailUrl = "/tutorCourseDetails";

                    $.each(JSON.parse(courses), function (i, obj) {           
                    let imageSrc = "../../contents/uploads/courses/" + obj.image;
                    let courseUrl = courseDetailUrl + obj.idcourse;
                    // course img
                    let $courseImgDiv = $("<div></div>").addClass("item-thumb");
                    let $courseImgAL = $("<a></a>").attr("href", courseUrl);
                    let $courseImg = $("<img />").attr("src",imageSrc)
                    $courseImgAL.append($courseImg);
                    $courseImgDiv.append($courseImgAL);
                    // course title
                    let $courseTitleDiv = $("<div></div>").addClass("content-title");
                    let $courseTitleH = $("<h3></h3>");
                    let $courseTitleAL = $("<a>"+obj.title+"</a>").attr("href", courseUrl);
                    $courseTitleH.append($courseTitleAL);
                    $courseTitleDiv.append($courseTitleH);
                    // course posted date
                    let $coursePostedDateDiv = $("<div></div>").addClass("content-title");
                    let $coursePostedDateSpan = $("<span>"+obj.posteddate+"</span>").addClass("title");
                    $coursePostedDateDiv.append($coursePostedDateSpan);
                    // other course details
                    let $parentDiv = $("<div></div>").addClass("content-wrapper");
                    // price
                    let $priceDiv = $("<div></div>").addClass("content-box");
                    let $priceP = $("<p>Price</p>");
                    let $priceSpan = $("<span>"+obj.price+" TND</span>").addClass("count");
                    $priceP.append($priceSpan);
                    $priceDiv.append($priceP);
                    // NBR Registered
                    let $nbrRegisteredDiv = $("<div></div>").addClass("content-box");
                    let $nbrRegisteredP = $("<p>Registered</p>");
                    let $nbrRegisteredSpan = $("<span>"+obj.nbrregistred+"</span>").addClass("count");
                    $nbrRegisteredDiv.append($nbrRegisteredP);
                    $nbrRegisteredDiv.append($nbrRegisteredSpan);
                    // rating
                    let $ratingDiv = $("<div></div>").addClass("content-box");
                    let $ratingP = $("<p>Course Rating</p>");
                    let $ratingSpan = $("<span>"+obj.rate+"</span>").addClass("count");
                    let $ratingBarStar = $("<span></span>").addClass("rating-star");
                    let $ratingBarLine = $("<span>"+obj.rate+"</span>").addClass("rating-bar");
                    let styleBR = "width: "+(obj.rate/5)*100+"%;"
                    $ratingBarLine.attr("style", styleBR);
                    $ratingBarStar.append($ratingBarLine);
                    $ratingSpan.append($ratingBarStar);
                    $ratingDiv.append($ratingP);
                    $ratingDiv.append($ratingSpan);
                    // append all to other details div
                    $parentDiv.append($priceDiv);
                    $parentDiv.append($nbrRegisteredDiv);
                    $parentDiv.append($ratingDiv);
                    // append all to main div
                    let $mainDiv = $("<div></div>").addClass("courses-item");
                    $mainDiv.append($courseImgDiv);
                    $mainDiv.append($courseTitleDiv);
                    $mainDiv.append($coursePostedDateDiv);
                    $mainDiv.append($parentDiv);
                    $("#mainAllCourseDiv").append($mainDiv);
                    });
                } 
            },
        error: function (xhr, status, error) {
        console.log("Error: " + error);
        },
    });
}
// Courses Front
function filterCourseFront(button) {  
    let filter = button.getAttribute("data-value");
    if(filter == 'All') window.location.reload();
    let url = $("#searchFrontDiv").data("url");
    $.ajax({
        type: "GET",
        url: url,
        data: { filter: filter },

        success: function (courses) {
        if (courses) {
            console.log(courses);
            $("#mainDivCourses").empty();
                let courseDetailUrl = "/courseDetails";

                $.each(JSON.parse(courses), function (i, obj) {           
                let imageSrc = "../../contents/uploads/courses/" + obj.image;
                let tutorImgSrc = "../../assets/images/author/author-01.jpg";
                let courseUrl = courseDetailUrl + obj.idcourse;
                
                let $mainDiv = $("<div></div>").addClass("col-lg-4 col-md-6");
                let $singleCourses = $("<div></div>").addClass("single-courses");
                let $courseContentDiv = $("<div></div>").addClass("courses-content");
                // course img
                let $courseImgDiv = $("<div></div>").addClass("courses-images");
                let $courseImgAL = $("<a></a>").attr("href", courseUrl);
                let $courseImg = $("<img />").attr({
                    "src": imageSrc,
                    "alt": "Courses",
                    "width": 330,
                    "height": 200
                });
                $courseImgAL.append($courseImg);
                $courseImgDiv.append($courseImgAL);
                // tutors details
                let $tutorDetailsMainDiv = $("<div></div>").addClass("courses-author");
                let $tutorDetailsDiv = $("<div></div>").addClass("author");

                let $tutorImgDiv = $("<div></div>").addClass("author-thumb");
                let $tutorImgAL = $("<a href='#'></a>");
                let $tutorImg = $("<img />").attr({
                    "src": tutorImgSrc,
                    "alt": "Tutor"
                });
                $tutorImgAL.append($tutorImg);
                $tutorImgDiv.append($tutorImgAL);

                let $tutorNameDiv = $("<div></div>").addClass("author-name");
                let $tutorNameAL = $("<a href='#'>"+ obj.tutor[2] + obj.tutor[1] +"</a>");
                $tutorNameDiv.append($tutorNameAL);

                $tutorDetailsDiv.append($tutorImgDiv);
                $tutorDetailsDiv.append($tutorNameDiv);
                $tutorDetailsMainDiv.append($tutorDetailsDiv);

                // course title
                let $courseTitleH = $("<h4></h4>").addClass("title");
                let $courseTitleAL = $("<a>"+obj.title+"</a>").attr("href", courseUrl);
                $courseTitleH.append($courseTitleAL);

                // other course details
                let $courseDurationRegistered = $("<div></div>").addClass("courses-meta");
                let $courseDurationSpan = $("<span> <i class='icofont-clock-time'></i>"+obj.duration+"</span>");
                let $courseRegisteredSpan = $("<span> <i class='icofont-read-book'></i>"+obj.nbrregistred+"</span>");
                $courseDurationRegistered.append($courseDurationSpan);
                $courseDurationRegistered.append($courseRegisteredSpan);

                let $coursePriceRate = $("<div></div>").addClass("courses-price-review");
                let $priceDiv = $("<div></div>").addClass("courses-price");
                let $priceSpan = $("<span>"+ obj.price +" TND</span>").addClass("sale-parice");
                $priceDiv.append($priceSpan);

                let $rateDiv = $("<div></div>").addClass("courses-review");
                let $ratingCount = $("<span>"+ obj.rate +"</span>").addClass("rating-count");
                let $ratingBarStar = $("<span></span>").addClass("rating-star");
                let $ratingBarLine = $("<span>"+obj.rate+"</span>").addClass("rating-bar");
                let styleBR = "width: "+(obj.rate/5)*100+"%;"
                $ratingBarLine.attr("style", styleBR);
                $ratingBarStar.append($ratingBarLine);
                $rateDiv.append($ratingCount);
                $rateDiv.append($ratingBarStar);

                $coursePriceRate.append($priceDiv);
                $coursePriceRate.append($rateDiv);

                // append to courses content
                $courseContentDiv.append($tutorDetailsMainDiv);
                $courseContentDiv.append($courseTitleH);
                $courseContentDiv.append($coursePriceRate);
                
                $singleCourses.append($courseImgDiv);
                $singleCourses.append($courseContentDiv);

                $mainDiv.append($singleCourses);

                $("#mainDivCourses").append($mainDiv);
                $("#paginatorID").empty();
                });
            }
            },
        error: function (xhr, status, error) {
        console.log("Error: " + error);
        },
    });
}

function searchCourse(search){
    if(search == '') window.location.reload();
    let url = $("#searchCourse").data("url");
    $.ajax({
        type: "GET",
        url: url,
        data: { search: search },

        success: function (courses) {
        if (courses) {
            console.log(courses);
            $("#mainDivCourses").empty();
                let courseDetailUrl = "/courseDetails";

                $.each(JSON.parse(courses), function (i, obj) {           
                let imageSrc = "../../contents/uploads/courses/" + obj.image;
                let tutorImgSrc = "../../assets/images/author/author-01.jpg";
                let courseUrl = courseDetailUrl + obj.idcourse;
                
                let $mainDiv = $("<div></div>").addClass("col-lg-4 col-md-6");
                let $singleCourses = $("<div></div>").addClass("single-courses");
                let $courseContentDiv = $("<div></div>").addClass("courses-content");
                // course img
                let $courseImgDiv = $("<div></div>").addClass("courses-images");
                let $courseImgAL = $("<a></a>").attr("href", courseUrl);
                let $courseImg = $("<img />").attr({
                    "src": imageSrc,
                    "alt": "Courses",
                    "width": 330,
                    "height": 200
                });
                $courseImgAL.append($courseImg);
                $courseImgDiv.append($courseImgAL);
                // tutors details
                let $tutorDetailsMainDiv = $("<div></div>").addClass("courses-author");
                let $tutorDetailsDiv = $("<div></div>").addClass("author");

                let $tutorImgDiv = $("<div></div>").addClass("author-thumb");
                let $tutorImgAL = $("<a href='#'></a>");
                let $tutorImg = $("<img />").attr({
                    "src": tutorImgSrc,
                    "alt": "Tutor"
                });
                $tutorImgAL.append($tutorImg);
                $tutorImgDiv.append($tutorImgAL);

                let $tutorNameDiv = $("<div></div>").addClass("author-name");
                let $tutorNameAL = $("<a href='#'>"+ obj.tutor[2] + obj.tutor[1] +"</a>");
                $tutorNameDiv.append($tutorNameAL);

                $tutorDetailsDiv.append($tutorImgDiv);
                $tutorDetailsDiv.append($tutorNameDiv);
                $tutorDetailsMainDiv.append($tutorDetailsDiv);

                // course title
                let $courseTitleH = $("<h4></h4>").addClass("title");
                let $courseTitleAL = $("<a>"+obj.title+"</a>").attr("href", courseUrl);
                $courseTitleH.append($courseTitleAL);

                // other course details
                let $courseDurationRegistered = $("<div></div>").addClass("courses-meta");
                let $courseDurationSpan = $("<span> <i class='icofont-clock-time'></i>"+obj.duration+"</span>");
                let $courseRegisteredSpan = $("<span> <i class='icofont-read-book'></i>"+obj.nbrregistred+"</span>");
                $courseDurationRegistered.append($courseDurationSpan);
                $courseDurationRegistered.append($courseRegisteredSpan);

                let $coursePriceRate = $("<div></div>").addClass("courses-price-review");
                let $priceDiv = $("<div></div>").addClass("courses-price");
                let $priceSpan = $("<span>"+ obj.price +" TND</span>").addClass("sale-parice");
                $priceDiv.append($priceSpan);

                let $rateDiv = $("<div></div>").addClass("courses-review");
                let $ratingCount = $("<span>"+ obj.rate +"</span>").addClass("rating-count");
                let $ratingBarStar = $("<span></span>").addClass("rating-star");
                let $ratingBarLine = $("<span>"+obj.rate+"</span>").addClass("rating-bar");
                let styleBR = "width: "+(obj.rate/5)*100+"%;"
                $ratingBarLine.attr("style", styleBR);
                $ratingBarStar.append($ratingBarLine);
                $rateDiv.append($ratingCount);
                $rateDiv.append($ratingBarStar);

                $coursePriceRate.append($priceDiv);
                $coursePriceRate.append($rateDiv);

                // append to courses content
                $courseContentDiv.append($tutorDetailsMainDiv);
                $courseContentDiv.append($courseTitleH);
                $courseContentDiv.append($coursePriceRate);
                
                $singleCourses.append($courseImgDiv);
                $singleCourses.append($courseContentDiv);

                $mainDiv.append($singleCourses);

                $("#mainDivCourses").append($mainDiv);
                $("#paginatorID").empty();
                });
            }
            },
        error: function (xhr, status, error) {
        console.log("Error: " + error);
        },
    });
}