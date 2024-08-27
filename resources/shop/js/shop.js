//Jquery
import "./custom/jquery.global";

//Custom JS scripts
import "./custom/custom-bootstrap";
import "./custom/form-validation";
import "./custom/header-sticky";
import "./custom/preloader";
import "./custom/topTop";
import "./custom/nav-indicators";
import "./custom/dark-mode";
import "./custom/header-sticky-reverse";
import "./custom/aos";
import "./custom/glightbox";
import "./custom/isotope-layout";
import "./custom/jarallax";
import "./custom/buttons";
import "./custom/smooth-scroll";
import "./custom/svgInjector";
import "./custom/typed";
import "./custom/rellax";
import "./custom/mouseover";

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
});
