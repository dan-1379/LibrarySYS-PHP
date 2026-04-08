/**
 * ELEMENTS FOR NAVIGATION MENU
 * - TOGGLE ASIDE
 * - TOGGLE LIGHT MODE
 */

document.addEventListener("DOMContentLoaded", () => {
    const savedColorMode = localStorage.getItem("colorMode");
    const savedAsideMenuOption = localStorage.getItem("asideMenuOption");
    
    if (savedColorMode === "light") {
        toggleLightMode();
    }

    if (savedAsideMenuOption === "collapsed") {
        toggleAside();
    }
})

/**
 * Opens the sub menu for each module i.e. Books, Members, Loans, Admin
 * @param {*} button The menu button
 * @see https://www.w3schools.com/jsref/prop_element_classlist.asp
 */
function toggleMenu(button) {
    const menuItem = button.parentElement;
    menuItem.classList.toggle('open');
}

/**
 * Opens and closes the aside menu using the arrow.
 * Uses local storage to persist the selected option across pages.
 */
function toggleAside() {
    const aside = document.querySelector("aside");
    aside.classList.toggle("collapsed");
    
    const arrowIcon = document.querySelector(".logo i");
    if (aside.classList.contains("collapsed")) {
        arrowIcon.classList.remove("fa-angle-left");
        arrowIcon.classList.add("fa-angle-right");
    } else {
        arrowIcon.classList.add("fa-angle-left");
        arrowIcon.classList.remove("fa-angle-right");
    }

    localStorage.setItem('asideMenuOption', aside.classList.contains('collapsed') ? 'collapsed' : 'not-collapsed');

}

/**
 * Sets the pages color mode to dark/light.
 * Uses local storage to persist the selected option across pages
 * @see https://www.w3schools.com/js/js_htmldom_navigation.asp
 */
function toggleLightMode() {
    const lightIcon = document.querySelector(".lightMode i");
    const lightText = document.querySelector(".lightModeText");

    const root = document.documentElement;
    root.classList.toggle("light");

    if (root.classList.contains("light")) {
        lightIcon.classList.remove("fa-sun-o");
        lightIcon.classList.add("fa-moon-o");
        lightText.textContent = "Dark Mode";
    } else {
        lightIcon.classList.remove("fa-moon-o");
        lightIcon.classList.add("fa-sun-o");
        lightText.textContent = "Light Mode";
    }

    localStorage.setItem('colorMode', document.documentElement.classList.contains('light') ? 'light' : 'dark');
}

/**
 * ELEMENTS FOR MEMBERS MODULE
 */

const overlay = document.getElementById("overlay");
const editForm = document.getElementById("updateMemberForm");

/**
 * Provides the functionality to update a member when an edit button is clicked.
 * The button is used to identify which row it exists on and therefore the member data to be fetched.
 * The member data is retrieved from the table and displayed in the form for updating.
 * @param {*} editButton 
 */
function showEditMenu(editButton) {
    // https://www.w3schools.com/jsref/met_element_closest.asp
    const row = editButton.closest("tr");
    const cells = row.querySelectorAll("td");

    const submitButton = document.getElementById("submitMemberDetails");
    submitButton.value = "Update Member";
    submitButton.name = "updateMemberDetails";

    const nameSections = cells[1].innerText.split(" ");

    document.getElementById('cFirstName').value = nameSections[0];
    document.getElementById('cLastName').value = nameSections[1];

    document.getElementById('cMemberID').value = cells[0].innerText;
    document.getElementById('cDOB').value = cells[2].innerText;
    document.getElementById('cPhone').value = cells[3].innerText;
    document.getElementById('cEmail').value = cells[4].innerText;

    const addressSections = cells[5].innerText.split(", ");

    document.getElementById('cAddressLine1').value = addressSections[0];
    document.getElementById('cAddressLine2').value = addressSections[1];
    document.getElementById('cCity').value = addressSections[2];

    document.getElementById('cCounty').value = cells[6].innerText.toLowerCase();
    document.getElementById('cEircode').value = addressSections[3];
    document.getElementById('cRegistrationDate').value = cells[7].innerText;
    document.getElementById('cStatus').value = cells[8].innerText === 'Active' ? 'A' : 'I';

    overlay.classList.add("open");
    editForm.classList.add("open");
}

/**
 * Provides the functionality to allow the user to close the edit menu by clicking the cancel button
 * or the overlay. 
 */
function closeEditMenu() {
    overlay.classList.remove("open");
    editForm.classList.remove("open");

    const errorOutputs = document.querySelectorAll(".errorOutput");
    errorOutputs.forEach(error => error.remove());
}

/**
 * Provides the funcionality to add a member when the button is clicked.
 * The same form used for editing a member is used here by changing the button text and
 * value when submitting.
 */
function openAddMenu() {
    const submitButton = document.getElementById("submitMemberDetails");
    submitButton.value = "Add Member";
    submitButton.name = "addMemberDetails";

    document.getElementById('cFirstName').value = "";
    document.getElementById('cLastName').value = "";
    document.getElementById('cDOB').value = "";
    document.getElementById('cPhone').value = "";
    document.getElementById('cEmail').value = "";
    document.getElementById('cAddressLine1').value = "";
    document.getElementById('cAddressLine2').value = "";
    document.getElementById('cCity').value = "";
    document.getElementById('cCounty').value = "";
    document.getElementById('cEircode').value = "";
    document.getElementById('cRegistrationDate').value = "";
    document.getElementById('cStatus').value = "";

    overlay.classList.add("open");
    editForm.classList.add("open");
}

const loanOverlay = document.getElementById("loanOverlay");
const editLoan = document.getElementById("updateLoanForm");

/**
 * Provides the functionality to update loan date and due date of a loan
 * for the purposes of testing.
 * @param {*} editButton 
 */
function showEditLoan(editButton) {
    // https://www.w3schools.com/jsref/met_element_closest.asp
    const row = editButton.closest("tr");
    const cells = row.querySelectorAll("td");

    document.getElementById("cLoanID").value = cells[0].innerText;
    document.getElementById("cLoanDate").value = cells[1].innerText;
    document.getElementById("cDueDate").value = cells[2].innerText;
    document.getElementById("cMemberID").value = cells[3].innerText;

    loanOverlay.classList.add("open");
    editLoan.classList.add("open");
}

/**
 * Provides the functionality to close the edit loan form.
 */
function closeEditLoan() {
    loanOverlay.classList.remove("open");
    editLoan.classList.remove("open");

    const errorOutputs = document.querySelectorAll(".errorOutput");
    errorOutputs.forEach(error => error.remove());
}