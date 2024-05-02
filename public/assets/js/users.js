$(document).ready(function() {
    $('#searchinput').on('input', function() {
        var searchTerm = $(this).val().trim();
        if (searchTerm.length > 0) {
            searchUser(searchTerm);
        } else {
                searchUser('');
            
        }
    });
});

function searchUser(searchTerm) {
    let url = "/admin/searchUsersBack"; 
    $.ajax({
        type: "GET",
        url: url,
        data: { search: searchTerm },
        success: function (data) {
            console.log(data);
            if (Array.isArray(data) && data.length > 0) {
                $("#mainAllUserseDiv").empty(); 
                $.each(data, function (i, user) {
                    let imageSrc = user.image ? "/contents/uploads/users/" + user.image : user.image;
                    let userHtml = `
                        <div class="courses-item">
                            <div class="item-thumb">
                                <a href="#">
                                    <img src="${imageSrc}" alt="Author">
                                </a>
                            </div>
                            <div class="content-title">
                                <h3><a href="#">${user.firstname} ${user.lastname}</a></h3>
                            </div>
                            <div class="content-title">
                                <span class="title">${user.role}</span>
                            </div>
                            <div class="content-title">
                                <span class="title">${user.gender}</span>
                            </div>
                            <div class="content-title">
                                <span class="title">${user.email}</span>
                            </div>
                            ${user.isactive ? 
                                `<div class="content-title">
                                    <span class="title" style="color:green;">active</span>
                                </div>
                                <div class="content-wrapper">
                                    <a href="/admin/blockProfil/${user.iduser}" class="btn btn-primary btn-hover-dark">block</a>
                                </div>` 
                                :
                                `<div class="content-title">
                                    <span class="title" style="color:red;">blocked</span>
                                </div>
                                <div class="content-wrapper">
                                    <a href="/admin/unblockProfil/${user.iduser}" class="btn btn-primary btn-hover-dark">Unblock</a>
                                </div>`}
                            <div class="content-wrapper">
                                <a href="/admin/makeAdmin/${user.iduser}" class="btn btn-primary btn-hover-dark">admin</a>
                            </div>
                        </div>`;
                    $("#mainAllUserseDiv").append(userHtml);
                });
            } else {
                console.log("No users found.");
            }
        },
        error: function (xhr, status, error) {
            console.log("Error fetching users: " + error);
        },
    });
}