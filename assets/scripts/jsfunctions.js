window.onload = function() {
    var theme = localStorage.getItem('mytheme');
    if (theme) { // check if a previous theme item has been set by user
        var sheet = document.getElementById('site-style');
        if (theme === 'dark') { // if the dark theme is set in the mytheme item on page load then set ccs to dark
            sheet.setAttribute('href', '/tsfestivals/assets/stylesheets/dark_style.css');
        } else {
            sheet.setAttribute('href', '/tsfestivals/assets/stylesheets/style.css');
        }
    } else {
        switchStyle(); // call the switch style function when user clicks
    }
}

// switch style function to get and set attributes basd on current
function switchStyle() {
    var sheet = document.getElementById('site-style');
    var theme;

    if (sheet.getAttribute('href') === '/tsfestivals/assets/stylesheets/style.css') { // if current stylesheet is default then switch using setattribute
        sheet.setAttribute('href', '/tsfestivals/assets/stylesheets/dark_style.css');
        theme = 'dark';
    } else {
        sheet.setAttribute('href', '/tsfestivals/assets/stylesheets/style.css');
        theme = 'light';
    }

    // Store theme in localStorage
    localStorage.setItem('mytheme', theme);
}

//This will show a pop up to users in the admin panel when confirming certain actions such as deleting or adding a event
function confirmAction(buttonName) {
    return confirm(`Are you sure you want to ${buttonName}?`);
}


//function to toggle the displaying of the button on the registration form - it will be grey and disabled when the checkbox is unselected.
function toggleButtonDisplay(){
    var button  = document.getElementById('register');
    var chkbox = document.getElementById('termsandconds');

    if (chkbox.checked){
        button.disabled = false; //changes state of button if checked
        button.style.background = '#490A3D';
    }else{
        button.disabled = true;
        button.style.background = 'grey';
    }
}
