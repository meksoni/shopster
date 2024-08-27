import axios from "axios";
import bootstrap from "bootstrap/dist/js/bootstrap.js";

window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

document.addEventListener("DOMContentLoaded", function () {
    var toastElList = [].slice.call(document.querySelectorAll(".toast"));
    var toastList = toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl);
    });
});

// Collapse for sidebar
document.querySelectorAll(".collapse-group .collapse").forEach((e) => {
    const t = new bootstrap.Collapse(e, { toggle: false });

    // Check if the collapse element contains an active link
    if (e.querySelector(".active")) {
        t.show();
    }

    e.addEventListener("show.bs.collapse", (a) => {
        a.stopPropagation();
        e.parentElement
            .closest(".collapse")
            .querySelectorAll(".collapse")
            .forEach((e) => {
                const a = bootstrap.Collapse.getInstance(e);
                a !== t && a.hide();
            });
    }),
        e.addEventListener("hide.bs.collapse", (t) => {
            t.stopPropagation();
            e.querySelectorAll(".collapse").forEach((e) => {
                bootstrap.Collapse.getInstance(e).hide();
            });
        });
});

// Modal shown input autoFocus
const myModalEl = document.querySelectorAll(".modal");
myModalEl.forEach(function (el) {
    if (el) {
        // Provera da li element postoji
        el.addEventListener("shown.bs.modal", (event) => {
            event.preventDefault();
            var input = document.querySelector("[autofocus]");
            if (input) {
                // Provera da li input postoji
                input.focus();
            }
        });
    }
});

function showToast(message) {
    var toastContainer = document.getElementById("toastContainer");

    // Dobijanje visine top menija i navbar-a
    var navbarHeight = document
        .querySelector(".navbar-info")
        .getBoundingClientRect().height;

    // Postavljanje visine za toast
    toastContainer.style.top = `${navbarHeight + 10}px`;

    // Postavljanje pozicije na osnovu širine ekrana
    if (window.innerWidth < 768) {
        toastContainer.style.right = "50%";
        toastContainer.style.transform = "translateX(50%)";
    } else {
        toastContainer.style.right = "10px";
        toastContainer.style.transform = "none";
    }

    var toastId = "toast-" + Date.now();
    var toastHTML = `
        <div id="${toastId}" class="toast fade bg-light text-dark mb-3" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000" data-bs-autohide="true">
            <div class="toast-header bg-light rounded align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag-check-fill me-2 text-success" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10.5 3.5a2.5 2.5 0 0 0-5 0V4h5zm1 0V4H15v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4h3.5v-.5a3.5 3.5 0 1 1 7 0m-.646 5.354a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z"/>
                </svg>
                <strong class="me-auto">${message}</strong>
                

                <button class="border-0 bg-white">
                    <svg class="text-dark" data-bs-dismiss="toast" aria-label="Close" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                    </svg>
                </button>
            </div>
        </div>
    `;

    toastContainer.insertAdjacentHTML("beforeend", toastHTML);

    var newToast = document.getElementById(toastId);
    var bootstrapToast = new bootstrap.Toast(newToast);
    bootstrapToast.show();

    newToast.addEventListener("hidden.bs.toast", function () {
        newToast.remove();
    });
}

window.showToast = showToast;
