window.addEventListener("DOMContentLoaded", init);
function init() {
    const name = document.querySelector('#name');
    const desc = document.querySelector('#description');
    const date = document.querySelector('#date');
    const location = document.querySelector('#location');
    const updateEventFrm = document.querySelector('#updateEvent');
    const img = document.querySelector('#img');
    let today=new Date();
    let dd=String(today.getDate()).padStart(2,'0');
    let mm=String(today.getMonth()+1).padStart(2,'0');
    let yyy=today.getFullYear();
    let hours=today.getHours();
    let minutes=today.getMinutes();
    today=yyy+'-'+mm+'-'+dd+"T"+hours+":"+minutes;
    date.min=today;
    updateEventFrm.addEventListener('submit', function (e) {
        e.preventDefault();
        if (validateAdd()) this.submit();
    });

    let validateAdd = () => {
        let isValid = true;

        if (isEmpty(location.value.trim())) {
            showErrorMessage(location, "Molimo unesite lokaciju");
            isValid = false;
        }
        // else if (!isValidLoaction(name.value.trim())) {
        //     showErrorMessage(location, "Unos mora da bude u formatu drzava,\u2423grad");
        //     isValid = false;
        // }
        else {
            hideErrorMessage(location);
        }

        if (isEmpty(desc.value.trim())) {
            showErrorMessage(desc, "Unesite neki opis");
            isValid = false;
        } else {
            hideErrorMessage(desc);
        }

        if (isEmpty(name.value.trim())) {
            showErrorMessage(name, "Molimo unesite ime");
            isValid = false;
        } else {
            hideErrorMessage(name);
        }

        if (isEmpty(date.value)) {
            showErrorMessage(date, "Molimo izaberite datum");
            isValid = false;
        } else {
            hideErrorMessage(date);
        }
        //
        // if (isEmpty(date.value)){
        //     showErrorMessage(name, "Molimo unesite datum");
        //     isValid = false;
        // } else {
        //     hideErrorMessage(name);
        // }
        /*if (img.files.length==0){
            showErrorMessage(img, "Molimo ubacite sliku");
            isValid = false;
        } else {
            hideErrorMessage(img);
        }*/

        return isValid;
    }
}
const isEmpty = value => value === '';
const isValidLoaction = (location) => {
    let rex = /\w*\S,\s\S\w*/g;
    let pattern = location.match(rex);
    if(pattern!=null){
        if(pattern.length==1) {
            return true;
        }
    }else if(pattern==null){
        return false;
    }
}
const showErrorMessage = (field, message) => {
    const error = field.nextElementSibling;
    error.innerText = message;
};
const hideErrorMessage = (field) => {
    const error = field.nextElementSibling;
    error.innerText = '';
}