const preLoader = () => {
  const preloader = document.getElementById("ctn-preloader");

  window.addEventListener("load", () => {
    preloader.classList.add("loaded");

    setTimeout(() => {
      preloader.style.display = "none";
    }, 500); // match CSS transition
  });
};

preLoader();

// Home Slider
var swiper = new Swiper(".hero__slider--activation", {
  slidesPerView: 1,
  loop: true,
  clickable: true,
  speed: 500,
  spaceBetween: 30,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
});

// Shpi by Category
var swiper = new Swiper(".shop__collection--column5", {
  slidesPerView: 5,
  loop: true,
  clickable: true,
  spaceBetween: 30,
  breakpoints: {
    1200: {
      slidesPerView: 5,
    },
    992: {
      slidesPerView: 4,
      spaceBetween: 30,
    },
    768: {
      slidesPerView: 4,
      spaceBetween: 25,
    },
    576: {
      slidesPerView: 3,
      spaceBetween: 20,
    },
    200: {
      slidesPerView: 2,
      spaceBetween: 15,
    },
    0: {
      slidesPerView: 1,
    },
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
});

document.addEventListener("DOMContentLoaded", function () {
  const path = window.location.pathname;
  if (path.includes("/checkout")) {
    counter('address', 'addressCount', 100);
    counter('remarks', 'remarksCount', 100);
  }
});

// Scroll up activation
const scrollTop = document.getElementById("scroll__top");
if (scrollTop) {
  scrollTop.addEventListener("click", function () {
    window.scroll({ top: 0, left: 0, behavior: "smooth" });
  });
  window.addEventListener("scroll", function () {
    if (window.scrollY > 300) {
      scrollTop.classList.add("active");
    } else {
      scrollTop.classList.remove("active");
    }
  });
}

function onlyAlphabets(input) {
  input.value = input.value.replace(/[^a-zA-Z\s]/g, "");
}

function validateEmail(input) {
  let value = input.value;
  value = value.toLowerCase();
  value = value.replace(/\s/g, "");
  value = value.replace(/[^a-z0-9@._]/g, "");
  input.value = value;
}

function validatePhone(input) {
  let value = input.value;
  value = value.replace(/\s/g, "");
  value = value.replace(/[^0-9+]/g, "");
  if (value.indexOf('+') > 0) {
    value = value.replace(/\+/g, '');
  }

  // Limit length (e.g. +923001234567 = 13 chars)
  if (value.startsWith('+')) {
    value = value.substring(0, 13);
  } else {
    value = value.substring(0, 11);
  }

  input.value = value;
}

function counter(taId, countId, max) {
    const ta = document.getElementById(taId);
    const ct = document.getElementById(countId);

    function update() {
        const n = ta.value.length;
        ct.textContent = n + ' / ' + max;
        ct.className = 'char-counter' + (n >= max ? ' over' : n > max * .85 ? ' warn' : '');
    }
    ta.addEventListener('input', update);
    return update;
}

function showAlert(type, message) {
  const alert =
    type === "success"
      ? document.getElementById("successAlert")
      : document.getElementById("errorAlert");
  const text =
    type === "success"
      ? document.getElementById("successAlertText")
      : document.getElementById("errorAlertText");

  text.innerHTML = message;
  alert.classList.add("show");
  alert.style.display = "block";

  setTimeout(() => {
    if (type === "success") hideSuccessAlert();
    else hideErrorAlert();
  }, 5000);
}

function hideSuccessAlert() {
  const alert = document.getElementById("successAlert");
  alert.classList.remove("show");
  setTimeout(() => (alert.style.display = "none"), 150); // Smooth hide
}

function hideErrorAlert() {
  const alert = document.getElementById("errorAlert");
  alert.classList.remove("show");
  setTimeout(() => (alert.style.display = "none"), 150); // Smooth hide
}
