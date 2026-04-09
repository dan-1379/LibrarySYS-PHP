/*
 * Title: How To - Toggle Password Visibility
 * Author: W3 Schools
 * Site: w3schools.com
 * Date: 09/04/2026
 * Code Version: N/A
 * Availability: https://www.w3schools.com/howto/howto_js_toggle_password.asp
 * Accessed: 09/04/26
 * Modified: Changed function parameter to pass in a reference to the eye icon under consideration
 *           i.e. eye / slashed eye
*/

function showPassword(eyeIcon) {
    const inputField = document.getElementById("password");

    if (inputField.type === "password") {
        inputField.type = "text";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    } else {
        inputField.type = "password";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    }
}