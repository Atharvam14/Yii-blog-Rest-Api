
let currentPostId = null;

if (window.blogApiAuth && window.blogApiAuth.token && window.blogApiAuth.expiresAt) {
    sessionStorage.setItem("token", window.blogApiAuth.token);
    sessionStorage.setItem("token_expiry", String(window.blogApiAuth.expiresAt));
}

function readToken(showAlert = false) {
    let token = sessionStorage.getItem("token");
    let expiry = sessionStorage.getItem("token_expiry");

    if (!token || !expiry) {
        if (showAlert) {
            alert("Please login first!");
        }
        return null;
    }

    if (new Date().getTime() > expiry) {
        sessionStorage.clear();
        if (showAlert) {
            alert("Session expired!");
        }
        return null;
    }

    return token;
}

//  Get token with validation
function getToken() {
    return readToken(true);
}

function fetchApiToken() {
    return $.ajax({
        url: '/yii2-blog-api/frontend/web/site/api-token',
        type: 'GET',
        dataType: 'json'
    }).then(function (response) {
        if (response.token && response.expires_in) {
            sessionStorage.setItem("token", response.token);
            sessionStorage.setItem("token_expiry", String(new Date().getTime() + (response.expires_in * 1000)));
            return response.token;
        }

        return null;
    }).catch(function () {
        alert("Please login first!");
        return null;
    });
}

function ensureToken() {
    let token = readToken(false);

    if (token) {
        return $.Deferred().resolve(token).promise();
    }

    return fetchApiToken();
}

$.ajaxSetup({
    beforeSend: function(xhr){
        let token = readToken(false);
        if(token){
            xhr.setRequestHeader("Authorization", "Bearer " + token);
        }
    }
});

function loadComments(postId = null) {

    if (!postId) {
        postId = prompt("Enter Post ID:");
    }

    if (!postId) {
        alert("Post ID is required!");
        return;
    }

    currentPostId = postId; // store globally

    $.ajax({
        url: '/yii2-blog-api/frontend/web/comment?postId=' + postId,
        type: 'GET',
        success: function(data) {
            let output = "";

            data.forEach(c => {
                output += `
                    <div>
                        <b>${c.title}</b><br>
                        ${c.body}<br>
                        <button onclick="updateComment(${c.id})">Update</button>
                        <button onclick="deleteComment(${c.id})">Delete</button>
                        <hr>
                    </div>
                `;
            });

            document.getElementById("comments").innerHTML = output;
        },
        error: function(err){
            if(err.status === 401){
                sessionStorage.clear();
                alert("Session expired from server!");
                location.reload();
            }
            console.log("Error loading comments:", err);
        }
    });
}


function addComment() {
    ensureToken().then(function (token) {
        if (!token) {
            return;
        }

        $.ajax({
            url: '/yii2-blog-api/frontend/web/comments',
            type: 'POST',
            contentType: 'application/json',
           
            data: JSON.stringify({
                title: 'comment demo ajax method',
                body: 'lorem ipsum dolor hello',
                post_id: currentPostId || 10 // fallback if not loaded
            }),
            success: function(response){
                console.log("Created:", response);
                loadComments(currentPostId); // refresh properly
            },
            error: function(err){
                if(err.status === 401){
                    sessionStorage.clear();
                    alert("Session expired from server!");
                    location.reload();
                }
                console.log("Error:", err);
            }
        });
    });
}


function updateComment(id) {
    let newTitle = prompt("Enter new title:");
    let newBody = prompt("Enter new comment:");

    if (!newTitle || !newBody) {
        alert("All fields required");
        return;
    }

    ensureToken().then(function (token) {
        if (!token) {
            return;
        }

        $.ajax({
            url: '/yii2-blog-api/frontend/web/comments/' + id,
            type: 'PATCH',
            contentType: 'application/json',    
           
            data: JSON.stringify({
                title: newTitle,
                body: newBody
            }),
            success: function(response) {
                alert("Updated successfully");
                loadComments(currentPostId); // refresh 
            },
            error: function(err) {
                if(err.status === 401){
                    sessionStorage.clear();
                    alert("Session over from server!");
                    location.reload();
                }
                console.log("Error:", err);
            }
        });
    });
}


function deleteComment(id){
    ensureToken().then(function (token) {
        if (!token) {
            return;
        }

        $.ajax({
            url: '/yii2-blog-api/frontend/web/comments/' + id,
            type: 'DELETE',
            
            success: function(response){
                console.log("Deleted:", response);
                loadComments(currentPostId); // refresh 
            },
            error: function(err){
                if(err.status === 401){
                    sessionStorage.clear();
                    alert("Session expired from server!");
                    location.reload();
                }
                console.log("Error:",err);
            }
        });
    });
} 

setInterval( () => {
    let expiry = sessionStorage.getItem("token_expiry");

    if (expiry && new Date().getTime() > expiry){
        sessionStorage.clear();
        alert("Session expired!");
        location.reload();
    }
}, 5000);
