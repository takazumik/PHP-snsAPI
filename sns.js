"use strict"

//新規登録

let signup = () => {
    const name = document.getElementById("name").value;
    const bio = document.getElementById("bio").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const passwordConfirmation = document.getElementById("passwordConfirmation").value;


    const signupData = {
        sign_up_user_params: {
            name: name,
            bio: bio,
            email: email,
            password: password,
            password_confirmation: passwordConfirmation
        }
    };

    console.log("今送ったデータ");
    console.log(JSON.stringify(signupData));


    fetch("http://localhost/php_api/sign_up.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json; charset=utf-8",
            },
            body: JSON.stringify(signupData),
        })
        .then(response => response.json())
        .then(json => {
            console.log(json)
        })
}



// //ログイン
// const login = () => {
//     const loginEmail = document.getElementById("loginEmail").value;
//     const loginPassword = document.getElementById("loginPassword").value;
//     const loginPasswordConfirmation = document.getElementById("loginPasswordConfirmation").value;


//     const loginData = {
//         sign_in_user_params: {
//             email: loginEmail,
//             password: loginPassword,
//             password_confirmation: loginPasswordConfirmation,
//         }
//     };


//     console.log("今送ったデータ");
//     console.log(JSON.stringify(loginData));

//     fetch("https://teachapi.herokuapp.com/sign_in", {
//             method: "POST",
//             headers: {
//                 "Content-Type": "application/json; charset=utf-8",
//             },
//             body: JSON.stringify(loginData),
//         })
//         .then(response => response.json())
//         .then(json => {
//             // ここでログインに成功したときにサーバから返されるtokenを保存する！
//             const token = json.token;
//             localStorage.setItem('token', token)
//             console.log(json)
//         })
// }

// //ユーザー一覧
// const users = () => {
//     const page = document.getElementById("page").value;
//     const limit = document.getElementById("limit").value;
//     const query = document.getElementById("query").value;
//     const allusers = document.getElementById("allusers");

//     console.log("今送ったデータ url")

//     const token = localStorage.getItem('token')

//     fetch(`https://teachapi.herokuapp.com/users?page=${page}&limit=${limit}&query=${query}`, {
//             method: "GET",
//             headers: {
//                 "Content-Type": "application/json; charset=utf-8",
//                 "Authorization": `Bearer ${token}`
//             },
//         })
//         .then(response => response.json())
//         .then(json => {
//             console.log(json);
//             // 名前だけ出したい
//             let s = "<br>"
//             for (let i = 0; i < json.length; i++) {
//                 const obj = json[i]
//                 const objstr = JSON.stringify(obj.name)
//                 s += objstr + "<br>"
//             }
//             allusers.innerHTML = s
//         })
// }


// //投稿一覧
// const posts = () => {
//     const postPage = document.getElementById("postPage").value;
//     const postLimit = document.getElementById("postLimit").value;
//     const postQuery = document.getElementById("postQuery").value;
//     const showPost = document.getElementById("showPost");


//     console.log("今送ったデータ url")

//     const token = localStorage.getItem('token')

//     fetch(`https://teachapi.herokuapp.com/posts?posts=${postPage}&limit=${postLimit}&query=${postQuery}`, {
//             method: "GET",
//             headers: {
//                 "Content-Type": "application/json; charset=utf-8",
//                 "Authorization": `Bearer ${token}`
//             },
//         })
//         .then(response => response.json())
//         .then(json => {
//             console.log(json)
//             showPost.innerHTML = JSON.stringify(json)
//         })
// }

// //ユーザー編集
// const userEdit = () => {
//     const editName = document.getElementById("editName").value;
//     const editBio = document.getElementById("editBio").value;
//     const userEditId = document.getElementById("user-edit-id").value;
//     const showUserEdit = document.getElementById("showUserEdit");


//     const userEditData = {
//         user_params: {
//             name: editName,
//             bio: editBio,
//         }
//     };


//     console.log("今送ったデータ");
//     console.log(JSON.stringify(userEditData));

//     const token = localStorage.getItem('token');

//     fetch(`https://teachapi.herokuapp.com/users/${userEditId}`, {
//             method: "PUT",
//             headers: {
//                 "Content-Type": "application/json; charset=utf-8",
//                 "Authorization": `Bearer ${token}`
//             },
//             body: JSON.stringify(userEditData),
//         })
//         .then(response => response.json())
//         .then(json => {
//             console.log(json)
//             showUserEdit.innerHTML = JSON.stringify(json)
//         })
// }

