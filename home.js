function confirmCall(phoneNumber) {
    var confirmed = confirm("Do you want to call " + phoneNumber + "?");
    if (confirmed) {
        window.location.href = "tel:" + phoneNumber;
    }
}