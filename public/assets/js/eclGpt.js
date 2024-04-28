const submitBtn = document.getElementById('submitQuestionBtn');
submitBtn.addEventListener("click", () => {
    const question = document.getElementById("questionInput").value;
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
    const imageFile = document.getElementById("questionImage").files[0];
    let formData = new FormData();
    formData.append("question", question);
    if (imageFile) {
        formData.append("image", imageFile);
    }

    let url = $("#questionDiv").data("url");

    $.ajax({
        type: "POST",
        url: url,
        data: formData,
        processData: false, 
        contentType: false, 
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
            console.error("Error: " + error);
        }
    });
})