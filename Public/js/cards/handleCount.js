function updateCounts() {
  const favorites = JSON.parse(localStorage.getItem("favorites")) || [];
  const compareList = JSON.parse(localStorage.getItem("compareList")) || [];

  const favoritesCount = document.getElementById("favorites-count");
  const compareCount = document.getElementById("compares-count");

  if (favoritesCount) favoritesCount.textContent = favorites.length;
  if (compareCount) compareCount.textContent = compareList.length;
}

document.addEventListener("DOMContentLoaded", () => {
  updateCounts(); // headerdə sayları göstər
});

