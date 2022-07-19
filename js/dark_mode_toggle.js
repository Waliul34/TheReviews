const arrows = document.querySelectorAll(".arrow");
const movieLists = document.querySelectorAll(".movie-list");

arrows.forEach((arrow, i) => {
    const itemNumber = movieLists[i].querySelectorAll("img").length;
    let clickCounter = 0;
    arrow.addEventListener("click", ()=>{
        const ratio = Math.floor(window.innerWidth/270);
        clickCounter++;
        if(itemNumber - (5+clickCounter) +(5 - ratio) >= 0)
            movieLists[i].style.transform = `translateX(${movieLists[i].computedStyleMap().get("transform")[0].x.value - 290}px)`;    
        else
        {
            movieLists[i].style.transform = `translateX(0)`;
            clickCounter = 0;
        }
    });

});

const ball = document.querySelector(".toggle-ball");
const items = document.querySelectorAll(".container, .movie-list-title, .navbar-container, .sidebar, .left-menu-icon, .toggle");

function init()
{
    items.forEach(item=>{
        item.classList.toggle("active");
        ball.classList.toggle("active");
    });
}

function tog()
{
    const curMode = document.cookie.match('(^|;)\\s*' + "mode" + '\\s*=\\s*([^;]+)')?.pop() || '';
    const expires = (new Date(Date.now()+86400)).toUTCString();
    // console.log(expires);
    if(curMode == "0")
        document.cookie = "mode = 1; expires=" + expires;
    else
        document.cookie = "mode = 0; expires=" + expires;

    // console.log(document.cookie);
    items.forEach(item=>{
        item.classList.toggle("active");
        ball.classList.toggle("active");
    });
}

ball.addEventListener("click", tog);