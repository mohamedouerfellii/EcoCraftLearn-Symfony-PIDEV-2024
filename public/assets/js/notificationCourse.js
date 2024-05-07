function notifytutor(idTutor,isNew){
    const url = $("#urlNotify").data('url');
    let notifSound = new Audio("../../assets/sounds/notification.mp3");
    $.ajax({
        type: "GET",
        url: url,
        data: { idTutor: idTutor },

        success: function (notifications) {
            $("#notifList").empty();
            $(".all-notification").empty();
            if (notifications && JSON.parse(notifications).length > 0) {     
                let urlRead = "/readNotificationCourse".idTutor;
                $(".all-notification").attr("href",urlRead);
                $(".all-notification").text("See all notifications");
                $("#notifAlert").addClass("active");
                $.each(JSON.parse(notifications), function (i, obj) { 
                    let $lItem = $("<li></li>").addClass("notification-item");
                    let $spanIcon = $("<span></span>").addClass("notify-icon bg-success text-white");
                    let $notifIcon = $("<i></i>").addClass("icofont-ui-user");
                    $spanIcon.append($notifIcon);
                    $lItem.append($spanIcon);

                    let $titleDiv = $("<div></div>").addClass("dropdown-body");
                    let $titleAL = $("<a></a>").attr("href","#");
                    let $notifTitle = $("<p></p>").attr("id","notifDesc");
                    let $notifTitleStrong = $("<strong>"+obj.title+"</strong>");
                    $notifTitle.append($notifTitleStrong);
                    $titleAL.append($notifTitle);
                    $titleDiv.append($titleAL);
                    $lItem.append($titleDiv);

                    let $timeSpan = $("<span>"+obj.date+"</span>").addClass("notify-time");
                    $lItem.append($timeSpan);
                    
                    $("#notifList").append($lItem);
                    if(isNew){
                        if(i == 0){
                            if (!("Notification" in window)) {
                                alert("Ce navigateur ne supporte pas les notifications système");
                            }
                            else if (Notification.permission === "granted") {
                                var notification = new Notification("EcoCraftLearning", {
                                body: obj.title,
                                icon: "../../assets/images/logoDark.png"
                                });
                            }
                            else if (Notification.permission !== "denied") {
                                Notification.requestPermission().then(function (permission) {
                                if (permission === "granted") {
                                    var notification = new Notification("EcoCraftLearning", {
                                    body: obj.title,
                                    icon: "../../assets/images/logoDark.png"
                                    });
                                }
                                });
                            }  
                        }
                    }
                });
            } else{
                $("#notifAlert").removeClass("active");
                $(".all-notification").text("Nothing new !");
            }
        },
        error: function (xhr, status, error) {
        console.log("Error: " + error);
        },
    });
}