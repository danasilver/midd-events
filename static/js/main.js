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

  var newEventPicker = $("#newEventDate").datetimepicker({
    language: 'en',
    pick12HourFormat: true,
    pickSeconds:false
  });
});