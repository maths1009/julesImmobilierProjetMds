const buttonDelete = document.querySelector("#delete_btn");
const annuleDelete = document.querySelector("#annule_delete");
buttonDelete.addEventListener("click", function (event) {
  event.preventDefault();
  document.querySelector(".modal").style.display = "flex";
});
annuleDelete.addEventListener("click", function (event) {
  event.preventDefault();
  document.querySelector(".modal").style.display = "none";
});
