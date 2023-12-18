const body = document.querySelector("body");
const modeToggle = document.querySelector(".dark-light");
let getMode = localStorage.getItem("mode");

if (getMode === "dark-mode") {
  body.classList.add("dark");
}

modeToggle.addEventListener("click", () => {
  modeToggle.classList.toggle("active");
  body.classList.toggle("dark");
  
  if (!body.classList.contains("dark")) {
    localStorage.setItem("mode", "light-mode");
  } else {
    localStorage.setItem("mode", "dark-mode");
  }
});

const searchToggle = document.querySelector(".searchToggle");

searchToggle.addEventListener("click", () => {
  searchToggle.classList.toggle("active");
});

const sidebarOpen = document.querySelector(".sidebarOpen");
const nav = document.querySelector("nav");

sidebarOpen.addEventListener("click", () => {
  nav.classList.add("active");
});

document.body.addEventListener("click", e => {
  let clickedElm = e.target;

  if (!clickedElm.classList.contains("sidebarOpen") && !clickedElm.classList.contains("menu")) {
    nav.classList.remove("active");
  }
});
