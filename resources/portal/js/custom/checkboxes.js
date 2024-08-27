// Provera da li su checkboxovi prisutni na stranici
const shouldActivateCheckboxes = () => {
    const parentCheckboxes = document.querySelectorAll(".js-check-all");
    return parentCheckboxes.length > 0;
};

// Funkcija koja aktivira checkboxove ako su prisutni na stranici
const activateCheckboxes = () => {
    const parentCheckboxes = document.querySelectorAll(".js-check-all");
    for (let i = 0; i < parentCheckboxes.length; i++) {
        const parentCheckbox = parentCheckboxes[i];
        const parentId =
            parentCheckbox.parentElement.getAttribute("data-check-id");
        const childCheckboxes = document.querySelectorAll(
            '.form-check[data-check-id="' + parentId + '"] .js-checkbox'
        );
        checkParentCheckbox(parentCheckbox, childCheckboxes);
        parentCheckbox.addEventListener("click", function () {
            checkAllChildCheckboxes(parentCheckbox, childCheckboxes);
        });

        for (let j = 0; j < childCheckboxes.length; j++) {
            childCheckboxes[j].addEventListener("click", function () {
                checkParentCheckbox(parentCheckbox, childCheckboxes);
            });
        }
    }
};

// Provera da li treba aktivirati checkboxove i aktiviranje ako je potrebno
if (shouldActivateCheckboxes()) {
    activateCheckboxes();
}

// Ostatak vaše checkboxes.js skripte
function checkParentCheckbox(parentCheckbox, childCheckboxes) {
    let checkedCount = 0;
    for (let i = 0; i < childCheckboxes.length; i++) {
        if (childCheckboxes[i].checked) {
            checkedCount++;
        }
    }
    // Update parent checkbox state
    if (checkedCount === childCheckboxes.length) {
        parentCheckbox.checked = true;
        parentCheckbox.indeterminate = false;
    } else if (checkedCount === 0) {
        parentCheckbox.checked = false;
        parentCheckbox.indeterminate = false;
    } else {
        parentCheckbox.checked = false;
        parentCheckbox.indeterminate = true;
    }
}

function checkAllChildCheckboxes(parentCheckbox, childCheckboxes) {
    // Check or uncheck all child checkboxes based on parent state
    for (let i = 0; i < childCheckboxes.length; i++) {
        childCheckboxes[i].checked = parentCheckbox.checked;
    }
    // Update parent checkbox state
    checkParentCheckbox(parentCheckbox, childCheckboxes);
}
