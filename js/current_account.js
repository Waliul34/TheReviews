let cont = document.querySelectorAll('[id=logged]');

let myReviews = document.querySelectorAll('[id=myReviews]');
let addAFilm = document.querySelectorAll('[id=addAFilm]');

let prof = document.getElementById("profile");
let logout = document.getElementById("logout");
let d2 = prof.getElementsByTagName("span")[0];

if (usr) 
{
    cont[0].style.display = "none";
    cont[1].style.display = "none";
    prof.style.display = "block";
    logout.style.display = "block";
    d2.innerHTML = usr;
    myReviews[0].style.display = "block";
} else 
{
    cont[0].style.display = "block";
    cont[1].style.display = "block";
    prof.style.display = "none";
    logout.style.display = "none";
    myReviews[0].style.display = "none";
}
if(usr && isAdmin)
    addAFilm[0].style.display = "block";
else
    addAFilm[0].style.display = "none";