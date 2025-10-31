var select = document.getElementById("selectCountryCode");
var options = ["91", "1", "44", "61", "81"];

for (option in options) {
  select.add(new Option(options[option]));
}

function validate() {
  var name = document.form1.name.value;
  var email = document.form1.email1.value;
  var phone = document.form1.phone.value;
  var password1 = document.form1.pwd1.value;
  var password2 = document.form1.pwd2.value;

  // Name validation
  if (name.trim() === "") {
    alert("Please provide your name!");
    document.form1.name.focus();
    return false;
  } else if (!ValidateName(name)) {
    alert("Please enter a valid name.");
    return false;
  }

  // Email validation
  if (email.trim() === "") {
    alert("Please provide your Email!");
    document.form1.email1.focus();
    return false;
  } else if (!ValidateEmail(email)) {
    alert("Please enter a valid email address.");
    return false;
  }

  // Phone validation
  if (phone.trim() === "") {
    alert("Please provide your Phone Number!");
    document.form1.phone.focus();
    return false;
  } else if (!phonenumber(phone)) {
    alert("Please enter a valid phone number.");
    return false;
  }

  // Password validation
  if (password1.trim() === "") {
    alert("Please create a password.");
    document.form1.pwd1.focus();
    return false;
  } else if (!ValidatePassword(document.form1)) {
    return false;
  }

  // Password match validation
  if (password1 !== password2) {
    alert("Passwords do not match.");
    return false;
  }

  // If all validations pass, return true to submit the form
  return true;
}
