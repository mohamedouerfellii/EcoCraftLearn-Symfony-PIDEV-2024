function submitQustion(){
    const question = document.getElementById("questionInput").value;
    let msgList = document.getElementById("msgList");
    let url = $("#questionDiv").data("url");
    let userDetails = "Ouerfelli Mohamed";
    let userImg = "/assets/images/author/author-01.jpg";
    let userMsg = `
    <li>
        <!-- Single Message Start -->
        <div class="single-message">
            <div class="message-author">
                <div class="author-images">
                    <img src="${userImg}" alt="Author">
                </div>
                <div class="author-content">
                    <h6 class="name"><strong>${userDetails}</strong></h6>
                </div>
            </div>
            <p>${question}</p>
        </div>
    </li>
    `;
    $("#msgList").append(userMsg);
    document.getElementById("questionInput").value="";
    $.ajax({
        type: "GET",
        url: url,
        data: { question: question },

        success: function (response) {
            if (response) {
                console.log(response);
                let imageSrc = "/assets/images/logoDark.png";
                let $liMsg = $("<li></li>");

                let $divSingleMsg = $("<div></div>").addClass("single-message");
                let $divMsgAuth = $("<div></div>").addClass("message-author");

                let $divImgAuth = $("<div></div>").addClass("author-images");
                let $imgAuth = $("<img></img>").attr("src",imageSrc);
                $divImgAuth.append($imgAuth);

                let $divContentAuth = $("<div></div>").addClass("author-content");
                let $hName = $("<h6></h6>").addClass("name");
                let $strongN = $("<strong>ECL-GPT</strong>");
                $hName.append($strongN);
                $divContentAuth.append($hName);

                $divMsgAuth.append($divImgAuth);
                $divMsgAuth.append($divContentAuth);

                let $mainMsg = $("<p>"+response+"</p>");

                $divSingleMsg.append($divMsgAuth);
                $divSingleMsg.append($mainMsg);
                $liMsg.append($divSingleMsg);

                $("#msgList").append($liMsg);
                } 
            },
        error: function (xhr, status, error) {
        console.log("Error: " + error);
        },
    });
}