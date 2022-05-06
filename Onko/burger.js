const burger = document.getElementById("burger-menu");
const links = document.getElementById("link-list");
burger.addEventListener("click", function() {

    
    if(burger.className == "burger") {
        burger.className += "-click";
        links.className +="-click";
    }

    else {
        burger.className = "burger";
        links.className = "links";
    }
});

var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    } 
  });
}

