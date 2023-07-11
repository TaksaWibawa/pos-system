document.addEventListener("DOMContentLoaded", function () {
  const menuItems = document.querySelectorAll(".menu-link");
  const navbarTitle = document.getElementById("menu-title");
  const contentContainer = document.querySelector(".content");

  // Initialize the first menu as active and load its content on page load
  menuItems[0].classList.add("active");
  navbarTitle.innerHTML = menuItems[0].querySelector("a").textContent;
  const firstMenuUrl = menuItems[0].querySelector("a").getAttribute("href");
  loadContent(firstMenuUrl);

  menuItems.forEach((menuItem) => {
    // Exclude the logout button
    if (!menuItem.classList.contains("logout")) {
      menuItem.addEventListener("click", (event) => {
        event.preventDefault(); // Prevent the default link behavior

        // Remove 'active' class from all menu items
        menuItems.forEach((item) => item.classList.remove("active"));
        // Add 'active' class to the clicked menu item
        menuItem.classList.add("active");

        const clickedTitle = menuItem.querySelector("a").textContent;
        navbarTitle.innerHTML = clickedTitle;

        const targetUrl = menuItem.querySelector("a").getAttribute("href"); // Get the URL from the menu item's link

        // Load the content from the target URL
        loadContent(targetUrl);
      });
    }
  });

  function loadContent(url) {
    fetch(url)
      .then((response) => response.text())
      .then((content) => {
        // Update the content container with the loaded content
        contentContainer.innerHTML = content;
        // Reset the URL
        history.replaceState({}, document.title, window.location.pathname);
      })
      .catch((error) => {
        // Handle any errors that occur during the fetch request
        console.error("Error:", error);
      });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  var sidebarLinks = document.querySelectorAll(".menu-link a");

  sidebarLinks.forEach(function (link) {
    link.addEventListener("contextmenu", function (event) {
      event.preventDefault();
    });
  });
});
