function toggleAnswer(id) {
  const answer = document.getElementById(`faq-answer-${id}`);
  answer.style.display = answer.style.display === "none" ? "block" : "none";
  const questionIcon = document.querySelector(
    `#faq-answer-${id} .question-icon`
  );
  questionIcon.classList.toggle("collapsed");
}

// QUESTIONARY

document
  .getElementById("questionnaire")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent form submission

    // Get form values
    var propertyType = document.querySelector(
      'input[name="property-type"]:checked'
    ).value;
    var bedrooms = document.getElementById("bedrooms").value;
    var bathrooms = document.getElementById("bathrooms").value;
    var address = document.getElementById("address").value;
    var description = document.getElementById("description").value;
    var laundryType = document.getElementById("laundry-type").value;
    var parkingType = document.querySelector(
      'input[name="parking-type"]:checked'
    ).value;
    var acType = document.querySelector('input[name="ac-type"]:checked').value;
    var heatingAvailable = document.querySelector(
      'input[name="heating-available"]:checked'
    ).value;

    // Get selected amenities
    var amenities = [];
    var amenitiesCheckboxes = document.querySelectorAll(
      'input[name="amenities"]:checked'
    );
    for (var i = 0; i < amenitiesCheckboxes.length; i++) {
      amenities.push(amenitiesCheckboxes[i].value);
    }

    // Create object to store form data
    var formData = {
      propertyType: propertyType,
      bedrooms: bedrooms,
      bathrooms: bathrooms,
      address: address,
      description: description,
      laundryType: laundryType,
      parkingType: parkingType,
      acType: acType,
      heatingAvailable: heatingAvailable,
      amenities: amenities,
    };

    // Print form data to console (replace with your own logic to save the data)
    console.log(formData);

    // Reset form
    event.target.reset();
  });
