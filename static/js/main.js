$(document).ready(function() {

  // Select 2

  $("#searchCat").select2({
    placeholder: "Filter by category..."
  });
  $("#searchOrg").select2({
    placeholder: "Filter by organization..."
  });

  // Carousel
  $("#events-carousel").carousel({
    interval: 10000
  });

  $("#searchFilterToggle").on('click', function () {
    $("#searchFilter").toggleClass("hide");
  });

  $("#navSearchStartDate, #navSearchEndDate").datetimepicker({
    language: 'en',
    pickTime: false
  });

  /*****************
   *  New event page
   *****************/

  // Datepicker
  var newEventPicker = $("#newEventDate").datetimepicker({
    language: 'en',
    pick12HourFormat: true,
    pickSeconds:false
  });

  // Select 2

  $("#newEventOrg, #newEventCats").select2({
    placeholder: "Select all that apply"
  });

  // Image preview
  var imgInput = $("#newEventImg input"),
      previewImgWrapper = $("#newEventImgPreview"),
      urlRegex = /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)/;

  function updateImage() {
    if (urlRegex.test(imgInput.val())) {
      previewImgWrapper.find("div")
        .text("")
        .css("padding-top", "0");
      previewImgWrapper.find("img").attr("src", imgInput.val());
    }
    else if (imgInput.val() !== "") {
      alertImgError();
    }
    // else do nothing
  }

  function alertImgError() {
    previewImgWrapper.find("img").attr("src", "");
    previewImgWrapper.find("div")
      .text("Oops, looks like that's not an image.")
      .css("padding-top", "23px");
  }

  previewImgWrapper.find("img").on("error", alertImgError);
  imgInput.on("change", updateImage);

  // Initialize for POST requests
  updateImage();

});