// //ユーザー削除
// const userDelete = () => {
//     const deleteUserId = document.getElementById("delete-user-id").value;
//     const deleteUser = document.getElementById("deleteUser").value;
//     const userDeleteData = {
//         post_params: {
//             text: deleteUser
//         }
//     };

//     console.log("今送ったデータ");
//     console.log(JSON.stringify(userDeleteData));

//     const token = localStorage.getItem('token');

//     fetch(`https://teachapi.herokuapp.com/users/${deleteUserId}`, {
//             method: "DELETE",
//             headers: {
//                 "Content-Type": "application/json; charset=utf-8",
//                 "Authorization": `Bearer ${token}`
//             },
//             body: JSON.stringify(userDeleteData), // 本文のデータ型は "Content-Type" ヘッダーと一致する必要があります
//         })
//         .then(response => response.json())
//         .then(json => {
//             console.log(json)
//         })
// }


// //タイムライン
// const timeline = () => {
//     const timelinePage = document.getElementById("timelinePage").value;
//     const timelineLimit = document.getElementById("timelineLimit").value;
//     const timelineQuery = document.getElementById("timelineQuery").value;
//     const timelineId = document.getElementById("timeline-id").value;
//     const showTimeline = document.getElementById("showTimeline");


//     console.log("今送ったデータ url")

//     const token = localStorage.getItem('token');

//     fetch(`https://teachapi.herokuapp.com/users/${timelineId}/timeline?page=${timelinePage}&limit=${timelineLimit}&query=${timelineQuery}`, {
//             method: "GET",
//             headers: {
//                 "Content-Type": "application/json; charset=utf-8",
//                 "Authorization": `Bearer ${token}`
//             },
//         })
//         .then(response => response.json())
//         .then(json => {
//             console.log(json);
//             showTimeline.innerHTML = JSON.stringify(json);
//         })
// }


// //投稿作成
// const newPost = () => {
//     const postText = document.getElementById("postText").value;
//     const showNewpost = document.getElementById("showNewpost");


//     const newPostData = {
//         post_params: {
//             text: postText
//         }
//     };

//     console.log("今送ったデータ")
//     console.log(JSON.stringify(newPostData))

//     const token = localStorage.getItem('token');

//     fetch("https://teachapi.herokuapp.com/posts", {
//             method: "POST",
//             headers: {
//                 "Content-Type": "application/json; charset=utf-8",
//                 "Authorization": `Bearer ${token}`
//             },
//             body: JSON.stringify(newPostData), // 本文のデータ型は "Content-Type" ヘッダーと一致する必要があります
//         })
//         .then(response => response.json())
//         .then(json => {
//             console.log(json);
//             showNewpost.innerHTML = JSON.stringify(json);
//         })
// }

// //投稿編集
// const editPost = () => {
//     const editText = document.getElementById("editText").value;
//     const postEditId = document.getElementById("post-edit-id").value;
//     const showEditPost = document.getElementById("showEditPost");


//     const editPostData = {
//         post_params: {
//             text: editText
//         }
//     };

//     console.log("今送ったデータ")
//     console.log(JSON.stringify(editPostData))

//     const token = localStorage.getItem('token');

//     fetch(`https://teachapi.herokuapp.com/posts/${postEditId}`, {
//             method: "PUT",
//             headers: {
//                 "Content-Type": "application/json; charset=utf-8",
//                 "Authorization": `Bearer ${token}`
//             },
//             body: JSON.stringify(editPostData), // 本文のデータ型は "Content-Type" ヘッダーと一致する必要があります
//         })
//         .then(response => response.json())
//         .then(json => {
//             console.log(json);
//             showEditPost.innerHTML = JSON.stringify(json);
//         })
// }

// //投稿削除
// const deleteText = () => {
//     const idDelete = document.getElementById("delete-id").value;
//     const deleteID = document.getElementById("delete");

//     const deleteData = {
//         post_params: {
//             text: deleteID
//         }
//     };

//     console.log("今送ったデータ")
//     console.log(JSON.stringify(deleteData))

//     const token = localStorage.getItem('token');

//     fetch(`https://teachapi.herokuapp.com/posts/${idDelete}`, {
//             method: "DELETE",
//             headers: {
//                 "Content-Type": "application/json; charset=utf-8",
//                 "Authorization": `Bearer ${token}`
//             },
//             body: JSON.stringify(deleteData), // 本文のデータ型は "Content-Type" ヘッダーと一致する必要があります
//         })
//         .then(response => response.json())
//         .then(json => {
//             console.log(json)
//         })
// }