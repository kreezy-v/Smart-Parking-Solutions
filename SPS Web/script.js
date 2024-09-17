var access = document.getElementById("access");
var accessSide = document.getElementById("accessSide");
var text = document.getElementById("updateTextLog");

if(localStorage.length == 0){
    access.innerHTML ="<br><br><br><br><br><br><br><div class='col text-center'><a type='button' class='btn btn-danger w-100' href='signup.php'>SIGN UP</a></div><br><div class='col text-center'><button type='button' class='btn btn-success w-100' data-bs-toggle='modal' data-bs-target='#loginModal'>LOGIN</button></div><br>";
    accessSide.innerHTML = "<li class='nav-item'> <a type='button' class='btn btn-danger w-100' href='signup.php'>SIGN UP</a></li><br> <li class='nav-item'>  <button type='button' class='btn btn-success w-100' data-bs-toggle='modal' data-bs-target='#loginModal'>LOGIN</button></li>";
    text.innerHTML = "LOGIN FIRST";
}
else{
    text.innerHTML = "";
}