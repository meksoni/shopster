import "./custom/jquery.global";
import "./custom/bootstrap.custom";
import "./custom/page-sidebar";
import "./custom/validation";
import "./custom/choices";
import "simplebar";
import "./custom/dark-mode";
import "./custom/tippy";
import "./custom/preloader";
import "./custom/header";
import "./custom/card-refresh";
import "./custom/checkboxes";
import "./custom/svgInjector";

import Dropzone from "dropzone";

$(document).ready(function () {
    // CSRF TOKEN
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    //

    // DROPZONE CATEGORIES IMAGE FUNCTIONS
    if ($("#image").length) {
        Dropzone.autoDiscover = false;
        const dropzone = new Dropzone("#image", {
            init: function () {
                this.on("addedfile", function (file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url: window.routes.ImageRoute,
            maxFiles: 1,
            paramName: "image",
            addRemoveLinks: true,
            acceptedFiles:
                "image/jpeg,image/png,image/gif,image/svg,image/webp",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (file, response) {
                $("#image_id").val(response.image_id);
            },
        });
    }

    // UPLOAD PRODUCT IMAGE
    if ($("#productImage").length) {
        Dropzone.autoDiscover = false;
        const dropzone = new Dropzone("#productImage", {
            url: window.routes.ProductImageRoute,
            maxFiles: 10,
            paramName: "image",
            addRemoveLinks: true,
            acceptedFiles:
                "image/jpeg,image/png,image/gif,image/svg,image/webp",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (file, response) {
                $("#image_id").val(response.image_id);
                console.log(response);

                var html = `
                    <div class="col-md-3 mb-10" id="image-row-${response.image_id}">
                        <div class="card">
                            <input type="hidden" name="image_array[]" value="${response.image_id}" />
                            <img src="${response.imagePath}" class="card-img-top" alt="" />
                            <div class="card-body">
                                <a href="javascript:void(0)" data-id="${response.image_id}" class="btn btn-danger delete-image">Delete</a>    
                            </div>    
                        </div>
                    </div>
                `;
                $("#product-gallery").append(html);
            },
            complete: function (file) {
                this.removeFile(file);
            },
        });
    }

    // UPDATE PRODUCT IMAGE
    // UPLOAD PRODUCT IMAGE
    if ($("#updateProductImage").length) {
        Dropzone.autoDiscover = false;
        const dropzone = $("#updateProductImage").dropzone({
            url: window.routes.UpdateImageRoute,
            maxFiles: 10,
            paramName: "image",
            params: {
                product_id: $("#product_id").val(), // Koristimo vrednost iz skrivenog polja
            },
            addRemoveLinks: true,
            acceptedFiles:
                "image/jpeg,image/png,image/gif,image/svg,image/webp",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (file, response) {
                // Obrada uspešnog odgovora
                var html = `
                <div class="col-md-3 mb-10" id="image-row-${response.image_id}">
                    <div class="card">
                        <input type="hidden" name="image_array[]" value="${response.image_id}" />
                        <img src="${response.imagePath}" class="card-img-top" alt="" />
                        <div class="card-body">
                            <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Delete</a>    
                        </div>    
                    </div>
                </div>
            `;
                $("#product-gallery").append(html);
            },
            complete: function (file) {
                this.removeFile(file);
            },
        });
    }

    // Definicija deleteImage funkcije unutar $(document).ready
    function deleteImage(id) {
        $.ajax({
            url: window.routes.DeleteImageRoute,
            type: "DELETE",
            data: { id: id },
            success: function (response) {
                $("#image-row-" + id).remove();
            },
        });
    }

    $(document).on("click", ".delete-image", function () {
        const imageId = $(this).data("id");
        deleteImage(imageId);
    });
});
