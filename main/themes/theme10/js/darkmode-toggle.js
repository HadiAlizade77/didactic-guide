var toggleMode = document.getElementById("toggleButton");
var link = document.getElementById("toggleCss");
const themeLocalStorage = localStorage.getItem("dark-mode");

(function stateCheck()
{
    // console.log('state',themeLocalStorage == null);
    if (themeLocalStorage === "true" || themeLocalStorage == null) {
        link.setAttribute("href", "themes/theme10/css/theme10-darkmode.css");
        toggleMode.checked = true;
    } else {
        toggleMode.checked = false;
        // link.setAttribute("href", "css/custom.css");
    }
})();


function toggleButton(e) {
//   const isChecked = e.target.checked;
  const isChecked = toggleMode.checked;
  if (isChecked) {
    localStorage.setItem("dark-mode", true);
    link.setAttribute("href", "themes/theme10/css/theme10-darkmode.css");
    // console.log("dark", link);
  } 
  else {
    localStorage.setItem("dark-mode", false);
    link.setAttribute("href", "css/custom.css");
    // console.log("light", link);
  }
}

toggleMode.addEventListener("click", toggleButton);
