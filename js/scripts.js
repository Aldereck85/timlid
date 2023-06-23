"use-strict";
window.addEventListener("load", () => {
  const wrapper = document.getElementById("content-wrapper");
  const navItems = document.querySelectorAll(".nav-item");
  const accordionSidebar = document.getElementById("accordionSidebar");
  const notificationItems = document.querySelectorAll(".notification-item");

  /* Desparecer sub menus al dar click fuera */
  if (wrapper) {
    wrapper.addEventListener("click", () => {
      navItems.forEach((item) => {
        if (item.children[1]) {
          const navIcon = item.children[0];
          navIcon.classList.toggle("collapsed");
          navIcon.setAttribute("aria-expanded", "false");
          const subMenu = item.children[1];
          subMenu.classList.remove("show");
        }
      });
    });
  }

  /* Mantener la clase toggled */
  window.addEventListener("resize", () => {
    if (window.matchMedia("(min-width: 768px)").matches) {
      accordionSidebar.classList.add("toggled");
    } else {
      accordionSidebar.classList.add("toggled");
    }
  });

  /* Eliminar tooltip al dar click */
  $(document).ready(function () {
    $('[data-toggle="tooltip"]').click(function () {
      $('[data-toggle="tooltip"]').tooltip("hide");
    });
  });

  /* Funcion para mostrar u ocultar la contraseña */
  $(".toggle-pass").click(function () {
    const togglePass = document.querySelector(".toggle-pass");
    if (togglePass.dataset.pass === "false") {
      togglePass.dataset.pass = "true";
      togglePass.classList.add("fa-eye");
      togglePass.classList.remove("fa-eye-slash");
      $(this)[0].previousElementSibling.setAttribute("type", "text");
    } else {
      togglePass.dataset.pass = "false";
      togglePass.classList.remove("fa-eye");
      togglePass.classList.add("fa-eye-slash");
      $(this)[0].previousElementSibling.setAttribute("type", "password");
    }
  });

  notificationItems.forEach((item) => {
    item.addEventListener("click", function () {
      var ruta =
        document.getElementById("url-top").value +
        "catalogos/readNotifications.php";
      var notificationId = item.dataset.notification;
      var formData = new FormData();
      formData.append("notificationId", notificationId);
      fetch(ruta, {
        method: "POST",
        body: formData,
      })
        .then(function (res) {
          return res.json();
        })
        .then(function (data) {
          //console.log(data);
          //console.log(document.getElementById("url-top").value+item.dataset.href);
          //console.log(document.getElementById("url-top").value);
          //console.log(item.dataset.href);
          window.location.href = item.dataset.href;
        })
        .catch(function (e) {
          console.log(e);
        });
    });
  });
});
