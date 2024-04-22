function filterCourse(filter) {  
var url = $("#selectFilter").data("url");
$.ajax({
    type: "GET",
    url: url,
    data: { filter: filter },

    success: function (courses) {
    if (courses) {
        console.log(courses);
        $("#mainAllCourseDiv").empty();
            var courseDetailUrl = "{{ path('tutor_course_details', {'idCourse' : 'PLACEHOLDER'}) }}";

            $.each(JSON.parse(courses), function (i, obj) {           
            var imageSrc = "{{ asset('contents/uploads/courses/') }}" + obj.image;
            var courseUrl = courseDetailUrl.replace('PLACEHOLDER', obj.idcourse);
            // course img
            var $courseImgDiv = $("<div></div>").addClass("item-thumb");
            var $courseImgAL = $("<a></a>").attr("href", courseUrl);
            var $courseImg = $("<img />").attr("src",imageSrc)
            $courseImgAL.append($courseImg);
            $courseImgDiv.append($courseImgAL);
            // course title
            var $courseTitleDiv = $("<div></div>").addClass("content-title");
            var $courseTitleH = $("<h3></h3>");
            var $courseTitleAL = $("<a>"+obj.title+"</a>").attr("href", courseUrl);
            $courseTitleH.append($courseTitleAL);
            $courseTitleDiv.append($courseTitleH);
            // course posted date
            var $coursePostedDateDiv = $("<div></div>").addClass("content-title");
            var $coursePostedDateSpan = $("<span>"+obj.posteddate+"</span>").addClass("title");
            $coursePostedDateDiv.append($coursePostedDateSpan);
            // other course details
            var $parentDiv = $("<div></div>").addClass("content-wrapper");
            // price
            var $priceDiv = $("<div></div>").addClass("content-box");
            var $priceP = $("<p>Price</p>");
            var $priceSpan = $("<span>"+obj.price+" TND</span>").addClass("count");
            $priceP.append($priceSpan);
            $priceDiv.append($priceP);
            // NBR Registered
            var $nbrRegisteredDiv = $("<div></div>").addClass("content-box");
            var $nbrRegisteredP = $("<p>Registered</p>");
            var $nbrRegisteredSpan = $("<span>"+obj.nbrregistred+"</span>").addClass("count");
            $nbrRegisteredDiv.append($nbrRegisteredP);
            $nbrRegisteredDiv.append($nbrRegisteredSpan);
            // rating
            var $ratingDiv = $("<div></div>").addClass("content-box");
            var $ratingP = $("<p>Course Rating</p>");
            var $ratingSpan = $("<span>"+obj.rate+"</span>").addClass("count");
            var $ratingBarStar = $("<span></span>").addClass("rating-star");
            var $ratingBarLine = $("<span>"+obj.rate+"</span>").addClass("rating-bar");
            var styleBR = "width: "+(obj.rate/5)*100+"%;"
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
            var $mainDiv = $("<div></div>").addClass("courses-item");
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